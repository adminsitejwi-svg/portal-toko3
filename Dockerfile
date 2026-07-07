# PHP 8.3 FPM untuk menjalankan CodeIgniter 4
FROM php:8.3-fpm

# Paket sistem + ekstensi PHP yang dibutuhkan CI4 (mysqli, intl, mbstring)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev libonig-dev libzip-dev unzip git \
    && docker-php-ext-install mysqli pdo_mysql intl mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Salin seluruh proyek
COPY . /var/www/html

# Pastikan folder writable bisa ditulis oleh PHP-FPM (www-data)
RUN chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

EXPOSE 9000
CMD ["php-fpm"]
