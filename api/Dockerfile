FROM  php:7.2-apache

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y

RUN pecl install mongodb \
    && docker-php-ext-enable --ini-name 50-mongodb.ini mongodb.so