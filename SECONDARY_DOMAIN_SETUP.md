# Secondary Domain Setup Guide
## Point innovatorfoundervisauk.com to Oracle Cloud Server

This guide explains how to configure **innovatorfoundervisauk.com** (hosted at Dreamhost) to serve the same WordPress site as **torly.ai**.

---

## Prerequisites

- Access to Dreamhost control panel
- SSH access to Oracle Cloud server
- Oracle Cloud server IP address

---

## Part 1: Update DNS at Dreamhost

### Step 1: Log into Dreamhost

1. Go to https://panel.dreamhost.com/
2. Navigate to **Manage Domains**

### Step 2: Configure DNS Records

Click on **DNS** next to **innovatorfoundervisauk.com**, then add/update these records:

| Type | Name | Value | TTL |
|------|------|-------|-----|
| **A** | `@` | `YOUR_ORACLE_IP` | 14400 |
| **A** | `www` | `YOUR_ORACLE_IP` | 14400 |

**Replace `YOUR_ORACLE_IP`** with your Oracle Cloud server's public IP address.

### Step 3: Remove from Dreamhost Hosting

**IMPORTANT:** If the domain is currently hosted on Dreamhost:

1. Go to **Manage Domains**
2. Find **innovatorfoundervisauk.com**
3. Click **Edit** or **Hosting**
4. Change to **DNS Only** (do not host files on Dreamhost)
5. Save changes

This ensures DNS points to Oracle Cloud, not Dreamhost servers.

---

## Part 2: Configure Oracle Cloud Server

### Step 1: Get Your Server IP

SSH into your Oracle Cloud server:

```bash
# Get your public IP
curl -s ifconfig.me
```

**Note this IP** - you'll need it for Dreamhost DNS configuration.

### Step 2: Upload and Run Setup Script

From your **local machine** (this repository):

```bash
# Make the script executable
chmod +x deployment/setup-secondary-domain.sh

# Copy script to server (replace with your IP and SSH key)
scp -i ~/.ssh/oracle_key deployment/setup-secondary-domain.sh ubuntu@YOUR_SERVER_IP:/tmp/

# SSH into server
ssh -i ~/.ssh/oracle_key ubuntu@YOUR_SERVER_IP

# Run the setup script
sudo bash /tmp/setup-secondary-domain.sh
```

### What the Script Does

The script automatically:
- ✅ Creates Apache virtual host for innovatorfoundervisauk.com
- ✅ Configures WordPress for multi-domain support
- ✅ Enables security headers
- ✅ Backs up wp-config.php
- ✅ Tests and reloads Apache

---

## Part 3: Wait for DNS Propagation

DNS changes can take **5-60 minutes** to propagate worldwide.

### Check DNS Propagation

Run these commands from your **local machine**:

```bash
# Check A record
nslookup innovatorfoundervisauk.com

# Should show your Oracle Cloud IP
dig innovatorfoundervisauk.com +short

# Check from multiple locations
# Visit: https://dnschecker.org/
```

**Wait until the IP matches your Oracle Cloud server IP before proceeding.**

---

## Part 4: Install SSL Certificate

Once DNS has propagated, SSH into your server and run:

```bash
# Install SSL certificate for the new domain
sudo certbot --apache -d innovatorfoundervisauk.com -d www.innovatorfoundervisauk.com
```

Certbot will:
- Request Let's Encrypt SSL certificate
- Automatically configure HTTPS
- Set up automatic renewal
- Create HTTPS virtual host

**Answer the prompts:**
- Email: Your email for certificate renewal notifications
- Agree to terms: Yes
- Redirect HTTP to HTTPS: Yes (recommended)

---

## Part 5: Verify Setup

### Test Both Domains

Visit these URLs in your browser:

1. **Primary Domain:**
   - http://torly.ai (should redirect to HTTPS)
   - https://torly.ai
   - https://www.torly.ai

2. **Secondary Domain:**
   - http://innovatorfoundervisauk.com (should redirect to HTTPS)
   - https://innovatorfoundervisauk.com
   - https://www.innovatorfoundervisauk.com

**All should show the same WordPress site!**

### Check SSL Certificates

```bash
# SSH into server
ssh -i ~/.ssh/oracle_key ubuntu@YOUR_SERVER_IP

# List all SSL certificates
sudo certbot certificates
```

You should see certificates for both domains.

---

## How It Works

### Multi-Domain WordPress Configuration

The setup script modifies `wp-config.php` to add:

```php
/* Multi-Domain Configuration */
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);
```

This makes WordPress **dynamically respond** to the domain in the browser:
- If you visit `torly.ai`, WordPress uses `https://torly.ai`
- If you visit `innovatorfoundervisauk.com`, WordPress uses `https://innovatorfoundervisauk.com`

### Apache Virtual Host

Both domains point to the same document root:

