#!/bin/bash
# =====================================================
# Database Backup Script for AB Pet Grooming
# Run daily via cron: 0 2 * * * /path/to/backup_db.sh
# =====================================================

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/backups"
DB_NAME="ab_pet_grooming"
DB_USER="ab_pet_user"
DB_PASS="CHANGE_THIS_TO_YOUR_DB_PASSWORD"

# Create backup directory
mkdir -p $BACKUP_DIR

# Dump database with compression
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME --single-transaction --routines --triggers | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Check if backup succeeded
if [ $? -eq 0 ]; then
    echo "[$(date)] Backup successful: db_$DATE.sql.gz"
else
    echo "[$(date)] ERROR: Backup failed!"
    exit 1
fi

# Keep only last 7 days of backups
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +7 -delete

# Keep only last 30 days of backups (compressed)
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +30 -delete

echo "[$(date)] Backup cleanup complete. Remaining backups:"
ls -lh $BACKUP_DIR/db_*.sql.gz | tail -5