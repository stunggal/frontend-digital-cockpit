# Gunakan image PHP-Apache resmi sebagai basis
FROM php:8.3-apache

# Instal dependensi sistem yang dibutuhkan oleh Laravel (misalnya GD, PDO)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    && rm -rf /var/lib/apt/lists/*

# Instal ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Konfigurasi Apache: Aktifkan module rewrite
RUN a2enmod rewrite

# Atur direktori kerja di dalam kontainer
WORKDIR /var/www/html

# Salin composer.lock dan composer.json untuk menginstal dependensi
COPY composer.json composer.lock ./

# Instal Composer (sebagai bagian dari proses build)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Jalankan Composer install
# --no-dev untuk production, --no-scripts untuk menghindari error sebelum seluruh kode disalin
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Salin semua kode aplikasi ke direktori kerja kontainer
COPY . .

# Berikan hak akses ke direktori storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate key (hanya jika belum ada di .env)
# RUN php artisan key:generate

# Konfigurasi Virtual Host Apache agar mengarah ke public/
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Secara default, CMD dari base image akan menjalankan Apache