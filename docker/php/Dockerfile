FROM php:8.1-fpm-alpine

WORKDIR /var/www/

RUN apk update && \
    apk add --no-cache \
        npm \
        icu-dev \
        libzip-dev \
        fcgi

RUN docker-php-ext-install \
        pdo_mysql \
        opcache \
        intl \
        zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY ./docker/php/app.ini /usr/local/etc/php/conf.d/app.ini

COPY . .

RUN composer install && \
    npm install && \
    npm run build

RUN chmod +x ./docker/php/entrypoint.sh

ENTRYPOINT [ "./docker/php/entrypoint.sh" ]