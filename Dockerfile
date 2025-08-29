FROM php:8.1-apache

RUN apt-get update \
        && apt-get install -y \
                bash \
                git \
                curl \
                unzip \
                zip \
                libpng-dev \
                libjpeg-dev \
                libfreetype6-dev \
                libonig-dev \
                libxml2-dev \
                libicu-dev \
                libzip-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
        && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

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


# Configure Apache to serve Laravel from /var/www/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
