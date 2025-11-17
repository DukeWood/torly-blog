#!/usr/bin/env node

/**
 * Master Automation Orchestration Script for TorlyAI
 *
 * This script orchestrates the entire deployment process:
 * 1. Oracle Cloud VM setup
 * 2. DNS configuration via GoDaddy API
 * 3. WordPress deployment
 * 4. SSL certificate setup
 * 5. Blog structure creation
 * 6. Content publication
 *
 * Usage: node automation/deploy-all.js [--skip-oracle] [--skip-content]
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { execSync } from 'child_process';
import OracleCloudAutomation from './oracle-cloud-setup.js';
import SSLSetupManager from './ssl-setup.js';
import ContentPublisher from './content-publisher.js';
import axios from 'axios';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Configuration
const CONFIG = {
  credentialsFile: path.join(__dirname, '../.credentials/oracle_credentials.json'),
  godaddyCredentialsFile: path.join(__dirname, '../.credentials/godaddy_credentials.json'),
  deploymentScript: path.join(__dirname, '../deployment/deploy-script.sh'),
  domain: 'torly.ai',
  blogSubdomain: 'blog.torly.ai',
};

class MasterDeployment {
  constructor(options = {}) {
    this.skipOracle = options.skipOracle || false;
    this.skipContent = options.skipContent || false;
    this.credentials = {};
    this.godaddyCredentials = {};
  }

  /**
   * Print step header
   */
  printStep(stepNumber, stepName) {
    console.log('\n' + '='.repeat(70));
    console.log(`STEP ${stepNumber}: ${stepName}`);
    console.log('='.repeat(70) + '\n');
  }

  /**
   * Load Oracle Cloud credentials
   */
  loadOracleCredentials() {
    if (!fs.existsSync(CONFIG.credentialsFile)) {
      throw new Error(`Oracle credentials not found: ${CONFIG.credentialsFile}`);
    }

    this.credentials = JSON.parse(fs.readFileSync(CONFIG.credentialsFile, 'utf8'));
    console.log('✅ Oracle Cloud credentials loaded');
    console.log(`   VM IP: ${this.credentials.vmIP}`);
    console.log(`   SSH Key: ${this.credentials.sshPrivateKeyPath}`);
  }

  /**
   * Load GoDaddy credentials
   */
  loadGoDaddyCredentials() {
    if (!fs.existsSync(CONFIG.godaddyCredentialsFile)) {
      console.log('\n📝 GoDaddy credentials not found. Let\'s set them up:');
      this.godaddyCredentials = this.promptGoDaddyCredentials();
      this.saveGoDaddyCredentials();
    } else {
      this.godaddyCredentials = JSON.parse(fs.readFileSync(CONFIG.godaddyCredentialsFile, 'utf8'));
      console.log('✅ GoDaddy credentials loaded');
    }
  }

  /**
   * Prompt for GoDaddy credentials
   */
  promptGoDaddyCredentials() {
    console.log('\nTo configure DNS automatically, we need your GoDaddy API credentials.');
    console.log('You can get them from: https://developer.godaddy.com/keys\n');

    const readline = require('readline').createInterface({
      input: process.stdin,
      output: process.stdout
    });

    return new Promise((resolve) => {
      readline.question('GoDaddy API Key: ', (apiKey) => {
        readline.question('GoDaddy API Secret: ', (apiSecret) => {
          readline.close();
          resolve({ apiKey: apiKey.trim(), apiSecret: apiSecret.trim() });
        });
      });
    });
  }

  /**
   * Save GoDaddy credentials
   */
  saveGoDaddyCredentials() {
    const credDir = path.dirname(CONFIG.godaddyCredentialsFile);
    if (!fs.existsSync(credDir)) {
      fs.mkdirSync(credDir, { recursive: true });
    }

    fs.writeFileSync(
      CONFIG.godaddyCredentialsFile,
      JSON.stringify(this.godaddyCredentials, null, 2),
      'utf8'
    );
    fs.chmodSync(CONFIG.godaddyCredentialsFile, 0o600);
    console.log('✅ GoDaddy credentials saved');
  }

  /**
   * Step 1: Oracle Cloud VM Setup
   */
  async setupOracleCloud() {
    this.printStep(1, 'Oracle Cloud VM Setup');

    if (this.skipOracle) {
      console.log('⏭  Skipping Oracle Cloud setup (--skip-oracle flag)');
      this.loadOracleCredentials();
      return;
    }

    const automation = new OracleCloudAutomation();
    this.credentials = await automation.run();
  }

  /**
   * Step 2: Configure DNS
   */
  async configureDNS() {
    this.printStep(2, 'DNS Configuration via GoDaddy API');

    await this.loadGoDaddyCredentials();

    console.log('🌐 Configuring DNS records for torly.ai...\n');

    const godaddyApi = axios.create({
      baseURL: 'https://api.godaddy.com/v1',
      headers: {
        'Authorization': `sso-key ${this.godaddyCredentials.apiKey}:${this.godaddyCredentials.apiSecret}`,
        'Content-Type': 'application/json',
      },
    });

    const records = [
      {
        type: 'A',
        name: '@',
        data: this.credentials.vmIP,
        ttl: 3600,
      },
      {
        type: 'A',
        name: 'www',
        data: this.credentials.vmIP,
        ttl: 3600,
      },
      {
        type: 'A',
        name: 'blog',
        data: this.credentials.vmIP,
        ttl: 3600,
      },
    ];

    try {
      console.log('Updating DNS records:');
      records.forEach(r => {
        console.log(`  ${r.type} ${r.name === '@' ? CONFIG.domain : r.name + '.' + CONFIG.domain} → ${r.data}`);
      });

      await godaddyApi.patch(`/domains/${CONFIG.domain}/records`, records);

      console.log('\n✅ DNS records updated successfully');
      console.log('⏳ DNS propagation may take 5-30 minutes\n');
    } catch (error) {
      console.error('❌ DNS update failed:', error.response?.data?.message || error.message);
      console.log('\n⚠️  You may need to configure DNS manually:');
      console.log('   1. Go to GoDaddy DNS Management');
      console.log(`   2. Add A records pointing to ${this.credentials.vmIP}`);
      console.log('   3. Records needed: @, www, blog');
      throw error;
    }
  }

  /**
   * Step 3: Deploy WordPress
   */
  async deployWordPress() {
    this.printStep(3, 'WordPress Deployment to Oracle Cloud VM');

    console.log('📦 Deploying WordPress via SSH...\n');

    try {
      // Copy repository to VM
      console.log('1️⃣  Copying deployment files to VM...');
      execSync(
        `scp -i ${this.credentials.sshPrivateKeyPath} -r -o StrictHostKeyChecking=no ${path.join(__dirname, '..')} ${this.credentials.sshUsername}@${this.credentials.vmIP}:/tmp/torly-wordpress-setup`,
        { stdio: 'inherit' }
      );

      // Execute deployment script
      console.log('\n2️⃣  Running deployment script...');
      console.log('   (This may take 15-30 minutes)\n');

      execSync(
        `ssh -i ${this.credentials.sshPrivateKeyPath} -o StrictHostKeyChecking=no ${this.credentials.sshUsername}@${this.credentials.vmIP} "sudo bash /tmp/torly-wordpress-setup/deployment/deploy-script.sh"`,
        { stdio: 'inherit' }
      );

      console.log('\n✅ WordPress deployed successfully');
    } catch (error) {
      console.error('❌ WordPress deployment failed:', error.message);
      throw error;
    }
  }

  /**
   * Step 4: SSL Setup and Verification
   */
  async setupSSL() {
    this.printStep(4, 'SSL Certificate Setup and Verification');

    const sslManager = new SSLSetupManager({
      domain: CONFIG.domain,
      subdomains: ['www', 'blog'],
      expectedIP: this.credentials.vmIP,
    });

    const success = await sslManager.run();

    if (!success) {
      console.log('\n⚠️  SSL setup had issues, but continuing deployment');
      console.log('   You may need to troubleshoot SSL manually');
    }
  }

  /**
   * Step 5: Configure WordPress and Blog Structure
   */
  async configureWordPress() {
    this.printStep(5, 'WordPress Configuration and Blog Structure');

    console.log('⚙️  Configuring WordPress via SSH...\n');

    try {
      // Get WordPress admin credentials from deployment
      console.log('1️⃣  Retrieving WordPress admin credentials...');

      const sshCmd = `ssh -i ${this.credentials.sshPrivateKeyPath} -o StrictHostKeyChecking=no ${this.credentials.sshUsername}@${this.credentials.vmIP}`;

      // Configure multisite for blog subdomain
      console.log('\n2️⃣  Configuring WordPress Multisite for blog.torly.ai...');

      execSync(
        `${sshCmd} "sudo wp --path=/var/www/html --allow-root config set WP_ALLOW_MULTISITE true --raw"`,
        { stdio: 'inherit' }
      );

      execSync(
        `${sshCmd} "sudo wp --path=/var/www/html --allow-root core multisite-install --title='TorlyAI Network' --subdomains"`,
        { stdio: 'inherit' }
      );

      execSync(
        `${sshCmd} "sudo wp --path=/var/www/html --allow-root site create --slug=blog --title='TorlyAI Blog'"`,
        { stdio: 'inherit' }
      );

      console.log('\n✅ WordPress Multisite configured');
      console.log(`   Blog URL: https://${CONFIG.blogSubdomain}`);
    } catch (error) {
      console.error('❌ WordPress configuration failed:', error.message);
      throw error;
    }
  }

  /**
   * Step 6: Publish Content
   */
  async publishContent() {
    this.printStep(6, 'Blog Content Publication');

    if (this.skipContent) {
      console.log('⏭  Skipping content publication (--skip-content flag)');
      return;
    }

    console.log('📝 Publishing blog posts...\n');

    // Need WordPress credentials
    console.log('⚠️  Note: Content publication requires WordPress application password');
    console.log('   Create one at: https://torly.ai/wp-admin/profile.php');
    console.log('   Then set environment variables:');
    console.log('   - WP_SITE_URL=https://torly.ai');
    console.log('   - WP_USERNAME=admin');
    console.log('   - WP_APP_PASSWORD=<your_app_password>\n');

    if (!process.env.WP_APP_PASSWORD) {
      console.log('⏭  Skipping content publication (WP_APP_PASSWORD not set)');
      console.log('   Run later with: WP_APP_PASSWORD=xxx node automation/content-publisher.js');
      return;
    }

    try {
      const publisher = new ContentPublisher();
      await publisher.publishAll();

      console.log('\n✅ Content published successfully');
    } catch (error) {
      console.error('⚠️  Content publication failed:', error.message);
      console.log('   You can publish content later with: node automation/content-publisher.js');
    }
  }

  /**
   * Final verification
   */
  async finalVerification() {
    this.printStep(7, 'Final Verification');

    console.log('🔍 Verifying deployment...\n');

    const checks = [
      { name: 'Main site (HTTPS)', url: `https://${CONFIG.domain}` },
      { name: 'WWW redirect', url: `https://www.${CONFIG.domain}` },
      { name: 'Blog site', url: `https://${CONFIG.blogSubdomain}` },
      { name: 'WordPress admin', url: `https://${CONFIG.domain}/wp-admin` },
    ];

    for (const check of checks) {
      try {
        const response = await axios.get(check.url, { timeout: 10000, maxRedirects: 5 });
        console.log(`✅ ${check.name}: OK (Status: ${response.status})`);
      } catch (error) {
        console.log(`⚠️  ${check.name}: ${error.message}`);
      }
    }
  }

  /**
   * Main deployment workflow
   */
  async run() {
    console.log('🚀 TorlyAI Complete Deployment Automation');
    console.log('=========================================\n');

    const startTime = Date.now();

    try {
      await this.setupOracleCloud();
      await this.configureDNS();
      await this.deployWordPress();
      await this.setupSSL();
      await this.configureWordPress();
      await this.publishContent();
      await this.finalVerification();

      const elapsedMinutes = ((Date.now() - startTime) / 1000 / 60).toFixed(1);

      console.log('\n' + '='.repeat(70));
      console.log('🎉 DEPLOYMENT COMPLETE!');
      console.log('='.repeat(70));
      console.log(`\nTotal time: ${elapsedMinutes} minutes\n`);
      console.log('📌 Your sites:');
      console.log(`   Main site: https://${CONFIG.domain}`);
      console.log(`   Blog: https://${CONFIG.blogSubdomain}`);
      console.log(`   Admin: https://${CONFIG.domain}/wp-admin\n`);
      console.log('📝 Next steps:');
      console.log('   1. Log into WordPress admin');
      console.log('   2. Create an application password for API access');
      console.log('   3. Customize your landing page');
      console.log('   4. Review and publish blog posts');
      console.log('   5. Configure any additional plugins or settings\n');

      return true;
    } catch (error) {
      console.error('\n❌ Deployment failed:', error.message);
      console.error('\nPlease check the error above and retry or complete manually.\n');
      return false;
    }
  }
}

// Parse command line arguments
function parseArgs() {
  const args = process.argv.slice(2);
  const options = {};

  for (const arg of args) {
    if (arg === '--skip-oracle') {
      options.skipOracle = true;
    } else if (arg === '--skip-content') {
      options.skipContent = true;
    }
  }

  return options;
}

// Run if called directly
if (import.meta.url === `file://${process.argv[1]}`) {
  const options = parseArgs();

  const deployment = new MasterDeployment(options);
  deployment.run()
    .then((success) => {
      process.exit(success ? 0 : 1);
    })
    .catch((error) => {
      console.error('\n❌ Unexpected error:', error);
      process.exit(1);
    });
}

export default MasterDeployment;
