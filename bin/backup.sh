#!/bin/bash

#
## Database Backup script
## Author: @lcherone <lawrence@cherone.co.uk>
#

PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
export DISPLAY=:0.0

# Database credentials
user="app"
password="lsAZ0XEX5Kk5q9QnSUzqa1bVaFOxHF7b"
host="127.0.0.1"
db_name="app"

# Set paths ect
date=$(date +"%d-%b-%Y")

# Wrap logic to catch console output
{
    ##############################################################
    # - Start database dump latest
    #
    # In cron we are doing:
    # */5 * * * * cd /var/www/html/bin && bash backup.sh
    #
    # With the idea.. in development backup every 5 mins, with the latest 
    #                 and then copy it to the daily if does not exist.
    
    mysqldump --user=$user --password=$password --host=$host $db_name | gzip > /var/www/html/backups/adminer.sql.gz

    # Check if already backed up today
    if [ ! -f /var/www/html/backups/$date.sql.gz ]; then
        # Dump database into SQL file
        cp /var/www/html/backups/adminer.sql.gz /var/www/html/backups/$date.sql.gz
        #mysqldump --user=$user --password=$password --host=$host $db_name | gzip > /var/www/html/backups/$date.sql.gz
    fi

    # Delete files older than 7 days
    find /var/www/html/backups/*.sql.gz -mtime +7 -exec rm {} \;

    ##############################################################
    
} &> /dev/null
