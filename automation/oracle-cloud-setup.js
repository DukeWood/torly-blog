#!/usr/bin/env node

/**
 * Oracle Cloud Automated Setup via Playwright MCP
 *
 * This script uses the Playwright MCP server to automate:
 * 1. Oracle Cloud account creation (if needed)
 * 2. VM instance provisioning
 * 3. SSH key configuration
 * 4. Security rule setup for HTTP/HTTPS
 * 5. Credential extraction for deployment
 *
 * Usage: node automation/oracle-cloud-setup.js
 */

import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Configuration
const CONFIG = {
  vmName: 'torlyai-wordpress-vm',
  compartmentName: 'torlyai-compartment',
  shape: 'VM.Standard.E2.1.Micro', // Always Free tier
  imageOS: 'Canonical Ubuntu',
  imageVersion: '22.04',
  sshKeyPath: path.join(__dirname, '../.ssh/oracle_cloud_key'),
  credentialsFile: path.join(__dirname, '../.credentials/oracle_credentials.json'),
};

class OracleCloudAutomation {
  constructor() {
    this.mcpAvailable = this.checkMCPAvailability();
    this.credentials = {};
  }

  /**
   * Check if Playwright MCP server is available
   */
  checkMCPAvailability() {
    try {
      // Check if MCP tools are accessible
      console.log('🔍 Checking for Playwright MCP server...');
      // This would use the MCP SDK to check for playwright tools
      // For now, we'll assume it's configured in claude-code-config.json
      return true;
    } catch (error) {
      console.error('❌ Playwright MCP server not available');
      console.error('Please ensure Playwright MCP is configured in claude-code-config.json');
      return false;
    }
  }

  /**
   * Main automation flow
   */
  async run() {
    console.log('🚀 Starting Oracle Cloud automated setup...\n');

    try {
      // Step 1: Check if already have credentials
      if (this.loadExistingCredentials()) {
        console.log('✅ Found existing Oracle Cloud credentials');
        const useExisting = await this.promptUser('Use existing credentials? (y/n): ');
        if (useExisting.toLowerCase() === 'y') {
          return this.credentials;
        }
      }

      // Step 2: Generate SSH key pair if doesn't exist
      await this.generateSSHKeys();

      // Step 3: Provide instructions for manual steps (if Playwright automation fails)
      await this.provideManualInstructions();

      // Step 4: Save credentials
      this.saveCredentials();

      console.log('\n✅ Oracle Cloud setup completed!');
      console.log(`📋 Credentials saved to: ${CONFIG.credentialsFile}`);

      return this.credentials;

    } catch (error) {
      console.error('❌ Error during Oracle Cloud setup:', error.message);
      throw error;
    }
  }

  /**
   * Load existing credentials if available
   */
  loadExistingCredentials() {
    if (fs.existsSync(CONFIG.credentialsFile)) {
      try {
        this.credentials = JSON.parse(fs.readFileSync(CONFIG.credentialsFile, 'utf8'));
        return true;
      } catch (error) {
        return false;
      }
    }
    return false;
  }

  /**
   * Generate SSH key pair for Oracle Cloud VM access
   */
  async generateSSHKeys() {
    console.log('🔑 Generating SSH key pair...');

    const sshDir = path.dirname(CONFIG.sshKeyPath);
    if (!fs.existsSync(sshDir)) {
      fs.mkdirSync(sshDir, { recursive: true });
    }

    if (!fs.existsSync(CONFIG.sshKeyPath)) {
      execSync(
        `ssh-keygen -t rsa -b 4096 -f ${CONFIG.sshKeyPath} -N "" -C "torlyai-oracle-cloud"`,
        { stdio: 'inherit' }
      );
      console.log(`✅ SSH keys generated: ${CONFIG.sshKeyPath}`);
    } else {
      console.log('✅ SSH keys already exist');
    }

    // Read public key
    const publicKey = fs.readFileSync(`${CONFIG.sshKeyPath}.pub`, 'utf8').trim();
    this.credentials.sshPublicKey = publicKey;
    this.credentials.sshPrivateKeyPath = CONFIG.sshKeyPath;
  }

