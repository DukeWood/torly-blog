# Security Guidelines

## ✅ Security Measures Implemented

### 1. Gitignore Protection
All sensitive files are protected from accidental commits:
- `.credentials/` - API keys, SSH keys, passwords
- `*.key`, `*.pem` - Private keys
- `*.png` - Screenshots (may contain sensitive info)
- `*credentials*.json` - All credential files

### 2. File Permissions
Sensitive files have restricted permissions:
- SSH keys: `600` (owner read/write only)
- Credential files: `600` (owner read/write only)
- `.credentials` directory: `700` (owner access only)

### 3. Credentials Location
All sensitive credentials are stored in `.credentials/`:
```
.credentials/
├── godaddy_credentials.json    (API key & secret)
├── godaddy_login.json          (Web login credentials)
├── oracle_credentials.json     (VM details & SSH path)
└── ssh-key-2025-11-17.key     (SSH private key for Oracle VM)
```

## ⚠️ Security Checklist

Before committing code, verify:
- [ ] No credentials in code
- [ ] No API keys hardcoded
- [ ] No passwords in plain text
- [ ] All sensitive files in `.gitignore`
- [ ] SSH keys have `600` permissions
- [ ] `.credentials` directory has `700` permissions

## 🔒 Best Practices

1. **Never commit credentials**
   - Always use environment variables or credential files
   - Keep credentials in `.credentials/` directory

2. **Rotate keys regularly**
   - Change API keys every 90 days
   - Generate new SSH keys for different environments

3. **Use strong passwords**
   - Minimum 12 characters
   - Mix of letters, numbers, symbols

4. **Backup credentials securely**
   - Use encrypted password manager
   - Never email credentials

5. **Monitor access**
   - Review Oracle Cloud audit logs
   - Check GoDaddy API usage

## 🚨 If Credentials Are Compromised

1. **Immediate Actions:**
   - Revoke compromised keys in GoDaddy/Oracle console
   - Generate new keys
   - Update `.credentials/` files
   - Change all affected passwords

2. **Git History:**
   - If accidentally committed, use `git filter-branch` or BFG Repo-Cleaner
   - Force push to remove from remote
   - Notify all team members to re-clone

3. **Report:**
   - Document the incident
   - Update this security guide with lessons learned

## 📋 Current Credentials Inventory

| Service | Type | Location | Expiry |
|---------|------|----------|--------|
| GoDaddy API | API Key | `.credentials/godaddy_credentials.json` | None |
| GoDaddy Web | Password | `.credentials/godaddy_login.json` | None |
| Oracle Cloud | SSH Key | `.credentials/ssh-key-2025-11-17.key` | None |
| Oracle Cloud | API Key | `~/.oci/oci_api_key.pem` | None |

## 🔄 Key Rotation Schedule

- **GoDaddy API:** Rotate every 90 days
- **SSH Keys:** Rotate every 180 days
- **Passwords:** Change every 60 days

---

**Last Updated:** 2025-11-17
**Security Contact:** jasonxu05@gmail.com
