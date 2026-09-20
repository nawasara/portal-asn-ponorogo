# syntax=docker/dockerfile:1
# ============================================================
# Portal ASN Ponorogo, image produksi.
#
# Menggantikan Laravel Sail yang dipakai sebelumnya. Sail adalah perkakas
# pengembangan: ia memasang Xdebug, menjalankan Vite dev server di port 5173,
# dan mem-bind seluruh direktori proyek ke dalam container, sehingga kode dan
# dependensi di produksi mengikuti apa pun yang kebetulan ada di disk.
#
# Polanya mengikuti deploy/ milik nandur-panguripan yang sudah terbukti.
# ============================================================

# ============================================================
# Stage 1, bangun aset frontend
# ============================================================
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY . .
RUN npm run build

# ============================================================
# Stage 2, basis PHP dipakai bersama stage vendor dan runtime
#
# Vendor dibangun di atas basis yang sama dengan runtime supaya composer
# menyelesaikan dependensi terhadap ekstensi yang benar-benar tersedia nanti.
# ============================================================
FROM php:8.3-fpm-alpine AS php-base
RUN apk add --no-cache \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        icu-dev \
        oniguruma-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        intl \
        exif \
        pcntl \
    && apk del $PHPIZE_DEPS \
    && rm -rf /tmp/* /var/cache/apk/*

# ============================================================
# Stage 3, dependensi PHP tanpa dev
# ============================================================
FROM php-base AS vendor
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ============================================================
# Stage 4, runtime PHP-FPM
# ============================================================
FROM php-base AS runtime
COPY deploy/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

# Volume bernama akan menimpa /var/www/html/public saat runtime, jadi simpan
# salinan untuk disinkronkan kembali oleh entrypoint.
RUN cp -R public /var/www/html/public-dist

RUN chown -R www-data:www-data storage bootstrap/cache public-dist \
    && chmod -R ug+rwX storage bootstrap/cache

COPY deploy/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

USER www-data
EXPOSE 9000
ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]
