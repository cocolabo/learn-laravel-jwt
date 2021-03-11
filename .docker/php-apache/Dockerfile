FROM php:8.0.2-apache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN echo 'date.timezone = Asia/Tokyo' >> "$PHP_INI_DIR/php.ini"

RUN apt-get update \
    && apt-get install -y \
        vim \
        zip \
        unzip \
        git \
        curl \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libicu-dev

RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql bcmath opcache intl
RUN pecl install xdebug

RUN a2enmod rewrite
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default
