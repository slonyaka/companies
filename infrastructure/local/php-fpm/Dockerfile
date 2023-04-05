FROM php:8.1-fpm

RUN set -xe; \
    apt-get update; \
    apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# xdebug
RUN pecl install xdebug-3.1.5 && docker-php-ext-enable xdebug


EXPOSE 9000
