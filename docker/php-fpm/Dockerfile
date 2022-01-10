FROM php:8.1.0-fpm
WORKDIR /app

RUN apt-get update && apt-get -y install git zip libzip4 libzip-dev curl
RUN docker-php-ext-install pdo pdo_mysql zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer