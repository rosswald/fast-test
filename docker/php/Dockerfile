FROM php:8.5-fpm-alpine

RUN apk add --no-cache sqlite-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

RUN docker-php-ext-install -j"$(nproc)" \
    pdo_sqlite \
    gd

COPY docker/php/zz-local.ini /usr/local/etc/php/conf.d/zz-local.ini

RUN sed -i \
    -e 's/^user = .*/user = www-data/' \
    -e 's/^group = .*/group = www-data/' \
    /usr/local/etc/php-fpm.d/www.conf