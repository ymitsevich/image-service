FROM php:8.2-fpm-alpine as base

RUN apk add -U --no-cache procps git openssh-client autoconf gcc make libc-dev libzip-dev bzip2-dev libxml2-dev libpng-dev g++ icu-dev
RUN docker-php-ext-install intl gd exif
RUN apk add -U --no-cache imagemagick imagemagick-dev imagemagick-libs musl libmagic \
 && pecl install imagick \
 && docker-php-ext-enable imagick
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --chown=www-data ./app /app
COPY .infra/php/php.prod.ini /usr/local/etc/php/conf.d/php.local.ini

WORKDIR /app

RUN composer install --no-interaction --no-progress --optimize-autoloader
CMD ["php-fpm"]

EXPOSE 9000

FROM base as dev

COPY .infra/php/php.dev.ini /usr/local/etc/php/conf.d/php.local.ini

RUN apk add -U --no-cache linux-headers
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
