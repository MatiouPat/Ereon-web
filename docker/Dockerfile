FROM php:8.1-apache

WORKDIR /var/www/

RUN apt-get update && \
    apt install -y \
        npm

RUN docker-php-ext-install \
        pdo_mysql \
        zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /var/www/

COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN composer install && \
    npm install && \
    npm run build

RUN chmod +x ./docker/entrypoint.sh

ENTRYPOINT [ "./docker/entrypoint.sh" ]