FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
        bash \
        git \
        curl \
        unzip \
        zip \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        oniguruma-dev \
        libxml2-dev \
        icu-dev \
        libzip-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
        && rm -rf /var/cache/apk/*

# Set working directory
WORKDIR /var/www

# Copy application code
COPY . /var/www

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Install key
RUN php artisan key:generate

# Optimize
RUN php artisan optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 for php-fpm
EXPOSE 9000

CMD ["php-fpm"]
