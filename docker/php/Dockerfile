FROM php:8.2-fpm

# Dependency
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    libzip-dev libpq-dev rsync \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql pgsql \
    mbstring exif pcntl bcmath gd zip

RUN docker-php-ext-enable opcache

# Raise upload limits to match the app's largest allowed upload (25MB)
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy semua project Laravel. File yang tidak boleh ikut ke dalam image (.env, .git,
# vendor lokal, node_modules, dll.) didaftarkan di .dockerignore di root project.
COPY . .

# Install dependency (tanpa paket dev di image produksi)
RUN composer install --no-dev --optimize-autoloader

# Simpan salinan bersih public/ di luar volume mount, dipakai entrypoint.sh untuk
# menyegarkan volume bersama dengan Nginx setiap container start (lihat entrypoint.sh).
RUN cp -r public /opt/public-src

# Catatan yang sengaja DIHILANGKAN dari sini (dan kenapa):
# - "php artisan migrate" saat build: butuh koneksi database & kredensial .env yang asli
#   tersedia saat `docker build`, dan kredensial tsb ikut permanen tersimpan di layer image.
#   Migrasi sekarang dijalankan oleh entrypoint.sh setiap container start, sesudah .env
#   di-mount saat runtime, bukan saat build.
# - "php artisan route:cache" langsung diikuti "route:clear": dua perintah itu saling
#   meniadakan (cache lalu langsung dihapus lagi), jadi tidak berguna. route:cache yang
#   benar dijalankan entrypoint.sh setelah migrasi.

RUN chmod +x docker/php/entrypoint.sh \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

ENTRYPOINT ["docker/php/entrypoint.sh"]
CMD ["php-fpm"]
