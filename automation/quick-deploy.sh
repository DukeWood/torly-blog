#!/bin/bash

#################################################################
# Quick Deployment Script for TorlyAI
#
# This script helps you set up credentials and run deployment
#################################################################

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 TorlyAI Quick Deployment Setup${NC}"
echo "======================================"
echo ""

# Create credentials directory
CRED_DIR="$(dirname "$0")/../.credentials"
mkdir -p "$CRED_DIR"
chmod 700 "$CRED_DIR"

# Collect Oracle Cloud credentials
echo -e "${YELLOW}Step 1: Oracle Cloud VM Information${NC}"
echo "--------------------------------------"
read -p "Enter your VM Public IP: " VM_IP
read -p "Enter your SSH username (default: ubuntu): " SSH_USER
SSH_USER=${SSH_USER:-ubuntu}
read -p "Enter path to your SSH private key: " SSH_KEY_PATH

# Expand tilde to full path
SSH_KEY_PATH="${SSH_KEY_PATH/#\~/$HOME}"

# Validate SSH key exists
if [ ! -f "$SSH_KEY_PATH" ]; then
    echo "❌ SSH key not found at: $SSH_KEY_PATH"
    exit 1
fi

# Create Oracle credentials file
cat > "$CRED_DIR/oracle_credentials.json" << EOF
{
  "vmIP": "$VM_IP",
  "sshUsername": "$SSH_USER",
  "sshPrivateKeyPath": "$SSH_KEY_PATH",
  "vmName": "torlyai-wordpress-vm",
  "createdAt": "$(date -u +"%Y-%m-%dT%H:%M:%SZ")"
}
EOF

chmod 600 "$CRED_DIR/oracle_credentials.json"
echo -e "${GREEN}✅ Oracle credentials saved${NC}"
echo ""

# Collect GoDaddy credentials
echo -e "${YELLOW}Step 2: GoDaddy API Credentials${NC}"
echo "--------------------------------------"
echo "Get your credentials from: https://developer.godaddy.com/keys"
echo ""
read -p "Enter your GoDaddy API Key: " GODADDY_KEY
read -p "Enter your GoDaddy API Secret: " GODADDY_SECRET

# Create GoDaddy credentials file
cat > "$CRED_DIR/godaddy_credentials.json" << EOF
{
  "apiKey": "$GODADDY_KEY",
  "apiSecret": "$GODADDY_SECRET",
  "createdAt": "$(date -u +"%Y-%m-%dT%H:%M:%SZ")"
}
EOF

chmod 600 "$CRED_DIR/godaddy_credentials.json"
echo -e "${GREEN}✅ GoDaddy credentials saved${NC}"
echo ""

# Summary
echo "======================================"
echo -e "${GREEN}📋 Configuration Summary${NC}"
echo "======================================"
echo "VM IP: $VM_IP"
echo "SSH User: $SSH_USER"
echo "SSH Key: $SSH_KEY_PATH"
echo "GoDaddy API: Configured ✓"
echo ""

# Test SSH connection
echo -e "${YELLOW}Testing SSH connection...${NC}"
if ssh -i "$SSH_KEY_PATH" -o StrictHostKeyChecking=no -o ConnectTimeout=10 ${SSH_USER}@${VM_IP} "echo 'SSH connection successful'" 2>/dev/null; then
    echo -e "${GREEN}✅ SSH connection successful${NC}"
else
    echo -e "${YELLOW}⚠️  SSH connection failed. Please check:${NC}"
    echo "   1. VM is running"
    echo "   2. Security rules allow SSH (port 22)"
    echo "   3. SSH key is correct"
    echo ""
    read -p "Continue anyway? (y/N): " CONTINUE
    if [[ ! $CONTINUE =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

echo ""
echo "======================================"
echo -e "${GREEN}🎉 Setup Complete!${NC}"
echo "======================================"
echo ""
echo "Ready to deploy! Choose an option:"
echo ""
echo "1. Run full deployment now (recommended)"
echo "2. Just save credentials and exit"
echo ""
read -p "Enter choice (1 or 2): " CHOICE

if [ "$CHOICE" == "1" ]; then
    echo ""
    echo -e "${GREEN}Starting deployment...${NC}"
    echo ""

    # Install npm dependencies if needed
    if [ ! -d "$(dirname "$0")/../mcp-integration/node_modules" ]; then
        echo "Installing npm dependencies..."
        (cd "$(dirname "$0")/../mcp-integration" && npm install)
    fi

    # Run deployment with skip-oracle flag
    node "$(dirname "$0")/deploy-all.js" --skip-oracle
else
    echo ""
    echo "Credentials saved! To deploy later, run:"
    echo "  node automation/deploy-all.js --skip-oracle"
    echo ""
fi
