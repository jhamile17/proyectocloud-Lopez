FROM php:8.2-cli

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www

# COPIAR EL PROYECTO (ESTO ES LO CLAVE)
COPY . .

# Instalar dependencias Laravel
RUN composer install --no-dev --optimize-autoloader

# Permisos Laravel
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
