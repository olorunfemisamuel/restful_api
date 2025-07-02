# Use official PHP 8.1 FPM image
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    && docker-php-ext-install \
    pdo_postgresql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader


# Cache config and routes
RUN php artisan config:cache && \
    php artisan route:cache

# Expose the port Laravel will run on
EXPOSE 8000

# Start Laravel using artisan
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
