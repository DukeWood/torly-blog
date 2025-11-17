#!/usr/bin/env node

/**
 * SSL Setup and Verification Script
 *
 * This script handles DNS propagation verification and SSL certificate
 * validation for torly.ai and blog.torly.ai
 *
 * Usage: node automation/ssl-setup.js --domain torly.ai --ip <VM_IP>
 */

import { execSync } from 'child_process';
import dns from 'dns';
import { promisify } from 'util';
import tls from 'tls';
import https from 'https';

const resolveDns = promisify(dns.resolve4);

// Configuration
const CONFIG = {
  maxDnsWaitMinutes: 15,
  dnsCheckIntervalSeconds: 30,
  sslCheckRetries: 3,
};

class SSLSetupManager {
  constructor(options = {}) {
    this.domain = options.domain || 'torly.ai';
    this.subdomains = options.subdomains || ['www', 'blog'];
    this.expectedIP = options.expectedIP;
  }

  /**
   * Check if DNS is propagated for a domain
   */
  async checkDNSPropagation(domain, expectedIP) {
    try {
      console.log(`🌐 Checking DNS for ${domain}...`);
      const addresses = await resolveDns(domain);

      if (addresses.includes(expectedIP)) {
        console.log(`  ✅ DNS propagated: ${domain} → ${expectedIP}`);
        return true;
      } else {
        console.log(`  ⏳ DNS not propagated yet. Found: ${addresses.join(', ')}`);
        return false;
      }
    } catch (error) {
      if (error.code === 'ENOTFOUND') {
        console.log(`  ⏳ DNS record not found yet for ${domain}`);
      } else {
        console.log(`  ⚠️  DNS check error: ${error.message}`);
      }
      return false;
    }
  }

  /**
   * Wait for DNS propagation with timeout
   */
  async waitForDNSPropagation(domain, expectedIP) {
    console.log(`\n⏳ Waiting for DNS propagation for ${domain}...`);
    console.log(`   Expected IP: ${expectedIP}`);
    console.log(`   Max wait time: ${CONFIG.maxDnsWaitMinutes} minutes\n`);

    const startTime = Date.now();
    const timeoutMs = CONFIG.maxDnsWaitMinutes * 60 * 1000;

    let attempt = 0;
    while (Date.now() - startTime < timeoutMs) {
      attempt++;
      console.log(`Attempt ${attempt}:`);

      const propagated = await this.checkDNSPropagation(domain, expectedIP);

      if (propagated) {
        const elapsedMinutes = ((Date.now() - startTime) / 1000 / 60).toFixed(1);
        console.log(`\n✅ DNS propagated in ${elapsedMinutes} minutes\n`);
        return true;
      }

      console.log(`  Waiting ${CONFIG.dnsCheckIntervalSeconds} seconds before next check...\n`);
      await new Promise(resolve => setTimeout(resolve, CONFIG.dnsCheckIntervalSeconds * 1000));
    }

    console.log(`\n❌ DNS propagation timeout after ${CONFIG.maxDnsWaitMinutes} minutes\n`);
    return false;
  }

  /**
   * Wait for all domains to propagate
   */
  async waitForAllDNS() {
    console.log('🔍 Checking DNS propagation for all domains...\n');

    const domains = [
      this.domain,
      ...this.subdomains.map(sub => `${sub}.${this.domain}`)
    ];

    const results = {};

    for (const domain of domains) {
      results[domain] = await this.waitForDNSPropagation(domain, this.expectedIP);

      if (!results[domain]) {
        console.log(`⚠️  Warning: ${domain} DNS not propagated`);
        console.log(`   SSL certificate issuance may fail for this domain\n`);
      }

      // Small delay between domain checks
      await new Promise(resolve => setTimeout(resolve, 2000));
    }

    const allPropagated = Object.values(results).every(r => r);

    if (allPropagated) {
      console.log('✅ All domains have propagated DNS\n');
    } else {
      console.log('⚠️  Some domains have not propagated. Proceeding anyway...\n');
    }

    return results;
  }

