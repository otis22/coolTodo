# Task A11: Создать Dockerfile.tools для инструментов анализа

**Статус**: Completed ✅  
**Начало**: 2025-01-27  
**Приоритет**: High  
**Оценка**: 0.5 дня

## Описание

Создать Dockerfile.tools для инструментов статического анализа кода (PHPStan и PHP-CS-Fixer). Dockerfile должен содержать минимальные зависимости и глобально установленные инструменты анализа.

## Критерии приемки

✅ Dockerfile.tools создан  
✅ Содержит PHPStan  
✅ Содержит PHP-CS-Fixer  
✅ Инструменты доступны глобально через `/usr/local/bin`  
✅ Docker образ собирается

## Реализация

### Создан Dockerfile.tools

**Базовый образ**: `php:8.3-cli`

**Установленные компоненты**:
- ✅ Минимальные системные зависимости: git, curl, unzip
- ✅ PHP расширение: pdo_mysql (только для PHPStan)
- ✅ Composer из официального образа
- ✅ PHPStan (глобально через Composer)
- ✅ PHP-CS-Fixer (глобально через Composer)

**Особенности**:
- ✅ Рабочая директория: `/var/www/html`
- ✅ Инструменты доступны глобально через `/usr/local/bin`
- ✅ Минимальный набор зависимостей (только необходимое для инструментов)

### Структура файла

```dockerfile
FROM php:8.3-cli

# Установка минимальных системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Глобальная установка PHPStan и PHP-CS-Fixer через Composer
RUN composer global require \
    phpstan/phpstan \
    friendsofphp/php-cs-fixer \
    --no-interaction --prefer-dist

# Добавление глобальных bin директорий Composer в PATH
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Создание символических ссылок для глобального доступа
RUN ln -s /root/.composer/vendor/bin/phpstan /usr/local/bin/phpstan && \
    ln -s /root/.composer/vendor/bin/php-cs-fixer /usr/local/bin/php-cs-fixer

# Настройка рабочей директории
WORKDIR /var/www/html
```

### Отличия от Dockerfile.dev

| Характеристика | Dockerfile.dev | Dockerfile.tools |
|----------------|----------------|------------------|
| Назначение | Разработка (PHP, Composer, Xdebug) | Инструменты анализа (PHPStan, PHP-CS-Fixer) |
| Системные зависимости | Полный набор (libpng-dev, libonig-dev, и т.д.) | Минимальный набор (git, curl, unzip) |
| PHP расширения | pdo_mysql, mbstring, exif, pcntl, bcmath, gd | Только pdo_mysql |
| Xdebug | Да | Нет |
| Инструменты | Composer | PHPStan, PHP-CS-Fixer (глобально) |

## Результат

Dockerfile.tools создан и соответствует требованиям:
- Содержит PHPStan и PHP-CS-Fixer
- Инструменты доступны глобально
- Минимальный набор зависимостей
- Готов к использованию в docker-compose.yml (будет настроено в задаче A12)

## Зависимости

- [x] A3: Настроить Docker окружение (Completed ✅)

## Связанные задачи

- A10: Создать Dockerfile.dev для разработки (Completed ✅)
- A12: Настроить права доступа в Docker контейнерах (будет использовать Dockerfile.tools)

