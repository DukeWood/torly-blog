# TorlyAI WordPress Blog Setup Guide

## Project Structure
```
torly-wordpress-setup/
├── README.md (this file)
├── config/
│   ├── wp-config-custom.php
│   ├── .htaccess
│   └── nginx.conf
├── theme/
│   ├── torly-theme/
│   │   ├── style.css
│   │   ├── index.php
│   │   ├── functions.php
│   │   └── front-page.php
├── mcp-integration/
│   ├── wordpress-mcp-server.js
│   ├── package.json
│   └── .env.example
├── api-integrations/
│   ├── godaddy-dns-manager.php
│   └── wordpress-api-client.js
└── deployment/
    └── deploy-script.sh
```

## Prerequisites
- Domain: torly.ai (already purchased on GoDaddy)
- Hosting solution (see options below)
- Node.js for MCP integration
- PHP 7.4+ for WordPress

## Step 1: Free Hosting Options

### Option A: InfinityFree (Recommended for beginners)
- **URL**: https://infinityfree.net/
- **Features**: Free subdomain, 5GB disk space, unlimited bandwidth
- **Limitations**: No SSH access, limited database connections

### Option B: 000webhost
- **URL**: https://www.000webhost.com/
- **Features**: 300MB disk space, 3GB bandwidth
- **Limitations**: Limited resources, ads on free plan

### Option C: Local Development First (Best for development)
- Use XAMPP/WAMP/MAMP locally
- Deploy later to a VPS or shared hosting

### Option D: Oracle Cloud Free Tier (Advanced)
- **URL**: https://www.oracle.com/cloud/free/
- **Features**: 2 AMD-based VMs forever free
- **Best for**: Full control, SSH access, custom configurations

## Step 2: GoDaddy DNS Configuration

### Main Domain Setup (torly.ai)
1. Log into GoDaddy Domain Manager
2. Navigate to DNS Management for torly.ai
3. Add/Update these records:

```
Type    Name    Value                   TTL
A       @       YOUR_HOSTING_IP         3600
A       www     YOUR_HOSTING_IP         3600
A       blog    YOUR_HOSTING_IP         3600
CNAME   www     torly.ai                3600
```

### Subdomain Setup (blog.torly.ai)
The A record for 'blog' above handles this. Your hosting provider needs to be configured to recognize this subdomain.

## Step 3: WordPress Installation

### 3.1 Download WordPress
```bash
wget https://wordpress.org/latest.tar.gz
tar -xzvf latest.tar.gz
mv wordpress/* /path/to/your/public_html/
```

### 3.2 Database Setup
Create a MySQL database and user for WordPress:
```sql
CREATE DATABASE torly_wordpress;
CREATE USER 'torly_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON torly_wordpress.* TO 'torly_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3.3 WordPress Configuration
1. Navigate to torly.ai in your browser
2. Follow the WordPress installation wizard
3. Use the database credentials created above

## Step 4: Subdomain Configuration for blog.torly.ai

### Apache Configuration (.htaccess in root)
```apache
RewriteEngine On
RewriteCond %{HTTP_HOST} ^blog\.torly\.ai$ [NC]
RewriteRule ^(.*)$ /blog/$1 [L]
```

### WordPress Multisite Alternative
Enable WordPress Multisite for subdomain blogs:
```php
define('WP_ALLOW_MULTISITE', true);
```

## Step 5: Custom Theme Development

The custom theme files will be created in the next steps. The theme will include:
- Custom homepage for TorlyAI
- Blog integration
- API connections
- MCP compatibility

## Step 6: MCP Integration

MCP (Model Context Protocol) server will be set up to interact with WordPress via REST API and WP-CLI commands.

## Step 7: API Integrations

### WordPress REST API
Enable and configure WordPress REST API for external access:
```php
// In wp-config.php
define('JWT_AUTH_SECRET_KEY', 'your-secret-key');
define('JWT_AUTH_CORS_ENABLE', true);
```

### GoDaddy API
For DNS management and domain configuration automation.

## Step 8: Security Considerations

1. Install security plugins (Wordfence, Sucuri)
2. Enable SSL certificate (Let's Encrypt free SSL)
3. Regular backups
4. Strong passwords
5. Limit login attempts

## Next Steps

1. Choose your hosting solution
2. Point your domain to the hosting
3. Install WordPress
4. Deploy the custom theme
5. Configure MCP server
6. Test API integrations

## Support Resources

- WordPress Codex: https://codex.wordpress.org/
- GoDaddy API Docs: https://developer.godaddy.com/
- MCP Documentation: https://modelcontextprotocol.io/
