FROM php:8.2-cli

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www

# Copiar proyecto
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Crear enlace simbólico
RUN php artisan storage:link || true

# Puerto de Cloud Run
EXPOSE 8080

# Iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]