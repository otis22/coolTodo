# Multi-stage build для оптимизации размера образа
# Stage 1: Composer dependencies
FROM composer:latest AS composer-stage
WORKDIR /app
COPY backend/composer.json backend/composer.lock* ./
# Отключаем скрипты, так как artisan еще не скопирован
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative --no-interaction --prefer-dist --ignore-platform-reqs --no-scripts

# Stage 2: Production PHP-FPM
FROM php:8.3-fpm

# Установка системных зависимостей (только необходимые для production)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Настройка OPCache для production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.enable_cli=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.save_comments=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

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

# Примечание: Оптимизация Laravel (config:cache, route:cache, view:cache)
# должна выполняться при запуске контейнера с правильными переменными окружения,
# а не при сборке образа. Это можно сделать через entrypoint скрипт или команду запуска.

EXPOSE 9000

# Использование полного пути к php-fpm для стабильности
CMD ["/usr/local/sbin/php-fpm", "-F", "-O"]





