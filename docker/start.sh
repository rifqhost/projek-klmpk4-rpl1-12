#!/bin/sh
set -eu

PORT="${PORT:-10000}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p /var/www/html/uploads/images /var/www/html/uploads/sessions
chown -R www-data:www-data /var/www/html/uploads

exec apache2-foreground
