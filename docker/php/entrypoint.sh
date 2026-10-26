#!/bin/sh
set -e

# Pastikan folder penyimpanan dan cache ada
mkdir -p /var/www/storage/framework/cache/data \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs \
         /var/www/bootstrap/cache

# Atur permission agar www-data bisa menulis ke storage & cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Jalankan command yang diberikan ke container (default: php-fpm)
exec "$@"

