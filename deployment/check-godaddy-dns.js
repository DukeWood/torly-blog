#!/usr/bin/env node
/**
 * Check GoDaddy DNS configuration for redirects
 */

const https = require('https');

const apiKey = 'fYfzoyYwnqCq_J1vNotJUnKPzFdZXK9YAZN';
const apiSecret = 'Sb5oX8EuzmEx3R4kS8HCUg';
const domain = 'torly.ai';

console.log('=== Checking GoDaddy DNS Records for torly.ai ===\n');

// Check DNS records
const options = {
  hostname: 'api.godaddy.com',
  path: `/v1/domains/${domain}/records`,
  method: 'GET',
  headers: {
    'Authorization': `sso-key ${apiKey}:${apiSecret}`,
    'Accept': 'application/json'
  }
};

const req = https.request(options, (res) => {
  let data = '';
  res.on('data', (chunk) => data += chunk);
  res.on('end', () => {
    try {
      const records = JSON.parse(data);
      console.log('DNS Records:');
      console.log(JSON.stringify(records, null, 2));

      // Check for CNAME redirects
      const blogRecords = records.filter(r => r.name === 'blog' || r.name.includes('blog'));
      if (blogRecords.length > 0) {
        console.log('\n=== Blog-related DNS Records ===');
        console.log(JSON.stringify(blogRecords, null, 2));
      }
    } catch (e) {
      console.error('Error parsing response:', e.message);
      console.error('Response data:', data);
    }
  });
});

req.on('error', (e) => {
  console.error('Request error:', e.message);
});

req.end();
