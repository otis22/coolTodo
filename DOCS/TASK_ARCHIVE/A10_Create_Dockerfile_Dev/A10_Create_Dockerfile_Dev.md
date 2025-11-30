# Task A10: Создать Dockerfile.dev для разработки

**Статус**: Completed ✅  
**Начало**: 2025-01-27  
**Приоритет**: High  
**Оценка**: 0.5 дня

## Описание

Создать Dockerfile.dev для разработки согласно требованиям изоляции инструментов разработки. Dockerfile.dev должен содержать PHP 8.3-cli, Composer и Xdebug для разработки.

## Критерии приемки

✅ Dockerfile.dev создан  
✅ Содержит PHP 8.3-cli  
✅ Содержит Composer  
✅ Содержит Xdebug 3.x (последняя версия)  
✅ Docker образ собирается

## Реализация

### Создан Dockerfile.dev

**Базовый образ**: `php:8.3-cli`

**Установленные компоненты**:
- ✅ Системные зависимости: git, curl, libpng-dev, libonig-dev, libxml2-dev, zip, unzip
- ✅ PHP расширения: pdo_mysql, mbstring, exif, pcntl, bcmath, gd
- ✅ Composer из официального образа
- ✅ Xdebug 3.x (последняя версия через `pecl install xdebug`)

**Особенности**:
- ✅ Рабочая директория: `/var/www/html`
- ✅ НЕ устанавливает зависимости на этапе сборки (будут устанавливаться через volume)
- ✅ НЕ выполняет оптимизацию Laravel (для разработки)

### Отличия от Dockerfile (production)

| Характеристика | Dockerfile (production) | Dockerfile.dev (development) |
|----------------|-------------------------|------------------------------|
| Базовый образ | php:8.3-fpm | php:8.3-cli |
| Xdebug версия | xdebug-3.3.0 (зафиксирована) | xdebug (последняя версия) |
| Установка зависимостей | Да (composer install) | Нет (через volume) |
| Оптимизация Laravel | Да (config:cache, route:cache, view:cache) | Нет |
| Пользователь | www-data | Будет настроен в A12 |
| CMD | php-fpm | Не указан (для разработки) |

## Структура файла

```dockerfile
FROM php:8.3-cli

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка Xdebug 3.x для отладки (последняя версия)
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Настройка рабочей директории
WORKDIR /var/www/html
```

## Результат

Dockerfile.dev создан и соответствует требованиям:
- Использует PHP 8.3-cli для разработки
- Содержит все необходимые инструменты (Composer, Xdebug)
- Оптимизирован для разработки (без установки зависимостей и оптимизации на этапе сборки)
- Готов к использованию в docker-compose.yml (будет настроено в задаче A12)

## Зависимости

- [x] A3: Настроить Docker окружение (Completed ✅)

## Связанные задачи

- A11: Создать Dockerfile.tools для инструментов анализа (следующая задача)
- A12: Настроить права доступа в Docker контейнерах (будет использовать Dockerfile.dev)

