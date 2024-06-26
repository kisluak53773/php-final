FROM php:fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS \
    autoconf \
    g++ \
    make \
    linux-headers

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY php/xdebug.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www