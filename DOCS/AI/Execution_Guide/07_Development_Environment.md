# Development Environment: Изоляция инструментов разработки

## Назначение

Этот документ описывает требования и техническую спецификацию для полной изоляции инструментов разработки в Docker-контейнерах, исключая необходимость установки PHP, Composer, линтеров и других инструментов на локальной машине.

## Функциональные требования

### FR-DEV-1: Изоляция инструментов разработки

**Описание**: Все инструменты разработки должны работать только в Docker-контейнерах.

**Инструменты, которые должны быть изолированы**:
- PHP 8.3 и расширения
- Composer
- PHPUnit
- PHPStan
- PHP-CS-Fixer
- Xdebug
- Другие инструменты статического анализа

**Критерии приемки**:
- ✅ На локальной машине не требуется установка PHP, Composer, линтеров
- ✅ Все команды выполняются через `docker-compose exec` или helper-скрипты
- ✅ CI/CD использует те же контейнеры

### FR-DEV-2: Структура Docker-контейнеров

**Описание**: Выделить отдельные контейнеры для инструментов разработки.

**Структура контейнеров**:
- **Контейнер `app`**: PHP 8.3-cli, Composer, PHPUnit, Laravel, Xdebug
- **Контейнер `tools`**: PHPStan, PHP-CS-Fixer
- **Контейнер `nginx`**: без изменений
- **Контейнер `mysql`**: без изменений
- **Контейнер `node`**: без изменений

**Критерии приемки**:
- ✅ Контейнер `app` содержит PHP 8.3-cli, Composer, PHPUnit
- ✅ Контейнер `tools` содержит PHPStan, PHP-CS-Fixer
- ✅ Все контейнеры доступны через `docker-compose exec`

### FR-DEV-3: Синхронизация файлов

**Описание**: Изменения в контейнере должны быть доступны в файловой системе хоста.

**Требования**:
- Использовать Docker volumes для синхронизации
- Изменения файлов в контейнере должны быть видны на хосте
- Изменения на хосте должны быть видны в контейнере
- Поддержка hot-reload для разработки

**Критерии приемки**:
- ✅ Изменения в `backend/src/` видны на хосте и в контейнере
- ✅ Изменения на хосте видны в контейнере без перезапуска
- ✅ Volumes настроены в `docker-compose.yml`

### FR-DEV-4: Права доступа к файлам

**Описание**: Файлы создаются от имени пользователя системы хоста.

**Требования**:
- Файлы, созданные в контейнере, должны принадлежать пользователю хоста
- UID/GID пользователя хоста должны совпадать с UID/GID в контейнере
- Избежать проблем с правами доступа при работе с файлами

**Критерии приемки**:
- ✅ Файлы создаются с правами пользователя хоста (не root)
- ✅ Нет проблем с правами доступа при редактировании файлов
- ✅ Git видит изменения, созданные в контейнере
- ✅ IDE может редактировать файлы без проблем с правами

## Технические детали реализации

### Dockerfile.dev

**Базовый образ**: `php:8.3-cli`

**Установка**:
- Системные зависимости: git, curl, libpng-dev, libonig-dev, libxml2-dev, zip, unzip
- PHP расширения: pdo_mysql, mbstring, exif, pcntl, bcmath, gd
- Composer из официального образа
- Xdebug 3.x для отладки

**Особенности**:
- Рабочая директория: `/var/www/html`
- НЕ устанавливать зависимости на этапе сборки (будут устанавливаться через volume)
- НЕ выполнять оптимизацию Laravel (для разработки)

### Dockerfile.tools

**Базовый образ**: `php:8.3-cli`

**Установка**:
- Минимальные системные зависимости: git, curl, unzip
- PHP расширения: pdo_mysql (только для PHPStan)
- Composer
- Глобальная установка PHPStan и PHP-CS-Fixer через Composer

