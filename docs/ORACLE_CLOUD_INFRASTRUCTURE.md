# TorlyAI Oracle Cloud Infrastructure Documentation

> **Last Updated**: 2026-01-09
> **Status**: ✅ Operational - SSH Access Restored
> **Author**: Claude Code

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Architecture Overview](#architecture-overview)
3. [Oracle Cloud Infrastructure (OCI)](#oracle-cloud-infrastructure-oci)
4. [Backend Stack](#backend-stack)
5. [Frontend Stack](#frontend-stack)
6. [Deployment Structure](#deployment-structure)
7. [Network Configuration](#network-configuration)
8. [Security Configuration](#security-configuration)
9. [Database Configuration](#database-configuration)
10. [OCI CLI Configuration](#oci-cli-configuration)
11. [Known Issues & Recovery](#known-issues--recovery)
12. [Maintenance Procedures](#maintenance-procedures)
13. [Resource IDs Reference](#resource-ids-reference)

---

## Executive Summary

TorlyAI operates a hybrid architecture:

| Layer | Technology | Hosting |
|-------|------------|---------|
| **Frontend** | Next.js 14 (React 18) | Vercel (or local dev) |
| **Backend API** | Next.js API Routes | Vercel (or local dev) |
| **WordPress Backend** | WordPress + PHP | Oracle Cloud VM |
| **Database** | MySQL 8.x | Oracle Cloud VM |
| **Database Admin** | Adminer | Oracle Cloud VM |
| **Auth & Storage** | Supabase | Supabase Cloud |
| **Payments** | Stripe | Stripe Cloud |

### Current Deployment Status (January 2026)

> **IMPORTANT**: This section clarifies what is actually live vs. planned.

| Repository | Component | Deployment Status | Live URL |
|------------|-----------|-------------------|----------|
| **torly-blog** | WordPress + Blog | ✅ **LIVE** on Oracle VM | https://torly.ai |
| **torlyAI** | Next.js SaaS App | ❌ **NOT DEPLOYED** - Local dev only | None |

**What's Actually Running at torly.ai:**
```
Server: Apache/2.4.52 (Ubuntu)
Link: <https://torly.ai/wp-json/>  ← WordPress REST API
```

The domain `torly.ai` currently points to the **WordPress site** hosted on Oracle Cloud VM, NOT a Vercel deployment of the Next.js app.

**Two Repositories:**
1. **torlyAI** (this doc's original repo → moved to torly-blog)
   - Next.js 14 SaaS application with 31 AI Skills
   - Runs locally with `npm run dev` on port 3000
   - NOT connected to Vercel, no production deployment

2. **torly-blog** (contains this doc)
   - WordPress theme, deployment scripts, SSH/OCI credentials
   - **This is what's live** at https://torly.ai
   - Located at: `/Users/Jason-uk/AI/AI_Coding/Repositories/torly-blog/`

**SSH Connection (from torly-blog):**
```bash
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179
```

### Quick Access URLs

| Service | URL | Notes |
|---------|-----|-------|
| Production Site | https://torly.ai | Main application |
| Database Admin | https://torly.ai/db-admin/ | Adminer interface |
| Oracle Console | https://cloud.oracle.com | OCI management |
| Supabase Dashboard | https://supabase.com/dashboard | Auth & storage |

---

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              USER BROWSER                                    │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CLOUDFLARE / DNS                                   │
│                         torly.ai → 141.147.89.179                           │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    ▼                               ▼
┌───────────────────────────────┐   ┌───────────────────────────────────────┐
│      VERCEL (Frontend)        │   │     ORACLE CLOUD VM (Backend)         │
│  ┌─────────────────────────┐  │   │  ┌─────────────────────────────────┐  │
│  │   Next.js 14 App        │  │   │  │         NGINX                   │  │
│  │   - React 18 UI         │  │   │  │    (Reverse Proxy + SSL)        │  │
│  │   - 31 AI Skills        │  │   │  └─────────────┬───────────────────┘  │
│  │   - Tailwind CSS        │  │   │                │                      │
│  │   - TypeScript          │  │   │    ┌───────────┼───────────┐          │
│  └─────────────────────────┘  │   │    ▼           ▼           ▼          │
│                               │   │  ┌─────┐   ┌───────┐   ┌────────┐     │
│  ┌─────────────────────────┐  │   │  │ PHP │   │ MySQL │   │Adminer │     │
│  │   API Routes            │  │   │  │ 8.x │   │  8.x  │   │  UI    │     │
│  │   - /api/chat           │  │   │  └──┬──┘   └───┬───┘   └────────┘     │
│  │   - /api/stripe         │  │   │     │         │                       │
│  │   - /api/autopilot      │  │   │     ▼         │                       │
│  └─────────────────────────┘  │   │  ┌──────────────────────────────────┐ │
└───────────────────────────────┘   │  │         WORDPRESS                │ │
                                    │  │   - Content Management           │ │
          │                         │  │   - Blog/Articles                │ │
          │                         │  │   - Waitlist Management          │ │
          ▼                         │  └──────────────────────────────────┘ │
┌───────────────────────────────┐   └───────────────────────────────────────┘
│      EXTERNAL SERVICES        │
│  ┌─────────────────────────┐  │
│  │   Supabase              │  │
│  │   - PostgreSQL DB       │  │
│  │   - Auth (JWT)          │  │
│  │   - Storage (Files)     │  │
│  └─────────────────────────┘  │
│  ┌─────────────────────────┐  │
│  │   AI Providers          │  │
│  │   - OpenAI              │  │
│  │   - Anthropic Claude    │  │
│  │   - Google Gemini       │  │
│  │   - DeepSeek            │  │
│  └─────────────────────────┘  │
│  ┌─────────────────────────┐  │
│  │   Stripe                │  │
│  │   - Subscriptions       │  │
│  │   - Payments            │  │
│  └─────────────────────────┘  │
└───────────────────────────────┘
```

---

## Oracle Cloud Infrastructure (OCI)

### Compute Instance

| Property | Value |
|----------|-------|
| **Display Name** | instance-20251117-1321 |
| **Instance ID** | `ocid1.instance.oc1.uk-london-1.anwgiljtdz6cpeyciyko632s6r4fpdoxfydao7rl4chfja76ghnhm2e5rmrq` |
| **Shape** | VM.Standard.E2.1.Micro |
| **OCPUs** | 1 |
| **Memory** | 1 GB |
| **vCPUs** | 2 |
| **Processor** | 2.55 GHz AMD EPYC 7J13 (Milan) |
| **Network Bandwidth** | 0.5 Gbps |
| **Tier** | Always Free |
| **Created** | 2025-11-17T13:56:20Z |
| **Region** | UK South (London) - uk-london-1 |
| **Availability Domain** | CZAd:UK-LONDON-1-AD-1 |
| **Fault Domain** | FAULT-DOMAIN-2 |
| **Lifecycle State** | RUNNING |

### Operating System

| Property | Value |
|----------|-------|
| **OS** | Canonical Ubuntu |
| **Version** | 22.04 LTS |
| **Image** | Canonical-Ubuntu-22.04-2025.10.31-0 |
| **Image ID** | `ocid1.image.oc1.uk-london-1.aaaaaaaaaqqnwwvppjnkkabddcygclu3vbdytqyfou5cr65lqis6ztakru4a` |
| **Firmware** | UEFI_64 |
| **Launch Mode** | PARAVIRTUALIZED |

### Boot Volume

| Property | Value |
|----------|-------|
| **Display Name** | instance-20251117-1321 (Boot Volume) |
| **Boot Volume ID** | `ocid1.bootvolume.oc1.uk-london-1.abwgiljtvxvyat4y72fjuegqlbpb4woyd6fbpwpqyn255w7eefydjnzbr6oq` |
| **Size** | 47 GB |
| **VPUs per GB** | 10 (Balanced) |
| **State** | AVAILABLE |
| **Encryption** | In-transit encryption enabled |

### Network Configuration

| Property | Value |
|----------|-------|
| **Public IP** | 141.147.89.179 |
| **Private IP** | 10.0.0.97 |
| **VCN Name** | vcn-20251117-1347 |
| **VCN ID** | `ocid1.vcn.oc1.uk-london-1.amaaaaaadz6cpeyalib655c4ksgo4nc4fzz4tlleji47b6uulciuq7ia6sva` |
| **VCN CIDR** | 10.0.0.0/16 |
| **Subnet Name** | subnet-20251117-1347 |
| **Subnet ID** | `ocid1.subnet.oc1.uk-london-1.aaaaaaaaopqbrmd6grrkoxgcotq6eapo7mlnc257vadqutbxpa34v2t6onrq` |
| **Subnet CIDR** | 10.0.0.0/24 |
| **DNS Domain** | subnet11171356.vcn11171356.oraclevcn.com |
| **Gateway IP** | 10.0.0.1 |

### Instance Agent Plugins

| Plugin | Status |
|--------|--------|
| Compute Instance Monitoring | ENABLED |
| Vulnerability Scanning | DISABLED |
| Management Agent | DISABLED |
| Custom Logs Monitoring | DISABLED |
| Block Volume Management | DISABLED |
| Bastion | DISABLED |
| Cloud Guard Workload Protection | DISABLED |

### Always Free Tier Status

> **Last Verified**: 2026-01-09 22:32 UTC

**Account Status:**
| Property | Value |
|----------|-------|
| **Account Type** | Always Free (Free Trial Ended) |
| **Free Trial** | ❌ Expired |
| **Always Free Resources** | ✅ Active - Will continue forever |
| **Billing** | No charges - $0/month |
| **Authentication** | 🔐 Google Authenticator App (2FA enabled) |

**Login Security:**
- Oracle Cloud Console uses **Multi-Factor Authentication (MFA)**
- 2FA is configured via **Google Authenticator App**
- Always keep the authenticator app backed up (export QR codes or recovery keys)

**What This Means:**
- The 30-day Free Trial (with $300 credits) has ended
- **Always Free** resources continue indefinitely at no cost
- Our VM shape `VM.Standard.E2.1.Micro` is Always Free eligible
- No action required - the VM will NOT be deleted

**Always Free Tier Limits (What We're Using):**

| Resource | Always Free Limit | Our Usage | Status |
|----------|-------------------|-----------|--------|
| AMD Compute VMs | 2 x VM.Standard.E2.1.Micro | 1 VM | ✅ Within limit |
| Boot Volume Storage | 200 GB total | 47 GB | ✅ Within limit |
| Block Volume Storage | 200 GB total | 0 GB | ✅ Within limit |
| Outbound Data Transfer | 10 TB/month | ~minimal | ✅ Within limit |

**Current VM Health (as of 2026-01-09):**

```
✅ VM Status: RUNNING
✅ Uptime: 8+ hours
✅ Apache2: active
✅ MySQL: active
✅ Website: HTTP 200 OK

Disk: 8.4GB used / 45GB (19%)
Memory: 365MB used / 956MB (38%)
Load: 0.00, 0.00, 0.00
```

**Important Notes:**
1. **Never upgrade** to a paid account unless you want to be charged
2. The "Free Trial ended" alert is informational only
3. Always Free resources are permanent (as long as Oracle maintains the program)
4. If VM is ever terminated, you can create a new Always Free VM

**Oracle's Always Free Commitment:**
> "Always Free services are available for an unlimited time... These resources will not be reclaimed, even if your Free Trial ends." - [Oracle Cloud Free Tier](https://www.oracle.com/cloud/free/)

---

## Backend Stack

### WordPress (on Oracle VM)

| Component | Details |
|-----------|---------|
| **WordPress Version** | (Check via wp-admin) |
| **PHP Version** | 8.x |
| **Web Server** | Nginx |
| **Purpose** | Content management, blog, waitlist |
| **Admin URL** | https://torly.ai/wp-admin/ |

### MySQL Database

| Property | Value |
|----------|-------|
| **Server** | localhost |
| **Port** | 3306 |
| **Username** | torly_user |
| **Password** | ChAOOHqfpRtIAbsj |
| **Database** | torly_wordpress |

### Database Tables (Known)

| Table | Purpose |
|-------|---------|
| `wp_waitlist` | Waitlist email signups with timestamps |
| `wp_posts` | WordPress posts/pages |
| `wp_users` | WordPress admin users |
| `wp_options` | WordPress settings |

### Database Admin (Adminer)

| Property | Value |
|----------|-------|
| **URL** | https://torly.ai/db-admin/ |
| **System** | MySQL |
| **Server** | localhost |
| **Login** | Use credentials above |

---

## Frontend Stack

### Next.js Application

| Technology | Version | Purpose |
|------------|---------|---------|
| **Next.js** | 14.0.0 | React framework with App Router |
| **React** | 18.2.0 | UI library |
| **TypeScript** | 5.2.0 | Type safety |
| **Tailwind CSS** | 3.3.0 | Utility-first styling |
| **Lucide React** | 0.553.0 | Icons |

### AI Integrations

| Provider | Package | Purpose |
|----------|---------|---------|
| **OpenAI** | openai@4.104.0 | GPT models |
| **Anthropic** | @anthropic-ai/sdk@0.27.0 | Claude models |
| **Google** | @google/generative-ai@0.1.0 | Gemini models |
| **DeepSeek** | (via OpenAI SDK) | DeepSeek models |

### Backend Services

| Service | Package | Purpose |
|---------|---------|---------|
| **Supabase** | @supabase/supabase-js@2.80.0 | Auth, DB, Storage |
| **Stripe** | stripe@19.3.0, @stripe/stripe-js@8.3.0 | Payments |

### Document Generation

| Package | Purpose |
|---------|---------|
| jspdf@3.0.3 | PDF generation |
| jspdf-autotable@5.0.2 | PDF tables |
| exceljs@4.4.0 | Excel generation |
| pptxgenjs@4.0.1 | PowerPoint generation |
| pdfjs-dist@5.4.394 | PDF parsing |

### Testing

| Package | Purpose |
|---------|---------|
| Jest@30.2.0 | Unit testing |
| Playwright@1.56.1 | E2E testing |
| @testing-library/react@16.3.0 | Component testing |

---

## Deployment Structure

### Current Deployment Model

```
┌──────────────────────────────────────────────────────────────────┐
│                    LOCAL DEVELOPMENT                              │
│                                                                   │
│   ~/AI/AI_Coding/Repositories/torlyAI/                           │
│   ├── npm run dev      → localhost:3000 (Next.js)                │
│   └── Uses .env for Supabase/Stripe/AI keys                     │
└──────────────────────────────────────────────────────────────────┘
                              │
                              ▼ git push
┌──────────────────────────────────────────────────────────────────┐
│                         GITHUB                                    │
│                                                                   │
│   github.com/DukeWood/torlyAI                                    │
│   └── Branch: main                                                │
└──────────────────────────────────────────────────────────────────┘
                              │
              ┌───────────────┴───────────────┐
              ▼                               ▼
┌─────────────────────────────┐   ┌─────────────────────────────────┐
│     VERCEL (Planned)        │   │    ORACLE CLOUD VM              │
│                             │   │                                  │
│  - Auto-deploy on push      │   │  SSH: ubuntu@141.147.89.179     │
│  - Next.js optimized        │   │  (Currently inaccessible)       │
│  - Edge functions           │   │                                  │
│  - CDN                      │   │  Manual deployment via:         │
│                             │   │  - git pull                      │
│                             │   │  - npm install                   │
│                             │   │  - pm2 restart                   │
└─────────────────────────────┘   └─────────────────────────────────┘
```

### Expected Server Stack on Oracle VM

```
/var/www/torly.ai/
├── wordpress/              # WordPress installation
│   ├── wp-admin/
│   ├── wp-content/
│   │   ├── themes/
│   │   ├── plugins/
│   │   └── uploads/
│   └── wp-config.php
│
├── html/                   # Static assets (if any)
│
└── adminer/                # Database admin tool
    └── index.php

/etc/nginx/
├── nginx.conf
└── sites-enabled/
    └── torly.ai            # Nginx vhost config

/etc/mysql/
└── mysql.conf.d/
    └── mysqld.cnf          # MySQL configuration

Logs:
├── /var/log/nginx/         # Nginx access/error logs
├── /var/log/mysql/         # MySQL logs
└── /var/log/php-fpm/       # PHP-FPM logs
```

### Deployment Process (When SSH is Fixed)

```bash
# 1. SSH into server
ssh ubuntu@141.147.89.179

# 2. Navigate to application directory
cd /var/www/torly.ai

# 3. Pull latest changes (if using git)
git pull origin main

# 4. Install dependencies (if Next.js is on VM)
npm install

# 5. Build application
npm run build

# 6. Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.x-fpm
pm2 restart all  # If using PM2 for Node.js
```

---

## Network Configuration

### Security List (Firewall Rules)

#### Ingress Rules (Inbound)

| Protocol | Port | Source | Description |
|----------|------|--------|-------------|
| TCP | 22 | 0.0.0.0/0 | SSH |
| TCP | 80 | 0.0.0.0/0 | HTTP |
| TCP | 443 | 0.0.0.0/0 | HTTPS |
| ICMP | Type 3, Code 4 | 0.0.0.0/0 | Path MTU Discovery |

#### Egress Rules (Outbound)

| Protocol | Destination | Description |
|----------|-------------|-------------|
| All | 0.0.0.0/0 | Allow all outbound |

### DNS Configuration

| Record | Type | Value |
|--------|------|-------|
| torly.ai | A | 141.147.89.179 |
| www.torly.ai | CNAME | torly.ai |

---

## Security Configuration

### SSH Access

| Property | Value |
|----------|-------|
| **SSH User** | ubuntu |
| **SSH Port** | 22 |
| **Auth Method** | Public Key Only |
| **Password Auth** | Disabled |

#### Authorized SSH Key (On Server)

```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDDBsJVTs5gUnkaYY5QbPj+
ODoamZiQpE2DY7YYjyWrnD56kas0412NXT7Mqgaz/SbBnp20ENWI9YJFTnrC
XlzcUK90uvMJd5uP1y3mfO14OOXdEjYWMl6/vy2jGpXJ7haQrslMh3Mq2kyj
aTYqYydKVp8AOh9wcwY2MClovCSJL25ymtRq9mTsS6mFXVvo9YpA9JmevCqA
x308z5s63QvzqOLG5glRxETwbcRqZpsbeqs0EW1PZOOVXdqMeQePWuR71SPV
PJQAyZFQDw2ZTydZrVhbQvglUutOwVgUZhnme7qfJwnwDopmHrrEYczuBFok
b9v10rEco9QdWnHpoG2x ssh-key-2025-11-17
```

**Note**: The private key for this is LOST. See [Known Issues](#known-issues--recovery).

#### Local SSH Keys

**Working Key (Original):**
Location: `torlyAI/.ssh/ssh-key-2025-11-17.key`
```
# This is the WORKING key that matches the server's authorized_keys
ssh -i /Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/.ssh/ssh-key-2025-11-17.key ubuntu@141.147.89.179
```

**Also available at:** `/Users/Jason-uk/AI/AI_Coding/Repositories/torly-blog/.credentials/ssh-key-2025-11-17.key`

**Status**: ✅ WORKING - Successfully connects to Oracle VM

**Alternative Key (NOT authorized):**
Location: `~/.ssh/id_rsa`
```
Fingerprint: SHA256:AlqRbHVjVo2Aei7GfcgXqQ4wthfeODtTzy+QfLlEHuA
Comment: torly-wordpress-oracle
```
**Status**: ❌ This key is NOT on the server's authorized_keys.

---

## Database Configuration

### MySQL Server

| Property | Value |
|----------|-------|
| **Host** | localhost (127.0.0.1) |
| **Port** | 3306 |
| **Version** | 8.x (assumed) |

### WordPress Database

```sql
-- Connection details
Host: localhost
Database: torly_wordpress
Username: torly_user
Password: ChAOOHqfpRtIAbsj

-- Key tables
SHOW TABLES FROM torly_wordpress;
-- Expected: wp_posts, wp_users, wp_options, wp_waitlist, etc.
```

### Supabase Database (Separate)

| Property | Value |
|----------|-------|
| **Provider** | Supabase Cloud |
| **Engine** | PostgreSQL |
| **Purpose** | User auth, subscriptions, evaluations |
| **Tables** | users, subscriptions, user_evaluations, course_progress, resources, autopilot_* |

---

## OCI CLI Configuration

### Config File Locations

**Global (Home Directory):**
```
~/.oci/config
```

**Repository-Local (Recommended for this project):**
```
/Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/.oci/config
```

### Configuration

**Repository-local config** (`torlyAI/.oci/config`):
```ini
[DEFAULT]
user=ocid1.user.oc1..aaaaaaaawo2seatkedupdmpsihlevgnbd7mkqlchgfnz7dqg23gjoy2vzqrq
fingerprint=40:0d:2e:92:4a:ac:5c:0b:84:d2:f5:05:de:12:ad:e8
tenancy=ocid1.tenancy.oc1..aaaaaaaanzvkl4w3dhw6e4lktdivfy2gtwuy52vbzj5xvj3zmxmrhgjyw4fa
region=uk-london-1
key_file=/Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/.oci/oci_api_key.pem
```

### API Key Files

| Location | File | Purpose |
|----------|------|---------|
| **Repository** | `torlyAI/.oci/oci_api_key.pem` | Private key for OCI API (project-local) |
| **Repository** | `torlyAI/.oci/oci_api_key_public.pem` | Public key (project-local) |
| **Global** | `~/.oci/oci_api_key.pem` | Private key for OCI API (home) |
| **Global** | `~/.oci/oci_api_key_public.pem` | Public key (home) |

### Using Repository-Local OCI Config

To use the repository-local OCI configuration, set the `OCI_CONFIG_FILE` environment variable:

```bash
# Set for current session
export OCI_CONFIG_FILE="/Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/.oci/config"

# Or use --config-file flag with OCI CLI
oci --config-file /Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/.oci/config compute instance list --compartment-id $COMPARTMENT_ID
```

### Common OCI CLI Commands

```bash
# List instances
oci compute instance list --compartment-id $COMPARTMENT_ID

# Get instance status
oci compute instance get --instance-id $INSTANCE_ID

# Start/Stop/Restart instance
oci compute instance action --instance-id $INSTANCE_ID --action START
oci compute instance action --instance-id $INSTANCE_ID --action STOP
oci compute instance action --instance-id $INSTANCE_ID --action SOFTRESET

# Get public IP
oci network vnic get --vnic-id $VNIC_ID
```

---

## Known Issues & Recovery

### Issue: SSH Access Lost → RESOLVED ✅

**Status**: ~~CRITICAL~~ **RESOLVED** (2026-01-09)
**Impact**: ~~Cannot deploy code, cannot access server~~ SSH access restored

**Resolution**:
- Original SSH private key (`ssh-key-2025-11-17.key`) was found in `torly-blog/.credentials/`
- Key has been copied to `torlyAI/.ssh/ssh-key-2025-11-17.key`
- SSH connection verified working

**Working SSH Command:**
```bash
ssh -i /Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/.ssh/ssh-key-2025-11-17.key ubuntu@141.147.89.179
```

**Original Root Cause** (for reference):
- Original SSH private key (`ssh-key-2025-11-17`) created on 2025-11-17 was thought to be lost
- Current local key (`~/.ssh/id_rsa`) was created AFTER the instance
- Oracle Cloud does not allow updating `ssh_authorized_keys` after instance launch

**What Was Tried**:
| Method | Result |
|--------|--------|
| Direct SSH | Permission denied (key mismatch) |
| Update instance metadata | Oracle blocks post-launch SSH key changes |
| Add cloud-init user_data | Oracle blocks post-launch user_data |
| OCI Run Command | Management Agent is disabled |
| Serial Console | Connected, but Ubuntu has no password |
| Bastion Service | Not configured |

### Recovery Solution

**Method**: Boot Volume Recovery

```bash
# 1. Terminate current instance (PRESERVES boot volume)
oci compute instance terminate \
  --instance-id $INSTANCE_ID \
  --preserve-boot-volume true

# 2. Launch new instance from boot volume with correct SSH key
oci compute instance launch \
  --availability-domain "CZAd:UK-LONDON-1-AD-1" \
  --compartment-id $COMPARTMENT_ID \
  --shape "VM.Standard.E2.1.Micro" \
  --source-boot-volume-id $BOOT_VOLUME_ID \
  --ssh-authorized-keys-file ~/.ssh/id_rsa.pub \
  --subnet-id $SUBNET_ID

# 3. SSH with new key
ssh ubuntu@<NEW_IP_ADDRESS>
```

**What's Preserved**:
- All data on boot volume (WordPress, MySQL, configs)
- Network security rules
- VCN/Subnet configuration

**What Changes**:
- New public IP address (141.147.89.179 will change)
- DNS needs updating to new IP

### Data Safe Locations

| Data | Location | Status |
|------|----------|--------|
| WordPress files | /var/www/torly.ai | On boot volume - SAFE |
| MySQL data | /var/lib/mysql | On boot volume - SAFE |
| Nginx config | /etc/nginx | On boot volume - SAFE |
| SSL certs | /etc/letsencrypt | On boot volume - SAFE |
| User uploads | wp-content/uploads | On boot volume - SAFE |

---

## Maintenance Procedures

### Routine Maintenance (When SSH Works)

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Check disk space
df -h

# Check memory usage
free -m

# View running processes
htop

# Check service status
sudo systemctl status nginx
sudo systemctl status mysql
sudo systemctl status php8.2-fpm

# View logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/mysql/error.log
```

### Backup Procedures

```bash
# Backup MySQL database
mysqldump -u torly_user -p torly_wordpress > backup_$(date +%Y%m%d).sql

# Backup WordPress files
tar -czvf wordpress_backup_$(date +%Y%m%d).tar.gz /var/www/torly.ai

# Upload to object storage (optional)
oci os object put --bucket-name backups --file backup.tar.gz
```

### SSL Certificate Renewal

```bash
# Check certificate status
sudo certbot certificates

# Renew (usually auto via cron)
sudo certbot renew

# Force renewal
sudo certbot renew --force-renewal
```

---

## Resource IDs Reference

### Compute

| Resource | OCID |
|----------|------|
| Instance | `ocid1.instance.oc1.uk-london-1.anwgiljtdz6cpeyciyko632s6r4fpdoxfydao7rl4chfja76ghnhm2e5rmrq` |
| Boot Volume | `ocid1.bootvolume.oc1.uk-london-1.abwgiljtvxvyat4y72fjuegqlbpb4woyd6fbpwpqyn255w7eefydjnzbr6oq` |
| Image | `ocid1.image.oc1.uk-london-1.aaaaaaaaaqqnwwvppjnkkabddcygclu3vbdytqyfou5cr65lqis6ztakru4a` |

### Networking

| Resource | OCID |
|----------|------|
| VCN | `ocid1.vcn.oc1.uk-london-1.amaaaaaadz6cpeyalib655c4ksgo4nc4fzz4tlleji47b6uulciuq7ia6sva` |
| Subnet | `ocid1.subnet.oc1.uk-london-1.aaaaaaaaopqbrmd6grrkoxgcotq6eapo7mlnc257vadqutbxpa34v2t6onrq` |
| Security List | `ocid1.securitylist.oc1.uk-london-1.aaaaaaaakqwczvhjeg6ox6yrgokb3j7x6pzxkklzcqwvafna42kbnq4ikbyq` |
| Route Table | `ocid1.routetable.oc1.uk-london-1.aaaaaaaax4xdjp7nuagtpvb2u3kmdc4n3wvtilpfgepjshq6ckhczn5claga` |
| DHCP Options | `ocid1.dhcpoptions.oc1.uk-london-1.aaaaaaaapxek5gjyrbnhthz5bfxd6ozzehlxiyktnuy25273bbrw5gsifxhq` |

### Identity

| Resource | OCID |
|----------|------|
| Tenancy | `ocid1.tenancy.oc1..aaaaaaaanzvkl4w3dhw6e4lktdivfy2gtwuy52vbzj5xvj3zmxmrhgjyw4fa` |
| User | `ocid1.user.oc1..aaaaaaaawo2seatkedupdmpsihlevgnbd7mkqlchgfnz7dqg23gjoy2vzqrq` |

---

## Quick Reference Card

### Server Access (Currently Broken)

```bash
# SSH (DOES NOT WORK - key mismatch)
ssh ubuntu@141.147.89.179

# Database Admin (WORKS)
https://torly.ai/db-admin/
```

### OCI CLI Quick Commands

```bash
# Check instance status
oci compute instance get \
  --instance-id ocid1.instance.oc1.uk-london-1.anwgiljtdz6cpeyciyko632s6r4fpdoxfydao7rl4chfja76ghnhm2e5rmrq \
  --query "data.\"lifecycle-state\"" --raw-output

# Start instance
oci compute instance action \
  --instance-id ocid1.instance.oc1.uk-london-1.anwgiljtdz6cpeyciyko632s6r4fpdoxfydao7rl4chfja76ghnhm2e5rmrq \
  --action START

# Stop instance
oci compute instance action \
  --instance-id ocid1.instance.oc1.uk-london-1.anwgiljtdz6cpeyciyko632s6r4fpdoxfydao7rl4chfja76ghnhm2e5rmrq \
  --action STOP
```

### Environment Variables

```bash
export INSTANCE_ID="ocid1.instance.oc1.uk-london-1.anwgiljtdz6cpeyciyko632s6r4fpdoxfydao7rl4chfja76ghnhm2e5rmrq"
export COMPARTMENT_ID="ocid1.tenancy.oc1..aaaaaaaanzvkl4w3dhw6e4lktdivfy2gtwuy52vbzj5xvj3zmxmrhgjyw4fa"
export BOOT_VOLUME_ID="ocid1.bootvolume.oc1.uk-london-1.abwgiljtvxvyat4y72fjuegqlbpb4woyd6fbpwpqyn255w7eefydjnzbr6oq"
export SUBNET_ID="ocid1.subnet.oc1.uk-london-1.aaaaaaaaopqbrmd6grrkoxgcotq6eapo7mlnc257vadqutbxpa34v2t6onrq"
```

---

## Next Steps

1. **Fix SSH Access**: Execute boot volume recovery procedure
2. **Update DNS**: Point torly.ai to new IP after recovery
3. **Document Server Stack**: SSH in and document actual installed services
4. **Set Up CI/CD**: Configure GitHub Actions for automated deployment
5. **Enable Backups**: Set up automated MySQL and file backups
6. **Enable Monitoring**: Enable OCI Management Agent for run commands

---

## Appendix A: SSH Recovery Attempts Log

### Session Date: 2026-01-09

This appendix documents all attempted methods to regain SSH access, their outcomes, and lessons learned.

---

### Attempts Made (All Failed)

| # | Method | Command/Action | Result | Why It Failed |
|---|--------|----------------|--------|---------------|
| 1 | Direct SSH (opc user) | `ssh opc@141.147.89.179` | Permission denied | Wrong user for Ubuntu image |
| 2 | Direct SSH (ubuntu user) | `ssh ubuntu@141.147.89.179` | Permission denied | Local key not in authorized_keys |
| 3 | Direct SSH with key | `ssh -i ~/.ssh/id_rsa ubuntu@141.147.89.179` | Permission denied | Key fingerprints don't match |
| 4 | Update SSH metadata | `oci compute instance update --metadata` | Error 400 | Oracle blocks ssh_authorized_keys changes post-launch |
| 5 | Add cloud-init user_data | `oci compute instance update --metadata user_data` | Error 400 | Oracle blocks user_data changes post-launch |
| 6 | OCI Run Command | `oci instance-agent command create` | Stuck in ACCEPTED | Management Agent plugin is DISABLED |
| 7 | Serial Console | Console connection created | Login prompt, no access | Ubuntu has no password, only key auth |
| 8 | Search for original key | Searched ~/Downloads, ~/Documents, ~/.ssh, Keychain | Not found | Original private key was never saved/lost |
| 9 | Bastion Service | `oci bastion bastion list` | Empty | Bastion not configured |

---

### Detailed Failure Analysis

#### Attempt 1-3: Direct SSH

```bash
# Tried these commands
ssh opc@141.147.89.179                    # Wrong user
ssh ubuntu@141.147.89.179                 # Key mismatch
ssh -i ~/.ssh/id_rsa ubuntu@141.147.89.179  # Key mismatch

# Error received
Permission denied (publickey).
```

**Root Cause**: The VM was created with `ssh-key-2025-11-17` but the private key for that pair is not on this machine. The current `~/.ssh/id_rsa` (fingerprint: `SHA256:AlqRbHVjVo2Aei7GfcgXqQ4wthfeODtTzy+QfLlEHuA`) does not match the server's authorized key.

#### Attempt 4: Update SSH Metadata

```bash
oci compute instance update \
  --instance-id $INSTANCE_ID \
  --metadata '{"ssh_authorized_keys": "...new-key..."}' \
  --force
```

**Error**:
```json
{
  "code": "InvalidParameter",
  "message": "The 'ssh_authorized_keys' metadata field cannot be updated and must be provided with the already existing value."
}
```

**Root Cause**: Oracle Cloud security policy prevents SSH key changes after instance launch.

#### Attempt 5: Add Cloud-Init User Data

```bash
oci compute instance update \
  --instance-id $INSTANCE_ID \
  --metadata '{"ssh_authorized_keys": "...", "user_data": "base64-script"}' \
  --force
```

**Error**:
```json
{
  "code": "InvalidParameter",
  "message": "The 'user_data' metadata field cannot be added to an already launched instance."
}
```

**Root Cause**: Cloud-init user_data can only be set at instance creation, not updated later.

#### Attempt 6: OCI Run Command (Instance Agent)

```bash
oci instance-agent command create \
  --compartment-id $COMPARTMENT_ID \
  --target '{"instanceId": "'$INSTANCE_ID'"}' \
  --content '{"source": {"sourceType": "TEXT", "text": "#!/bin/bash\necho new-key >> ~/.ssh/authorized_keys"}}'
```

**Result**: Command created but stayed in `ACCEPTED` state indefinitely.

**Root Cause**: The Management Agent plugin is DISABLED on this instance. Run Command requires an active agent.

#### Attempt 7: Serial Console Connection

```bash
# Created console connection
oci compute instance-console-connection create \
  --instance-id $INSTANCE_ID \
  --ssh-public-key-file ~/.ssh/id_rsa.pub

# Connected to console
ssh -o ProxyCommand='ssh -W %h:%p -p 443 ...' $INSTANCE_ID
```

**Result**: Connected to serial console, saw login prompt:
```
Ubuntu 22.04.5 LTS instance-20251117-1321 ttyS0
instance-20251117-1321 login: _
```

**Root Cause**: Ubuntu cloud images have password authentication disabled. Can only login with SSH keys, which we can't use from serial console.

#### Attempt 8: Search for Original Key

Searched locations:
- `~/.ssh/` - Only current keys
- `~/Downloads/` - No SSH keys from Nov 17
- `~/Documents/` - Empty
- `~/Desktop/` - Empty
- `~/Library/Mobile Documents/` (iCloud) - No SSH keys
- Mac Keychain - No SSH identities stored
- Time Machine - Snapshot from July 2025 (too old)
- Chrome download history - Unable to query

**Root Cause**: Original private key was likely generated in Oracle Console and either:
- Downloaded but deleted
- Never downloaded
- Saved to a location that was later cleaned up

---

### DO NOT DO (Lessons Learned)

| # | What NOT To Do | Why |
|---|----------------|-----|
| 1 | **Don't lose the SSH private key** | Oracle doesn't allow SSH key updates after launch |
| 2 | **Don't assume OCI Run Command works** | Requires Management Agent to be ENABLED at launch |
| 3 | **Don't try to update user_data after launch** | Oracle blocks this entirely |
| 4 | **Don't rely on serial console for Ubuntu** | Ubuntu has no password auth by default |
| 5 | **Don't use `--public-key`** | Correct param is `--ssh-public-key-file` |
| 6 | **Don't use `--instance-agent-command-id`** | Correct param is `--command-id` |
| 7 | **Don't expect metadata updates to work** | SSH keys are immutable after launch |
| 8 | **Don't skip enabling Management Agent** | Critical for remote troubleshooting |

---

### TODO (For Next Time)

#### At Instance Creation

| # | Action | Command/Steps |
|---|--------|---------------|
| 1 | **Save SSH private key immediately** | Download and store in `~/.ssh/` with backup |
| 2 | **Use existing local key** | `--ssh-authorized-keys-file ~/.ssh/id_rsa.pub` |
| 3 | **Enable Management Agent** | Set plugin to ENABLED in agent config |
| 4 | **Enable Bastion plugin** | For emergency access |
| 5 | **Add backup SSH key** | Add a second key in authorized_keys |
| 6 | **Document key fingerprint** | Record which key was used |

#### Instance Creation Command (Recommended)

```bash
# Create instance with YOUR existing key and management enabled
oci compute instance launch \
  --availability-domain "CZAd:UK-LONDON-1-AD-1" \
  --compartment-id $COMPARTMENT_ID \
  --shape "VM.Standard.E2.1.Micro" \
  --image-id $IMAGE_ID \
  --subnet-id $SUBNET_ID \
  --ssh-authorized-keys-file ~/.ssh/id_rsa.pub \
  --agent-config '{"pluginsConfig": [{"name": "Management Agent", "desiredState": "ENABLED"}]}'
```

#### After Instance Creation

| # | Action | Purpose |
|---|--------|---------|
| 1 | **Test SSH immediately** | Verify access before anything else |
| 2 | **Add backup SSH key on server** | `echo "backup-key" >> ~/.ssh/authorized_keys` |
| 3 | **Set up password for ubuntu user** | `sudo passwd ubuntu` (emergency access) |
| 4 | **Enable OCI Management Agent** | For run-command capability |
| 5 | **Configure Bastion Service** | Alternative access method |
| 6 | **Document everything** | IP, keys, credentials in secure location |

#### Recovery Preparation

```bash
# Add to ~/.ssh/config for easy access
Host torly-oracle
    HostName 141.147.89.179
    User ubuntu
    IdentityFile ~/.ssh/id_rsa
    StrictHostKeyChecking accept-new

# Create backup key pair
ssh-keygen -t rsa -b 4096 -f ~/.ssh/torly_backup -C "torly-backup-key"

# On server (once access restored), add backup key
echo "$(cat ~/.ssh/torly_backup.pub)" >> ~/.ssh/authorized_keys
```

---

### Recovery Procedure (When Ready)

The only remaining option is **Boot Volume Recovery**:

```bash
# Step 1: Set environment variables
export INSTANCE_ID="ocid1.instance.oc1.uk-london-1.anwgiljtdz6cpeyciyko632s6r4fpdoxfydao7rl4chfja76ghnhm2e5rmrq"
export COMPARTMENT_ID="ocid1.tenancy.oc1..aaaaaaaanzvkl4w3dhw6e4lktdivfy2gtwuy52vbzj5xvj3zmxmrhgjyw4fa"
export BOOT_VOLUME_ID="ocid1.bootvolume.oc1.uk-london-1.abwgiljtvxvyat4y72fjuegqlbpb4woyd6fbpwpqyn255w7eefydjnzbr6oq"
export SUBNET_ID="ocid1.subnet.oc1.uk-london-1.aaaaaaaaopqbrmd6grrkoxgcotq6eapo7mlnc257vadqutbxpa34v2t6onrq"
export AD="CZAd:UK-LONDON-1-AD-1"

# Step 2: Terminate instance (PRESERVE boot volume)
oci compute instance terminate \
  --instance-id $INSTANCE_ID \
  --preserve-boot-volume true \
  --wait-for-state TERMINATED

# Step 3: Launch new instance from boot volume
oci compute instance launch \
  --availability-domain "$AD" \
  --compartment-id $COMPARTMENT_ID \
  --shape "VM.Standard.E2.1.Micro" \
  --source-boot-volume-id $BOOT_VOLUME_ID \
  --subnet-id $SUBNET_ID \
  --ssh-authorized-keys-file ~/.ssh/id_rsa.pub \
  --assign-public-ip true \
  --display-name "torly-recovered" \
  --wait-for-state RUNNING

# Step 4: Get new public IP
NEW_INSTANCE_ID=$(oci compute instance list --compartment-id $COMPARTMENT_ID --display-name "torly-recovered" --query "data[0].id" --raw-output)
VNIC_ID=$(oci compute vnic-attachment list --compartment-id $COMPARTMENT_ID --instance-id $NEW_INSTANCE_ID --query "data[0].\"vnic-id\"" --raw-output)
NEW_IP=$(oci network vnic get --vnic-id $VNIC_ID --query "data.\"public-ip\"" --raw-output)
echo "New IP: $NEW_IP"

# Step 5: Update DNS
echo "Update torly.ai DNS A record to: $NEW_IP"

# Step 6: Test SSH
ssh ubuntu@$NEW_IP "echo 'SSH access restored!'"
```

**Time Required**: ~10 minutes
**Data Loss**: None (boot volume preserved)
**IP Change**: Yes (new public IP assigned)

---

### Checklist for Future Instances

- [ ] Use existing local SSH key (`~/.ssh/id_rsa.pub`)
- [ ] Enable Management Agent plugin
- [ ] Enable Bastion plugin
- [ ] Test SSH immediately after creation
- [ ] Add backup SSH key to server
- [ ] Set ubuntu user password
- [ ] Document instance details
- [ ] Add to SSH config file
- [ ] Create recovery runbook

---

*Appendix added: 2026-01-09*

---

*Document generated by Claude Code on 2026-01-09*
