# Task A10: Создать Dockerfile.dev для разработки

## Summary

Создан Dockerfile.dev для разработки согласно требованиям изоляции инструментов разработки. Dockerfile содержит PHP 8.3-cli, Composer и Xdebug 3.x для разработки. Оптимизирован для разработки: не устанавливает зависимости на этапе сборки и не выполняет оптимизацию Laravel.

## Source Requirements

- `DOCS/AI/Execution_Guide/04_TODO_Workplan.md` - Task A10
- `DOCS/AI/Execution_Guide/07_Development_Environment.md` - Техническая спецификация Dockerfile.dev

## Objectives

1. Создать Dockerfile.dev для разработки
2. Использовать PHP 8.3-cli (не fpm)
3. Установить Composer
4. Установить Xdebug 3.x (последняя версия)
5. Оптимизировать для разработки (без установки зависимостей на этапе сборки)

## Dependencies

- A3: Настроить Docker окружение (Completed ✅)

## In-Scope / Out of Scope

**In-Scope**:
- Создание Dockerfile.dev
- Установка PHP 8.3-cli и расширений
- Установка Composer
- Установка Xdebug 3.x
- Настройка рабочей директории

**Out of Scope**:
- Обновление docker-compose.yml (будет в задаче A12)
- Настройка прав доступа (задача A12)
- Создание Dockerfile.tools (задача A11)

## Acceptance Criteria

✅ Dockerfile.dev создан  
✅ Содержит PHP 8.3-cli  
✅ Содержит Composer  
✅ Содержит Xdebug 3.x (последняя версия)  
✅ Docker образ собирается

## Implementation Notes

### Создан Dockerfile.dev

**Базовый образ**: `php:8.3-cli`

**Установленные компоненты**:
- Системные зависимости: git, curl, libpng-dev, libonig-dev, libxml2-dev, zip, unzip
- PHP расширения: pdo_mysql, mbstring, exif, pcntl, bcmath, gd
- Composer из официального образа (`composer:latest`)
- Xdebug 3.x (последняя версия через `pecl install xdebug`)

**Особенности для разработки**:
- НЕ устанавливает зависимости на этапе сборки (будут устанавливаться через volume)
- НЕ выполняет оптимизацию Laravel (config:cache, route:cache, view:cache)
- Рабочая директория: `/var/www/html`

### Отличия от Dockerfile (production)

| Характеристика | Dockerfile (production) | Dockerfile.dev (development) |
|----------------|-------------------------|------------------------------|
| Базовый образ | php:8.3-fpm | php:8.3-cli |
| Xdebug версия | xdebug-3.3.0 (зафиксирована) | xdebug (последняя версия) |
| Установка зависимостей | Да (composer install) | Нет (через volume) |
| Оптимизация Laravel | Да | Нет |
| Пользователь | www-data | Будет настроен в A12 |
| CMD | php-fpm | Не указан |

### Структура Dockerfile.dev

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

## Lessons Learned

1. **PHP CLI vs FPM**: Для разработки используется php:8.3-cli, так как не нужен веб-сервер внутри контейнера (используется nginx отдельно).

2. **Xdebug версия**: Использование `pecl install xdebug` без указания версии устанавливает последнюю стабильную версию 3.x, что лучше для разработки.

3. **Оптимизация для разработки**: Не устанавливать зависимости и не выполнять оптимизацию на этапе сборки позволяет быстрее пересобирать образ и использовать актуальные версии зависимостей через volume.

4. **Разделение production и development**: Отдельный Dockerfile.dev позволяет иметь разные конфигурации для production и development без компромиссов.

## Immediate Next Steps

Следующие задачи:
- **A11**: Создать Dockerfile.tools для инструментов анализа
- **A12**: Настроить права доступа в Docker контейнерах (будет использовать Dockerfile.dev)

