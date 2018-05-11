FROM php:7.0-fpm

RUN apt-get update && apt-get install -y \
        zip unzip \
        git \
        libmcrypt-dev \
        mysql-client \
        libz-dev \
        libmemcached-dev \
        && pecl install memcached \
        && docker-php-ext-install mcrypt pdo_mysql \
        && docker-php-ext-enable memcached\

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
