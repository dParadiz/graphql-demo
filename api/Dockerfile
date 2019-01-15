FROM  php:7.2-apache

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    zip \
    unzip 

RUN pecl install mongodb \
    && docker-php-ext-enable --ini-name 50-mongodb.ini mongodb.so

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php