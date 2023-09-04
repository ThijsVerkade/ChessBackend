FROM php:8.2-fpm
WORKDIR /var/www

RUN docker-php-ext-install pdo_mysql
RUN pecl install xdebug redis && docker-php-ext-enable xdebug redis

RUN apt-get update -y && apt-get install -y git

RUN curl https://getcomposer.org/composer.phar --output /usr/bin/composer \
    && chmod +x /usr/bin/composer \
    && composer self-update

COPY xdebug.ini /usr/local/etc/php/conf.d/
