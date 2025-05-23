# Usa una imagen base con PHP 8.2 y extensiones necesarias
FROM php:8.2-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libpq-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia los archivos del proyecto
COPY . /var/www

WORKDIR /var/www

# Permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Genera clave de la app y cachea configuración
RUN php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache

# Exponer el puerto por defecto
EXPOSE 9000

CMD ["php-fpm"]
