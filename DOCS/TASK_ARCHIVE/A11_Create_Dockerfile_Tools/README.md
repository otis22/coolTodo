# Task A11: Создать Dockerfile.tools для инструментов анализа

## Summary

Создан Dockerfile.tools для инструментов статического анализа кода (PHPStan и PHP-CS-Fixer). Dockerfile содержит минимальные зависимости и глобально установленные инструменты анализа, доступные через `/usr/local/bin`.

## Source Requirements

- `DOCS/AI/Execution_Guide/04_TODO_Workplan.md` - Task A11
- `DOCS/AI/Execution_Guide/07_Development_Environment.md` - Техническая спецификация Dockerfile.tools

## Objectives

1. Создать Dockerfile.tools для инструментов анализа
2. Установить PHPStan глобально
3. Установить PHP-CS-Fixer глобально
4. Обеспечить глобальный доступ к инструментам через `/usr/local/bin`
5. Минимизировать зависимости (только необходимое)

## Dependencies

- A3: Настроить Docker окружение (Completed ✅)

## In-Scope / Out of Scope

**In-Scope**:
- Создание Dockerfile.tools
- Установка PHPStan и PHP-CS-Fixer глобально
- Настройка глобального доступа к инструментам
- Минимальные системные зависимости

**Out of Scope**:
- Обновление docker-compose.yml (будет в задаче A12)
- Настройка прав доступа (задача A12)
- Создание helper-скриптов (задача A13)

## Acceptance Criteria

✅ Dockerfile.tools создан  
✅ Содержит PHPStan  
✅ Содержит PHP-CS-Fixer  
✅ Инструменты доступны глобально через `/usr/local/bin`  
✅ Docker образ собирается

## Implementation Notes

### Создан Dockerfile.tools

**Базовый образ**: `php:8.3-cli`

**Установленные компоненты**:
- Минимальные системные зависимости: git, curl, unzip
- PHP расширение: pdo_mysql (только для PHPStan)
- Composer из официального образа
- PHPStan (глобально через `composer global require`)
- PHP-CS-Fixer (глобально через `composer global require`)

**Особенности**:
- Инструменты доступны глобально через `/usr/local/bin`
- Минимальный набор зависимостей (только необходимое)
- Рабочая директория: `/var/www/html`

### Структура Dockerfile.tools

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

### Использование инструментов

После сборки образа инструменты доступны глобально:
```bash
# PHPStan
phpstan analyse

# PHP-CS-Fixer
php-cs-fixer fix
```

## Lessons Learned

1. **Минимальные зависимости**: Для инструментов анализа нужен минимальный набор зависимостей, что ускоряет сборку образа.

2. **Глобальная установка**: Использование `composer global require` позволяет установить инструменты один раз и использовать их в любом проекте.

3. **Символические ссылки**: Создание символических ссылок в `/usr/local/bin` обеспечивает глобальный доступ к инструментам без необходимости указывать полный путь.

4. **Разделение ответственности**: Отдельный Dockerfile.tools позволяет изолировать инструменты анализа от окружения разработки, что упрощает поддержку и обновление.

## Immediate Next Steps

Следующая задача: **A12: Настроить права доступа в Docker контейнерах** (будет использовать Dockerfile.dev и Dockerfile.tools).