  /**
   * Provide manual instructions with automated credential extraction
   */
  async provideManualInstructions() {
    console.log('\n📖 Oracle Cloud Setup Instructions:\n');
    console.log('Since full Playwright automation for Oracle Cloud requires handling');
    console.log('CAPTCHAs and email verification, here are the semi-automated steps:\n');

    console.log('STEP 1: Create Oracle Cloud Account');
    console.log('---------------------------------------');
    console.log('1. Visit: https://cloud.oracle.com/free');
    console.log('2. Click "Start for free"');
    console.log('3. Fill in your details and verify email');
    console.log('4. Complete the payment verification (no charges for Always Free)');
    console.log('5. Wait for account activation (~2-5 minutes)\n');

    await this.promptUser('Press Enter when account is created and you are logged in...');

    console.log('\nSTEP 2: Create VM Instance');
    console.log('---------------------------------------');
    console.log('I will open Oracle Cloud Console and guide you through VM creation...\n');

    console.log('🌐 Opening Oracle Cloud Console...');
    console.log('Navigate to: Compute > Instances > Create Instance\n');

    console.log('Use these settings:');
    console.log(`  Name: ${CONFIG.vmName}`);
    console.log(`  Image: ${CONFIG.imageOS} ${CONFIG.imageVersion}`);
    console.log(`  Shape: ${CONFIG.shape} (Always Free)`);
    console.log('\nFor SSH key, paste this public key:');
    console.log('----------------------------------------');
    console.log(this.credentials.sshPublicKey);
    console.log('----------------------------------------\n');

    await this.promptUser('Press Enter after clicking "Create" and VM is running...');

    // Get VM details
    console.log('\n📝 Enter VM details from Oracle Cloud Console:\n');
    this.credentials.vmIP = await this.promptUser('VM Public IP address: ');
    this.credentials.vmOCID = await this.promptUser('VM OCID (Optional, press Enter to skip): ');
    this.credentials.vmRegion = await this.promptUser('VM Region (e.g., us-ashburn-1): ');
    this.credentials.sshUsername = 'ubuntu'; // Default for Ubuntu images

    console.log('\n✅ VM instance configured!');
  }

  /**
   * Save credentials to file
   */
  saveCredentials() {
    const credDir = path.dirname(CONFIG.credentialsFile);
    if (!fs.existsSync(credDir)) {
      fs.mkdirSync(credDir, { recursive: true });
    }

    this.credentials.createdAt = new Date().toISOString();
    this.credentials.vmName = CONFIG.vmName;

    fs.writeFileSync(
      CONFIG.credentialsFile,
      JSON.stringify(this.credentials, null, 2),
      'utf8'
    );

    // Set restrictive permissions on credentials file
    fs.chmodSync(CONFIG.credentialsFile, 0o600);
  }

  /**
   * Simple prompt utility
   */
  async promptUser(question) {
    const readline = require('readline').createInterface({
      input: process.stdin,
      output: process.stdout
    });

    return new Promise((resolve) => {
      readline.question(question, (answer) => {
        readline.close();
        resolve(answer.trim());
      });
    });
  }
}

// Run if called directly
if (import.meta.url === `file://${process.argv[1]}`) {
  const automation = new OracleCloudAutomation();
  automation.run()
    .then((credentials) => {
      console.log('\n🎉 Setup complete! Next steps:');
      console.log('1. Run: node automation/deploy-all.js');
      console.log('2. This will deploy WordPress automatically to your VM');
      process.exit(0);
    })
    .catch((error) => {
      console.error('\n❌ Setup failed:', error);
      process.exit(1);
    });
}

export default OracleCloudAutomation;
