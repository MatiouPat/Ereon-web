FROM php:8.2-fpm-alpine

WORKDIR /var/www/

RUN apk update && \
    apk add --no-cache \
        npm \
        icu-dev \
        libzip-dev \
        fcgi

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions \
        pdo_pgsql \
        opcache \
        intl \
        zip \
        apcu

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY ./docker/php/app.ini /usr/local/etc/php/conf.d/app.ini

COPY . .

RUN composer install && \
    npm install && \
    npm run build

RUN chmod +x ./docker/php/entrypoint.sh

ENTRYPOINT [ "./docker/php/entrypoint.sh" ]