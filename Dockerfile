FROM dunglas/frankenphp:latest-php8.2-alpine

RUN apk add --no-cache \
    bash \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    libexif-dev \
    zlib-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    exif \
    intl \
    zip \
    pcntl \
    bcmath \
    pdo_mysql

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

HEALTHCHECK NONE

EXPOSE 80

CMD ["frankenphp", "php-server", "-r", "public/"]