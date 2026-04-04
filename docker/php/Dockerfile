FROM php:8.2-fpm

# Dependency
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    libzip-dev libpq-dev

# PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql pgsql \
    mbstring exif pcntl bcmath gd zip

RUN docker-php-ext-enable opcache

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy semua project Laravel
COPY . /var/www

# Install dependency
RUN composer install --no-dev --optimize-autoloader

# Migration Database
RUN php artisan migrate

# clear cache and config
RUN php artisan config:cache 

# Permission
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache