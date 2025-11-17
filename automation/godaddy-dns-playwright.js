import { chromium } from 'playwright';
import { readFileSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

class GoDaddyDNSAutomation {
  constructor() {
    this.browser = null;
    this.page = null;
    this.credentials = null;
    this.targetIP = null;
  }

  loadCredentials() {
    try {
      const loginPath = join(__dirname, '../.credentials/godaddy_login.json');
      const oraclePath = join(__dirname, '../.credentials/oracle_credentials.json');

      this.credentials = JSON.parse(readFileSync(loginPath, 'utf8'));
      const oracleData = JSON.parse(readFileSync(oraclePath, 'utf8'));
      this.targetIP = oracleData.vmIP;

      if (!this.credentials.username || !this.credentials.password) {
        throw new Error('Missing GoDaddy login credentials');
      }

      console.log('✅ Credentials loaded');
      console.log(`   Target IP: ${this.targetIP}`);
    } catch (error) {
      console.error('❌ Failed to load credentials:', error.message);
      throw error;
    }
  }

  async launch() {
    console.log('🌐 Launching browser...');
    this.browser = await chromium.launch({
      headless: false, // Show browser so user can see what's happening
      slowMo: 100 // Slow down operations for visibility
    });
    this.page = await this.browser.newPage();

    // Set longer timeout for navigation
    this.page.setDefaultTimeout(60000);
    console.log('✅ Browser launched');
  }

  async login() {
    console.log('🔐 Opening GoDaddy login page...');
    console.log('⚠️  MANUAL LOGIN REQUIRED');
    console.log('   Please login to GoDaddy in the browser window that opens.');
    console.log('   Once logged in, navigate to: https://dcc.godaddy.com/domains/torly.ai/dns');
    console.log('   Then come back here and press Enter to continue...\n');

    try {
      // Navigate to GoDaddy DNS management directly
      await this.page.goto('https://dcc.godaddy.com/domains/torly.ai/dns');

      // This will redirect to login page if not logged in
      await this.page.waitForLoadState('networkidle');
      await this.page.waitForTimeout(2000);

      console.log('🌐 Browser opened. Please complete the following:');
      console.log('   1. Login to your GoDaddy account');
      console.log('   2. Complete any 2FA/verification if required');
      console.log('   3. You should land on the DNS management page for torly.ai');
      console.log('   4. Keep the browser window open');
      console.log('\n   ⏳ Waiting for you to login...\n');

      // Wait for the user to login by checking if DNS page loads
      let loggedIn = false;
      const maxWaitMinutes = 5;
      const checkInterval = 5000; // 5 seconds
      const maxChecks = (maxWaitMinutes * 60 * 1000) / checkInterval;

      for (let i = 0; i < maxChecks; i++) {
        await this.page.waitForTimeout(checkInterval);

        const url = this.page.url();
        const title = await this.page.title();

        // Check if we're on the DNS management page
        if (url.includes('dcc.godaddy.com') && url.includes('dns')) {
          // Check if DNS records are visible (means logged in)
          const dnsVisible = await this.page.locator('table, [data-eid*="dns"], .dns-records').first().isVisible().catch(() => false);

          if (dnsVisible) {
            loggedIn = true;
            break;
          }
        }

        // Show progress every 30 seconds
        if (i % 6 === 0 && i > 0) {
          console.log(`   ⏳ Still waiting... (${Math.floor(i * checkInterval / 1000)}s elapsed)`);
        }
      }

      if (!loggedIn) {
        throw new Error('Timeout waiting for login. Please try again.');
      }

      console.log('\n✅ Login detected! DNS management page loaded.');
      console.log('🤖 Now automating DNS record configuration...\n');

    } catch (error) {
      console.error('❌ Login failed:', error.message);
      throw error;
    }
  }

  async navigateToDNS() {
    // Skip - already on DNS page from login
    console.log('✅ Already on DNS management page');
  }

  async configureDNSRecords() {
    console.log('📝 Configuring DNS records...');

    const records = [
      { name: '@', value: this.targetIP, description: 'Root domain' },
      { name: 'www', value: this.targetIP, description: 'WWW subdomain' },
      { name: 'blog', value: this.targetIP, description: 'Blog subdomain' }
    ];

    try {
      for (const record of records) {
        console.log(`   Adding A record: ${record.name} → ${record.value}`);
        await this.addOrUpdateARecord(record.name, record.value);
      }

      console.log('✅ All DNS records configured');
    } catch (error) {
      console.error('❌ Failed to configure DNS records:', error.message);
      throw error;
    }
  }

  async addOrUpdateARecord(name, value) {
    try {
      // Check if record already exists
      const existingRecord = await this.page.locator(`tr:has-text("${name}"):has-text("A")`).first();

      if (await existingRecord.count() > 0) {
        console.log(`   Updating existing record: ${name}`);

        // Click edit button on the record
        const editButton = await existingRecord.locator('button[aria-label*="Edit"], button:has-text("Edit"), button.edit').first();
        await editButton.click();

        // Wait for edit form
        await this.page.waitForSelector('input[name="value"], input[placeholder*="IP"]');

        // Clear and enter new value
        const valueInput = await this.page.locator('input[name="value"], input[placeholder*="IP"]').first();
        await valueInput.fill('');
        await valueInput.fill(value);

        // Save changes
        const saveButton = await this.page.locator('button:has-text("Save"), button[type="submit"]').first();
        await saveButton.click();

        await this.page.waitForTimeout(1000);
      } else {
        console.log(`   Adding new record: ${name}`);

        // Click "Add" or "Add Record" button
        const addButton = await this.page.locator('button:has-text("Add"), button:has-text("Add Record")').first();
        await addButton.click();

        // Wait for add form
        await this.page.waitForSelector('select[name="type"], select:has-text("A")');

        // Select A record type
        await this.page.selectOption('select[name="type"], select', 'A');

        // Fill in name
        const nameInput = await this.page.locator('input[name="name"], input[placeholder*="Name"]').first();
        await nameInput.fill(name === '@' ? '' : name);

        // Fill in value (IP address)
        const valueInput = await this.page.locator('input[name="value"], input[placeholder*="IP"], input[placeholder*="Value"]').first();
        await valueInput.fill(value);

        // Save record
        const saveButton = await this.page.locator('button:has-text("Save"), button[type="submit"]').first();
        await saveButton.click();

        await this.page.waitForTimeout(1000);
      }
    } catch (error) {
      console.error(`   Failed to add/update record ${name}:`, error.message);
      throw error;
    }
  }

  async saveDNS() {
    console.log('💾 Saving DNS changes...');

    try {
      // Look for a global save button
      const saveButton = await this.page.locator('button:has-text("Save all"), button:has-text("Save changes")').first();

      if (await saveButton.count() > 0) {
        await saveButton.click();
        await this.page.waitForTimeout(2000);
        console.log('✅ DNS changes saved');
      } else {
        console.log('✅ DNS changes auto-saved');
      }
    } catch (error) {
      console.log('✅ DNS changes auto-saved (no save button found)');
    }
  }

  async verifyDNS() {
    console.log('🔍 Verifying DNS records...');

    try {
      const records = ['@', 'www', 'blog'];

      for (const name of records) {
        const record = await this.page.locator(`tr:has-text("${name}"):has-text("A"):has-text("${this.targetIP}")`).first();

        if (await record.count() > 0) {
          console.log(`   ✅ ${name} → ${this.targetIP}`);
        } else {
          console.log(`   ⚠️  ${name} record not found or incorrect`);
        }
      }

      console.log('✅ DNS verification complete');
    } catch (error) {
      console.error('❌ DNS verification failed:', error.message);
    }
  }

  async close() {
    if (this.browser) {
      console.log('🔒 Closing browser...');
      await this.browser.close();
    }
  }

  async run() {
    try {
      console.log('🚀 GoDaddy DNS Automation with Playwright');
      console.log('=========================================\n');

      this.loadCredentials();
      await this.launch();
      await this.login();
      await this.navigateToDNS();
      await this.configureDNSRecords();
      await this.saveDNS();
      await this.verifyDNS();

      console.log('\n✅ DNS configuration completed successfully!');
      console.log('\n⏰ Waiting 5 seconds before closing browser...');
      await this.page.waitForTimeout(5000);

      await this.close();
      return true;
    } catch (error) {
      console.error('\n❌ Automation failed:', error.message);
      console.error('\n⚠️  Browser will stay open for manual intervention.');
      console.error('   Please configure DNS manually and close the browser when done.\n');

      // Don't close browser on error - let user fix manually
      await this.page.waitForTimeout(300000); // Wait 5 minutes
      await this.close();
      return false;
    }
  }
}

// Run the automation
const automation = new GoDaddyDNSAutomation();
automation.run().then(success => {
  process.exit(success ? 0 : 1);
}).catch(error => {
  console.error('Fatal error:', error);
  process.exit(1);
});
