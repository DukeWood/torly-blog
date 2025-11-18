#!/bin/bash
# View TorlyAI Waitlist Entries
# Usage: bash deployment/view-waitlist.sh [count|export]

SSH_KEY="/Users/Jason-uk/AI/AI_Coding/Repositories/torly-wordpress-setup/.credentials/ssh-key-2025-11-17.key"
SERVER="ubuntu@141.147.89.179"

case "$1" in
  "count")
    echo "📊 Waitlist Statistics"
    echo "====================="
    ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
      "sudo mysql -e \"SELECT
        COUNT(*) as total_signups,
        COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today,
        COUNT(CASE WHEN DATE(created_at) >= CURDATE() - INTERVAL 7 DAY THEN 1 END) as this_week
      FROM torly_wordpress.wp_waitlist;\" -t"
    ;;

  "export")
    echo "💾 Exporting waitlist to CSV..."
    ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
      "sudo mysql -e \"SELECT email, status, created_at FROM torly_wordpress.wp_waitlist ORDER BY created_at DESC\" -B | sed 's/\t/,/g'" > waitlist_$(date +%Y%m%d).csv
    echo "✅ Exported to: waitlist_$(date +%Y%m%d).csv"
    ;;

  *)
    echo "📋 Latest Waitlist Entries"
    echo "========================="
    ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
      "sudo mysql -e \"SELECT id, email, status, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as joined FROM torly_wordpress.wp_waitlist ORDER BY created_at DESC LIMIT 20;\" -t"

    echo ""
    echo "Commands:"
    echo "  bash deployment/view-waitlist.sh         - View latest entries"
    echo "  bash deployment/view-waitlist.sh count   - Show statistics"
    echo "  bash deployment/view-waitlist.sh export  - Export to CSV"
    ;;
esac
