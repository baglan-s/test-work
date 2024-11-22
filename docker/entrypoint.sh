#!/bin/sh

# nginx -g "daemon on;"
/usr/bin/supervisord -c /etc/supervisor/laravel-worker.conf
cron
crontab /etc/cron.d/root
php-fpm