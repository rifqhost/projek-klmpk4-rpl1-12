FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
COPY docker/start.sh /usr/local/bin/render-start

RUN chmod +x /usr/local/bin/render-start \
    && chown -R www-data:www-data /var/www/html/uploads \
    && a2enmod headers

EXPOSE 10000

CMD ["render-start"]
