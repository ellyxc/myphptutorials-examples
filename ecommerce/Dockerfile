FROM php:8-apache

RUN apt-get update \
 && apt-get install -y git zlib1g-dev libzip-dev \
 && docker-php-ext-install zip \
 && a2enmod rewrite \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update \
    && apt-get install -y unzip;

RUN docker-php-ext-install pdo_mysql;
RUN apt-get update && apt-get install -y \
             libfreetype6-dev \
             libjpeg62-turbo-dev \
             libpng-dev \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql

RUN apt-get update \
    && apt-get install -y locales gettext\
    && echo 'en_US.UTF-8 UTF-8' >> /etc/locale.gen \
    && echo 'en_US ISO-8859-1' >> /etc/locale.gen \
    && echo 'en_US.ISO-8859-15 ISO-8859-15' >> /etc/locale.gen \
    && locale-gen \
    && docker-php-ext-install gettext \
    && docker-php-ext-enable gettext

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www