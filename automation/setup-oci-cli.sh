#!/bin/bash

#################################################################
# OCI CLI Setup Helper Script
#
# This script helps you set up OCI CLI configuration
#################################################################

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${GREEN}🔧 Oracle Cloud Infrastructure CLI Setup${NC}"
echo "=========================================="
echo ""

# Create .oci directory
OCI_DIR="$HOME/.oci"
mkdir -p "$OCI_DIR"
chmod 700 "$OCI_DIR"

echo -e "${YELLOW}To configure OCI CLI, you need to get some information from Oracle Cloud Console.${NC}"
echo ""
echo "I'll guide you through each step."
echo ""

# Step 1: Get User OCID
echo -e "${BLUE}Step 1: Get your User OCID${NC}"
echo "-------------------------------"
echo "1. Go to Oracle Cloud Console: https://cloud.oracle.com/"
echo "2. Click your profile icon (top-right) → User Settings"
echo "3. Copy your OCID (it starts with 'ocid1.user...')"
echo ""
read -p "Paste your User OCID here: " USER_OCID

# Step 2: Get Tenancy OCID
echo ""
echo -e "${BLUE}Step 2: Get your Tenancy OCID${NC}"
echo "-------------------------------"
echo "1. In Oracle Cloud Console, click profile icon → Tenancy: [your tenancy name]"
echo "2. Copy the Tenancy OCID (it starts with 'ocid1.tenancy...')"
echo ""
read -p "Paste your Tenancy OCID here: " TENANCY_OCID

# Step 3: Get Region
echo ""
echo -e "${BLUE}Step 3: Confirm your Region${NC}"
echo "-------------------------------"
echo "From your PDF, it looks like you're in UK South (London)"
echo "Region identifier: uk-london-1"
echo ""
read -p "Enter region identifier [uk-london-1]: " REGION
REGION=${REGION:-uk-london-1}

# Step 4: Generate API Key
echo ""
echo -e "${BLUE}Step 4: Generate API Signing Key${NC}"
echo "-------------------------------"
echo "Generating RSA key pair..."

API_KEY_DIR="$OCI_DIR"
API_KEY_FILE="$API_KEY_DIR/oci_api_key.pem"
API_KEY_PUBLIC="$API_KEY_DIR/oci_api_key_public.pem"

if [ -f "$API_KEY_FILE" ]; then
    echo "API key already exists at $API_KEY_FILE"
    read -p "Overwrite? (y/N): " OVERWRITE
    if [[ ! $OVERWRITE =~ ^[Yy]$ ]]; then
        echo "Using existing key"
    else
        openssl genrsa -out "$API_KEY_FILE" 2048
        chmod 600 "$API_KEY_FILE"
        openssl rsa -pubout -in "$API_KEY_FILE" -out "$API_KEY_PUBLIC"
    fi
else
    openssl genrsa -out "$API_KEY_FILE" 2048
    chmod 600 "$API_KEY_FILE"
    openssl rsa -pubout -in "$API_KEY_FILE" -out "$API_KEY_PUBLIC"
fi

echo -e "${GREEN}✅ API key pair generated${NC}"
echo ""

# Step 5: Get API Key Fingerprint
echo -e "${BLUE}Step 5: Upload Public Key to Oracle Cloud${NC}"
echo "-------------------------------"
echo "Now you need to upload the public key to Oracle Cloud:"
echo ""
echo "1. Go to Oracle Cloud Console: https://cloud.oracle.com/"
echo "2. Click profile icon → User Settings"
echo "3. Scroll down to 'API Keys' section"
echo "4. Click 'Add API Key'"
echo "5. Select 'Paste Public Key'"
echo "6. Copy and paste the following public key:"
echo ""
echo "--- BEGIN PUBLIC KEY (copy from line below) ---"
cat "$API_KEY_PUBLIC"
echo "--- END PUBLIC KEY (copy until line above) ---"
echo ""
echo "7. Click 'Add'"
echo "8. Oracle will show you a 'Configuration File Preview'"
echo "9. Look for the 'fingerprint' line (it looks like: aa:bb:cc:...)"
echo ""
read -p "After uploading, paste the fingerprint here: " FINGERPRINT

# Create OCI config file
echo ""
echo -e "${BLUE}Creating OCI configuration file...${NC}"

cat > "$OCI_DIR/config" << EOF
[DEFAULT]
user=$USER_OCID
fingerprint=$FINGERPRINT
tenancy=$TENANCY_OCID
region=$REGION
key_file=$API_KEY_FILE
EOF

chmod 600 "$OCI_DIR/config"

echo -e "${GREEN}✅ OCI CLI configured successfully!${NC}"
echo ""
echo "Configuration saved to: $OCI_DIR/config"
echo ""

# Test the configuration
echo -e "${BLUE}Testing OCI CLI configuration...${NC}"
if oci iam region list --output table 2>/dev/null; then
    echo -e "${GREEN}✅ OCI CLI is working!${NC}"
else
    echo -e "${YELLOW}⚠️  Configuration created but test failed. Please verify your credentials.${NC}"
fi

echo ""
echo "=========================================="
echo -e "${GREEN}Setup Complete!${NC}"
echo "=========================================="