  /**
   * Verify SSL certificate for a domain
   */
  async verifySSL(domain) {
    console.log(`🔒 Verifying SSL certificate for ${domain}...`);

    return new Promise((resolve) => {
      const socket = tls.connect({
        host: domain,
        port: 443,
        servername: domain,
        rejectUnauthorized: false,
      }, () => {
        const cert = socket.getPeerCertificate();
        const authorized = socket.authorized;

        if (!cert || !cert.subject) {
          console.log(`  ❌ No certificate found`);
          socket.end();
          resolve(false);
          return;
        }

        const validFrom = new Date(cert.valid_from);
        const validTo = new Date(cert.valid_to);
        const now = new Date();
        const daysRemaining = Math.floor((validTo - now) / (1000 * 60 * 60 * 24));

        console.log(`  Certificate Details:`);
        console.log(`    Subject: ${cert.subject.CN}`);
        console.log(`    Issuer: ${cert.issuer.O}`);
        console.log(`    Valid From: ${cert.valid_from}`);
        console.log(`    Valid To: ${cert.valid_to}`);
        console.log(`    Days Remaining: ${daysRemaining}`);
        console.log(`    Protocol: ${socket.getProtocol()}`);

        if (authorized) {
          console.log(`  ✅ SSL certificate is valid and trusted`);
          socket.end();
          resolve(true);
        } else {
          console.log(`  ⚠️  Certificate validation issue: ${socket.authorizationError}`);
          socket.end();
          resolve(false);
        }
      });

      socket.on('error', (error) => {
        console.log(`  ❌ SSL connection error: ${error.message}`);
        resolve(false);
      });

      socket.setTimeout(10000);
      socket.on('timeout', () => {
        console.log(`  ❌ Connection timeout`);
        socket.end();
        resolve(false);
      });
    });
  }

  /**
   * Check if HTTPS is accessible
   */
  async checkHTTPSAccess(url) {
    console.log(`🌐 Checking HTTPS access: ${url}...`);

    return new Promise((resolve) => {
      const request = https.get(url, { timeout: 10000 }, (res) => {
        console.log(`  ✅ HTTPS accessible (Status: ${res.statusCode})`);
        resolve(true);
      });

      request.on('error', (error) => {
        console.log(`  ❌ HTTPS error: ${error.message}`);
        resolve(false);
      });

      request.on('timeout', () => {
        console.log(`  ❌ HTTPS timeout`);
        request.destroy();
        resolve(false);
      });
    });
  }

  /**
   * Run comprehensive SSL verification
   */
  async verifyAllSSL() {
    console.log('\n🔐 Starting SSL verification for all domains...\n');

    const domains = [
      this.domain,
      ...this.subdomains.map(sub => `${sub}.${this.domain}`)
    ];

    const results = {};

    for (const domain of domains) {
      console.log(`\n${'='.repeat(60)}`);
      console.log(`Verifying: ${domain}`);
      console.log('='.repeat(60));

      // Check HTTPS access
      const httpsAccessible = await this.checkHTTPSAccess(`https://${domain}`);

      if (!httpsAccessible) {
        console.log(`⚠️  HTTPS not accessible for ${domain}\n`);
        results[domain] = false;
        continue;
      }

      // Verify SSL certificate
      const sslValid = await this.verifySSL(domain);
      results[domain] = sslValid;

      await new Promise(resolve => setTimeout(resolve, 1000));
    }

    // Print summary
    console.log(`\n${'='.repeat(60)}`);
    console.log('SSL VERIFICATION SUMMARY');
    console.log('='.repeat(60));

    for (const [domain, valid] of Object.entries(results)) {
      const status = valid ? '✅' : '❌';
      console.log(`${status} ${domain}`);
    }

    const allValid = Object.values(results).every(r => r);

    if (allValid) {
      console.log('\n✅ All SSL certificates are valid and trusted!\n');
    } else {
      console.log('\n⚠️  Some SSL certificates need attention\n');
    }

    return results;
  }