```apache
DocumentRoot /var/www/html
```

The same WordPress files serve both domains.

---

## Troubleshooting

### Issue: "Server not found"

**Cause:** DNS hasn't propagated yet.

**Solution:**
```bash
# Check if DNS is correct
nslookup innovatorfoundervisauk.com

# Wait 15-60 minutes and try again
```

### Issue: "This site can't provide a secure connection"

**Cause:** SSL certificate not installed or DNS not propagated when you ran Certbot.

**Solution:**
```bash
# Wait for DNS to propagate first
# Then run Certbot again
sudo certbot --apache -d innovatorfoundervisauk.com -d www.innovatorfoundervisauk.com
```

### Issue: Apache test failed

**Cause:** Configuration syntax error.

**Solution:**
```bash
# Check Apache configuration
sudo apache2ctl configtest

# View error logs
sudo tail -f /var/log/apache2/error.log
```

### Issue: WordPress shows wrong domain

**Cause:** Multi-domain configuration not properly added.

**Solution:**
```bash
# Check wp-config.php
sudo nano /var/www/html/wp-config.php

# Ensure these lines exist:
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);
```

### Issue: Certbot rate limit

**Cause:** Too many certificate requests in a short time.

**Solution:**
```bash
# Use staging environment for testing
sudo certbot --apache --staging -d innovatorfoundervisauk.com -d www.innovatorfoundervisauk.com

# After testing works, get real certificate
sudo certbot --apache -d innovatorfoundervisauk.com -d www.innovatorfoundervisauk.com
```

---

## Manual Configuration (If Script Fails)

### 1. Create Apache Virtual Host Manually

```bash
# Create configuration file
sudo nano /etc/apache2/sites-available/innovatorfoundervisauk.com.conf
```

**Add this content:**

```apache
<VirtualHost *:80>
    ServerName innovatorfoundervisauk.com
    ServerAlias www.innovatorfoundervisauk.com

    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/innovatorfoundervisauk_error.log
    CustomLog ${APACHE_LOG_DIR}/innovatorfoundervisauk_access.log combined

    # Security Headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
</VirtualHost>
```

**Save and enable:**

```bash
# Enable the site
sudo a2ensite innovatorfoundervisauk.com.conf

# Test configuration
sudo apache2ctl configtest

# Reload Apache
sudo systemctl reload apache2
```

### 2. Update wp-config.php Manually

```bash
# Backup first
sudo cp /var/www/html/wp-config.php /var/www/html/wp-config.php.backup

# Edit wp-config.php
sudo nano /var/www/html/wp-config.php
```

**Add BEFORE the line `/* That's all, stop editing! Happy publishing. */`:**

```php
/* Multi-Domain Configuration */
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);
```

**Save and exit** (Ctrl+O, Enter, Ctrl+X)

---

## SEO Considerations

### Canonical URLs

Since both domains serve the same content, set a **canonical domain** to avoid duplicate content penalties.

**Option A: Add canonical tags** (recommended)

Edit your theme's `header.php`:

```php
<link rel="canonical" href="https://torly.ai<?php echo $_SERVER['REQUEST_URI']; ?>" />
```

**Option B: Use redirects**

If you want innovatorfoundervisauk.com to redirect to torly.ai instead:

```bash
# Edit virtual host
sudo nano /etc/apache2/sites-available/innovatorfoundervisauk.com.conf

# Replace content with:
<VirtualHost *:80>
    ServerName innovatorfoundervisauk.com
    ServerAlias www.innovatorfoundervisauk.com
    Redirect 301 / https://torly.ai/
</VirtualHost>
```

---

## Maintenance

### Renew SSL Certificates

Certbot auto-renews via cron. Check renewal:

```bash
# Test renewal
sudo certbot renew --dry-run

# View certificate expiry dates
sudo certbot certificates
```

### View Apache Logs

```bash
# Error logs
sudo tail -f /var/log/apache2/innovatorfoundervisauk_error.log

# Access logs
sudo tail -f /var/log/apache2/innovatorfoundervisauk_access.log
```

---

## Summary

✅ DNS points from Dreamhost to Oracle Cloud
✅ Apache serves both domains from same WordPress installation
✅ WordPress dynamically responds to domain name
✅ SSL certificates for both domains
✅ Same content, same admin panel, same database

**Both domains work independently but serve identical content!**

---

## Files Modified

- `/etc/apache2/sites-available/innovatorfoundervisauk.com.conf` (created)
- `/var/www/html/wp-config.php` (multi-domain support added)
- `/var/www/html/wp-config.php.backup.*` (backup created)

---

## Support

If you encounter issues:
1. Check Apache error logs: `sudo tail -f /var/log/apache2/error.log`
2. Check DNS propagation: https://dnschecker.org/
3. Test SSL: https://www.ssllabs.com/ssltest/

---

*Last Updated: 2025-11-18*
