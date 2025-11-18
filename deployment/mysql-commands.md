# MySQL Database Access Guide

## Database Credentials
- **Host**: localhost (141.147.89.179)
- **Database**: torly_wordpress
- **Username**: torly_user
- **Password**: ChAOOHqfpRtIAbsj
- **Port**: 3306

## Web Access
**URL**: https://torly.ai/db-admin/

## Common SQL Queries

### View Waitlist
```sql
SELECT * FROM wp_waitlist ORDER BY created_at DESC;
```

### Count Total Signups
```sql
SELECT COUNT(*) as total FROM wp_waitlist;
```

### Today's Signups
```sql
SELECT * FROM wp_waitlist
WHERE DATE(created_at) = CURDATE()
ORDER BY created_at DESC;
```

### This Week's Signups
```sql
SELECT * FROM wp_waitlist
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY created_at DESC;
```

### Export Emails (CSV format)
```sql
SELECT email FROM wp_waitlist
WHERE status = 'active'
ORDER BY created_at ASC;
```

### Mark as Notified
```sql
UPDATE wp_waitlist
SET status = 'notified', notified_at = NOW()
WHERE email = 'user@example.com';
```

### Mark All as Notified
```sql
UPDATE wp_waitlist
SET status = 'notified', notified_at = NOW()
WHERE status = 'active';
```

### Search by Email
```sql
SELECT * FROM wp_waitlist
WHERE email LIKE '%@gmail.com%';
```

### Delete Test Entries
```sql
DELETE FROM wp_waitlist
WHERE email = 'test@example.com';
```

## SSH Access

```bash
# Connect to server
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179

# Access MySQL directly
sudo mysql torly_wordpress

# Or with password
mysql -u torly_user -p torly_wordpress
# Password: ChAOOHqfpRtIAbsj
```

## MySQL Workbench (Desktop App)

1. Download: https://dev.mysql.com/downloads/workbench/
2. Create new connection:
   - Connection Name: TorlyAI Database
   - Hostname: 141.147.89.179
   - Port: 3306
   - Username: torly_user
   - Password: ChAOOHqfpRtIAbsj
   - Default Schema: torly_wordpress

**Note**: You'll need to configure SSH tunnel or open port 3306 in Oracle Cloud firewall.

## TablePlus (Mac)

1. Download: https://tableplus.com/
2. Create new connection:
   - Type: MySQL
   - Host: 141.147.89.179
   - Port: 3306
   - User: torly_user
   - Password: ChAOOHqfpRtIAbsj
   - Database: torly_wordpress

## Security Notes

- Web admin panel: https://torly.ai/db-admin/
- **Important**: Delete /var/www/html/db-admin/ when not in use for security
- Keep database password secure
- Don't commit passwords to git

## Backup Database

```bash
# Full backup
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 \
  "sudo mysqldump torly_wordpress > /tmp/backup_$(date +%Y%m%d).sql"

# Waitlist only
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 \
  "sudo mysqldump torly_wordpress wp_waitlist > /tmp/waitlist_backup.sql"
```

## Restore Database

```bash
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 \
  "sudo mysql torly_wordpress < /tmp/backup.sql"
```