**Особенности**:
- Рабочая директория: `/var/www/html`
- Инструменты доступны глобально через `/usr/local/bin`

### docker-compose.yml изменения

**Сервис `app`**:
```yaml
app:
  build:
    context: .
    dockerfile: Dockerfile.dev
  user: "${UID:-1000}:${GID:-1000}"
  volumes:
    - ./backend:/var/www/html:cached
    - ./backend/vendor:/var/www/html/vendor
  environment:
    - COMPOSER_HOME=/var/www/html/.composer
```

**Новый сервис `tools`**:
```yaml
tools:
  build:
    context: .
    dockerfile: Dockerfile.tools
  user: "${UID:-1000}:${GID:-1000}"
  volumes:
    - ./backend:/var/www/html:cached
    - ./backend/vendor:/var/www/html/vendor
  working_dir: /var/www/html
```

### Helper-скрипт `dev`

**Расположение**: `./dev` (исполняемый скрипт в корне проекта)

**Команды**:
- `./dev composer <command>` → `docker-compose exec app composer <command>`
- `./dev php <command>` → `docker-compose exec app php <command>`
- `./dev artisan <command>` → `docker-compose exec app php artisan <command>`
- `./dev phpunit [args]` → `docker-compose exec app vendor/bin/phpunit [args]`
- `./dev phpstan` → `docker-compose exec tools vendor/bin/phpstan analyse`
- `./dev cs-fix [--dry-run]` → `docker-compose exec tools vendor/bin/php-cs-fixer fix [--dry-run]`
- `./dev shell` → `docker-compose exec app bash`
- `./dev tools-shell` → `docker-compose exec tools bash`

## Нефункциональные требования

### NFR-DEV-1: Производительность

**Требование**: Синхронизация файлов не должна замедлять работу.

**Реализация**:
- Использовать `:cached` для volumes на macOS
- Использовать `:delegated` для volumes на Linux (если необходимо)
- Минимизировать количество синхронизируемых директорий

### NFR-DEV-2: Совместимость

**Требование**: Решение должно работать на разных ОС.

**Поддерживаемые платформы**:
- Linux (Ubuntu, Debian)
- macOS (с Docker Desktop)
- Windows (с Docker Desktop, WSL2)

### NFR-DEV-3: Безопасность

**Требование**: Контейнеры не должны работать от root.

**Реализация**:
- Использовать пользователя хоста (UID/GID)
- Минимизировать привилегии контейнеров
- Избежать проблем с правами доступа

## Переменные окружения

### .env.example

```env
# Docker User/Group IDs для правильных прав доступа
# На Linux/macOS: используйте `id -u` и `id -g` для получения ваших UID/GID
UID=1000
GID=1000
```

## Примеры использования

### До реализации

```bash
# Требуется установка на локальной машине
composer install
php artisan migrate
vendor/bin/phpunit
vendor/bin/phpstan analyse
```

### После реализации

```bash
# Все через Docker
docker-compose exec app composer install
docker-compose exec app php artisan migrate
docker-compose exec app vendor/bin/phpunit
docker-compose exec tools vendor/bin/phpstan analyse

# Или через helper-скрипты
./dev composer install
./dev artisan migrate
./dev phpunit
./dev phpstan
```

## Критерии приемки (общие)

✅ На локальной машине не требуется установка PHP, Composer, линтеров  
✅ Все команды выполняются через Docker  
✅ Файлы создаются с правами пользователя хоста  
✅ Изменения синхронизируются между контейнером и хостом  
✅ Git корректно видит изменения, созданные в контейнере  
✅ IDE может редактировать файлы без проблем  
✅ CI/CD использует те же контейнеры  
✅ Документация обновлена с инструкциями по использованию  

## Зависимости

- Задача A3: Настроить Docker окружение (Completed ✅)
- Обновление `docker-compose.yml`
- Создание helper-скриптов (опционально)
- Обновление документации (README.md, DOCS/DEVELOPMENT.md)

