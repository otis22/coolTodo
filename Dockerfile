# Multi-stage build для оптимизации размера образа
# Stage 1: Composer dependencies
FROM composer:latest AS composer-stage
WORKDIR /app
COPY backend/composer.json backend/composer.lock* ./
# Отключаем скрипты, так как artisan еще не скопирован
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs --no-scripts

# Stage 2: Production PHP-FPM
FROM php:8.3-fpm

# Установка системных зависимостей (только необходимые для production)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Настройка рабочей директории
WORKDIR /var/www/project/backend

# Копирование зависимостей из composer stage
COPY --from=composer-stage /app/vendor ./vendor

# Копирование файлов приложения
COPY backend/ ./

# Установка прав
RUN chown -R www-data:www-data /var/www/project/backend \
    && chmod -R 755 storage \
    && chmod -R 755 bootstrap/cache

# Оптимизация Laravel (выполняется при сборке образа)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000

# Использование полного пути к php-fpm для стабильности
CMD ["/usr/local/sbin/php-fpm", "-F", "-O"]





