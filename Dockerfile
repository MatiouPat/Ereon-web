FROM php:8.1.2-apache

WORKDIR /var/www/

RUN apt-get update && \
    docker-php-ext-install \
        pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /var/www/

RUN rm .env.local

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

RUN chmod +x ./entrypoint.sh

RUN composer install