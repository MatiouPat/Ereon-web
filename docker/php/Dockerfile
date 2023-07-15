FROM php:8.1-fpm-alpine

WORKDIR /var/www/

RUN apk update && \
    apk add --no-cache \
        npm

RUN docker-php-ext-install \
        pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install && \
    npm install && \
    npm run build

RUN chmod +x ./docker/php/entrypoint.sh

ENTRYPOINT [ "./docker/php/entrypoint.sh" ]