FROM php:8.3-fpm-alpine

ENV TAR_OPTIONS="--no-same-owner --no-same-permissions"
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    git \
    curl \
    tar \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    mariadb-client \
    && docker-php-ext-install pdo_mysql intl mbstring zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN cp .env.example .env || true
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
