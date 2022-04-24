FROM php:8-apache

RUN docker-php-ext-install pdo_mysql;

COPY src/index.php /var/www/html/
COPY src/checkbox /var/www/html/checkbox/