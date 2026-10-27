#!/bin/sh
set -e

# Pastikan folder penyimpanan, cache, dan uploads ada
mkdir -p /var/www/storage/framework/cache/data \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs \
         /var/www/storage/app/public \
         /var/www/bootstrap/cache \
         /var/www/public/uploads

# Atur permission agar www-data bisa menulis ke storage, cache, dan uploads
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public/uploads 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/public/uploads 2>/dev/null || true

# Jalankan command yang diberikan ke container (default: php-fpm)
exec "$@"
