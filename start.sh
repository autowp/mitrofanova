#!/bin/sh

set -e

mkdir -p /var/run/php
mkdir -p /run/php
mkdir -p /run/nginx

mkdir -p /var/log/nginx 
mkdir -p /var/log/php7
mkdir -p /var/log/supervisor
mkdir -p /app/logs && chmod 0777 /app/logs

if [ ! -f data/url.db ]; then
    sqlite3 data/url.db < db.sql
fi

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
