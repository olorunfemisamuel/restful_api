#Stage 1 - Build Composer dependencies
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# Stage 2 - Application image
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    nginx \
    supervisor \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    g++ \
    make \
    zlib-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    postgresql-dev \
    libxml2-dev \
    curl \
    bison \
    re2c \
    php82-tokenizer \ 
     linux-headers \
    ;

#Install PHP extensions
# RUN docker-php-ext-install \
#     pdo_pgsql \
#     zip \
#      tokenizer \
#     mbstring \
#     bcmath \
#     exif \
#     pcntl \
#     sockets \
#     xml

RUN set -ex; \
    docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql; \
    docker-php-ext-install \
        pdo_pgsql \
        pgsql \
        zip \
        mbstring \
        bcmath \
        exif \
        pcntl \
        sockets \
        xml;

    # Configure working dir
WORKDIR /var/www/html

# Copy app files
COPY . .

# Copy composer deps from previous stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx and Supervisor config
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Expose port for Render
EXPOSE 8000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

