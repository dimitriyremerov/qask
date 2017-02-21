FROM php:7.1-fpm

RUN apt-get update && apt-get install -y unzip git && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY docker/composer-install.sh /composer-install.sh
RUN /bin/sh /composer-install.sh
RUN rm -f /composer-install.sh

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install --no-plugins --no-scripts --no-dev && composer clear-cache

