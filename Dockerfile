# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies including Node.js
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Apache configuration
COPY docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copy project files
COPY . .

# Ensure Laravel storage and bootstrap/cache exist with correct permissions
RUN mkdir -p storage/framework/{cache,sessions,views,testing} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Optionally build frontend assets (uncomment if using Vite or similar)
# RUN npm ci && npm run build

# Set correct ownership for the application
RUN chown -R www-data:www-data /var/www/html

# Create an entrypoint script to fix permissions & cache paths on startup
RUN echo '#!/bin/bash' > /usr/local/bin/docker-entrypoint.sh \
    && echo 'set -e' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'mkdir -p storage/framework/{cache,sessions,views,testing} bootstrap/cache' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'chown -R www-data:www-data storage bootstrap/cache' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'chmod -R 775 storage bootstrap/cache' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'php artisan config:clear || true' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'php artisan cache:clear || true' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'exec apache2-foreground' >> /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 80
EXPOSE 80

# Start Apache via entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]

