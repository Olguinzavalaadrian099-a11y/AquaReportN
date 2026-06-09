FROM php:8.2-apache
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql
COPY . /var/www/html/
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/uploads