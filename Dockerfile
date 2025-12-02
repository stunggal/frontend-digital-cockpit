# Multi-stage Dockerfile: build Node assets, then PHP-FPM app
FROM node:18-alpine AS node_build
WORKDIR /app
COPY package.json package-lock.json* ./
COPY resources resources
# Ensure public exists so later COPY won't fail if build produced no files
RUN mkdir -p /app/public \
    && npm ci --silent \
    && npm run build || true

FROM php:8.1-fpm
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
# Install PHP dependencies declared in composer files (if present)
COPY composer.json composer.lock* ./
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction --no-progress || true; fi

# Copy application code
COPY . .

# Copy built assets from node stage (if any)
COPY --from=node_build /app/public /var/www/html/public

# Ensure storage and cache are writable
RUN set -ex \
    && mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

EXPOSE 9000
CMD ["php-fpm"]