  /**
   * Test SSL A+ rating configuration
   */
  async testSSLRating(domain) {
    console.log(`\n🏆 Testing SSL configuration for A+ rating...`);
    console.log(`   Domain: ${domain}\n`);

    try {
      const socket = tls.connect({
        host: domain,
        port: 443,
        servername: domain,
      }, () => {
        const protocol = socket.getProtocol();
        const cipher = socket.getCipher();

        console.log(`  Protocol: ${protocol}`);
        console.log(`  Cipher: ${cipher.name} (${cipher.version})`);

        // Check for recommended protocols and ciphers
        const goodProtocol = protocol === 'TLSv1.3' || protocol === 'TLSv1.2';
        const goodCipher = cipher.name.includes('GCM') || cipher.name.includes('CHACHA20');

        if (goodProtocol && goodCipher) {
          console.log(`  ✅ Strong SSL configuration detected`);
        } else {
          console.log(`  ⚠️  SSL configuration could be improved`);
        }

        socket.end();
      });

      socket.on('error', (error) => {
        console.log(`  ❌ Error: ${error.message}`);
      });
    } catch (error) {
      console.log(`  ❌ Test failed: ${error.message}`);
    }
  }

  /**
   * Full SSL setup workflow
   */
  async run() {
    console.log('🚀 Starting SSL Setup and Verification\n');

    if (!this.expectedIP) {
      console.log('❌ Error: Expected IP address not provided');
      console.log('   Usage: node ssl-setup.js --domain torly.ai --ip <VM_IP>');
      return false;
    }

    // Step 1: Wait for DNS propagation
    console.log('Step 1: DNS Propagation');
    console.log('------------------------');
    const dnsResults = await this.waitForAllDNS();

    // Step 2: Verify SSL certificates
    console.log('\nStep 2: SSL Verification');
    console.log('------------------------');

    // Wait a bit after DNS propagation before checking SSL
    console.log('Waiting 30 seconds for SSL certificate issuance...\n');
    await new Promise(resolve => setTimeout(resolve, 30000));

    const sslResults = await this.verifyAllSSL();

    // Step 3: Test SSL configuration
    console.log('\nStep 3: SSL Configuration Test');
    console.log('------------------------------');
    await this.testSSLRating(this.domain);

    // Final summary
    const allDnsPropagated = Object.values(dnsResults).every(r => r);
    const allSslValid = Object.values(sslResults).every(r => r);

    console.log('\n' + '='.repeat(60));
    console.log('FINAL SUMMARY');
    console.log('='.repeat(60));
    console.log(`DNS Propagation: ${allDnsPropagated ? '✅ Complete' : '⚠️  Partial'}`);
    console.log(`SSL Certificates: ${allSslValid ? '✅ Valid' : '⚠️  Needs Attention'}`);
    console.log('='.repeat(60) + '\n');

    return allDnsPropagated && allSslValid;
  }
}

// Parse command line arguments
function parseArgs() {
  const args = process.argv.slice(2);
  const options = {};

  for (let i = 0; i < args.length; i++) {
    if (args[i] === '--domain' && args[i + 1]) {
      options.domain = args[i + 1];
      i++;
    } else if (args[i] === '--ip' && args[i + 1]) {
      options.expectedIP = args[i + 1];
      i++;
    } else if (args[i] === '--subdomains' && args[i + 1]) {
      options.subdomains = args[i + 1].split(',');
      i++;
    }
  }

  return options;
}

// Run if called directly
if (import.meta.url === `file://${process.argv[1]}`) {
  const options = parseArgs();

  const manager = new SSLSetupManager(options);
  manager.run()
    .then((success) => {
      process.exit(success ? 0 : 1);
    })
    .catch((error) => {
      console.error('\n❌ SSL setup failed:', error.message);
      process.exit(1);
    });
}

export default SSLSetupManager;
