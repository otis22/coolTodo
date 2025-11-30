# Development Guide: CoolTodo

Руководство по разработке для проекта CoolTodo.

## Содержание

- [Требования](#требования)
- [Настройка окружения](#настройка-окружения)
- [Helper-скрипт `dev`](#helper-скрипт-dev)
- [Работа с Docker](#работа-с-docker)
- [Разработка](#разработка)
- [Тестирование](#тестирование)
- [Code Quality](#code-quality)
- [Отладка](#отладка)
- [Структура проекта](#структура-проекта)

## Требования

Для разработки требуется только:

- **Docker** и **Docker Compose** (версия 2.0+)
- **Git**
- **Текстовый редактор** или IDE

**Не требуется** установка PHP, Composer, Node.js или других инструментов на локальной машине - все работает в Docker контейнерах.

## Настройка окружения

### 1. Клонирование репозитория

```bash
git clone <repository-url>
cd coolTodo
```

### 2. Настройка переменных окружения

```bash
cp backend/.env.example backend/.env
```

Отредактируйте `backend/.env` при необходимости (обычно настройки по умолчанию подходят для разработки).

### 3. Запуск Docker контейнеров

```bash
docker-compose up -d
```

Это запустит следующие сервисы:
- `app` - PHP 8.3 CLI с Composer, PHPUnit, Xdebug
- `tools` - PHPStan, PHP-CS-Fixer
- `nginx` - веб-сервер (порт 8080)
- `mysql` - база данных (порт 3306)
- `node` - Node.js для фронтенда

### 4. Установка зависимостей

**Backend**:
```bash
./dev composer install
```

**Frontend**:
```bash
cd frontend
npm install
```

### 5. Настройка базы данных

```bash
./dev artisan migrate
```

## Helper-скрипт `dev`

Для упрощения работы с Docker контейнерами используется helper-скрипт `./dev`.

### Доступные команды

#### Backend команды

```bash
./dev composer <command>    # Выполнить composer команду
./dev php <command>         # Выполнить php команду
./dev artisan <command>     # Выполнить artisan команду
./dev phpunit [args]        # Запустить PHPUnit тесты
```

#### Code Quality команды

```bash
./dev phpstan              # Запустить PHPStan анализ
./dev cs-fix [--dry-run]   # Запустить PHP-CS-Fixer
```

#### Утилиты

```bash
./dev shell                # Открыть bash shell в контейнере app
./dev tools-shell          # Открыть bash shell в контейнере tools
```

### Примеры использования

```bash
# Установить зависимости
./dev composer install

# Обновить зависимости
./dev composer update

# Запустить миграции
./dev artisan migrate

# Создать контроллер
./dev artisan make:controller TodoController

# Запустить тесты
./dev phpunit

# Проверить код
./dev phpstan
./dev cs-fix --dry-run

# Исправить стиль кода
./dev cs-fix
```

### Как это работает

Скрипт `dev` использует `docker compose run --rm`, что означает:
- Контейнеры не должны быть постоянно запущены
- Каждая команда запускает временный контейнер
- Контейнер автоматически удаляется после выполнения команды

Это позволяет работать без необходимости поддерживать постоянно запущенные контейнеры `app` и `tools`.

## Работа с Docker

### Запуск контейнеров

```bash
# Запустить все сервисы
docker-compose up -d

# Запустить только определенные сервисы
docker-compose up -d mysql nginx

# Просмотр логов
docker-compose logs -f app
```

### Остановка контейнеров

```bash
# Остановить все сервисы
docker-compose stop

# Остановить и удалить контейнеры
docker-compose down
```

### Пересборка образов

```bash
# Пересобрать все образы
docker-compose build

# Пересобрать конкретный сервис
docker-compose build app
```

### Права доступа

Контейнеры `app` и `tools` работают от имени пользователя хоста (UID/GID), что обеспечивает правильные права доступа к файлам. Переменные `UID` и `GID` можно задать в `.env` файле или они будут использованы по умолчанию (1000:1000).

## Разработка

### Структура проекта

Проект следует принципам Clean Architecture:

```
backend/
├── src/
│   ├── Domain/              # Бизнес-логика (независима от фреймворка)
│   │   ├── Models/          # Domain модели
│   │   ├── Repositories/    # Интерфейсы репозиториев
│   │   └── UseCases/        # Use Cases (бизнес-логика)
│   └── Infrastructure/      # Инфраструктурный слой
│       ├── Repositories/    # Реализации репозиториев
│       └── Http/            # Контроллеры, Requests
├── routes/                   # Маршруты API
├── database/                 # Миграции, seeders
└── tests/                    # Тесты
```

### Создание новых компонентов

**Use Case**:
```bash
# Создать файл вручную в backend/src/Domain/UseCases/
# Пример: CreateTodoUseCase.php
```

**Repository**:
```bash
# 1. Создать интерфейс в backend/src/Domain/Repositories/
# 2. Создать реализацию в backend/src/Infrastructure/Repositories/
```

**Controller**:
```bash
./dev artisan make:controller Infrastructure/Http/Controllers/TodoController
```

**Model**:
```bash
# Создать файл вручную в backend/src/Domain/Models/
# Пример: Task.php
```

### Работа с базой данных

**Миграции**:
```bash
# Создать миграцию
./dev artisan make:migration create_todos_table

# Запустить миграции
./dev artisan migrate

# Откатить последнюю миграцию
./dev artisan migrate:rollback
```

**Seeders**:
```bash
# Создать seeder
./dev artisan make:seeder TodoSeeder

# Запустить seeders
./dev artisan db:seed
```

## Тестирование

### PHPUnit

**Все тесты**:
```bash
./dev phpunit
```

**Конкретный тест**:
```bash
./dev phpunit tests/Unit/Domain/Models/TaskTest.php
```

**С фильтром**:
```bash
./dev phpunit --filter testCreateTodo
```

**С покрытием кода**:
```bash
./dev phpunit --coverage-text
```

### Структура тестов

```
tests/
├── Unit/                    # Unit тесты
│   └── Domain/              # Тесты Domain слоя
└── Feature/                 # Feature тесты
    └── Api/                 # Тесты API
```

### Написание тестов

**Unit тест**:
```php
<?php

namespace Tests\Unit\Domain\Models;

use Tests\TestCase;
use App\Domain\Models\Task;

class TaskTest extends TestCase
{
    public function test_can_create_task(): void
    {
        $task = new Task('Test task');
        $this->assertEquals('Test task', $task->getTitle());
    }
}
```

**Feature тест**:
```php
<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class TodoApiTest extends TestCase
{
    public function test_can_create_todo(): void
    {
        $response = $this->postJson('/api/todos', [
            'title' => 'Test todo'
        ]);
        
        $response->assertStatus(201);
    }
}
```

## Code Quality

### PHPStan

PHPStan настроен на уровень 9 (максимальный).

**Запуск анализа**:
```bash
./dev phpstan
```

**Конфигурация**: `phpstan.neon`

### PHP-CS-Fixer

PHP-CS-Fixer настроен на стандарт PSR-12.

**Проверка кода**:
```bash
./dev cs-fix --dry-run
```

**Исправление кода**:
```bash
./dev cs-fix
```

**Конфигурация**: `.php-cs-fixer.php`

### Pre-commit проверки

Перед коммитом рекомендуется:

1. Запустить тесты:
```bash
./dev phpunit
```

2. Проверить код:
```bash
./dev phpstan
./dev cs-fix --dry-run
```

3. Исправить стиль кода:
```bash
./dev cs-fix
```

## Отладка

### Xdebug

Xdebug 3.x настроен в контейнере `app`. Для отладки:

1. Настройте IDE для подключения к Xdebug (порт 9003)
2. Установите breakpoints
3. Запустите приложение или тесты
4. Xdebug автоматически подключится к IDE

### Логи

**Просмотр логов Laravel**:
```bash
tail -f backend/storage/logs/laravel.log
```

**Просмотр логов контейнера**:
```bash
docker-compose logs -f app
```

### Интерактивная отладка

**Открыть shell в контейнере app**:
```bash
./dev shell
```

**Открыть shell в контейнере tools**:
```bash
./dev tools-shell
```

## Структура проекта

```
coolTodo/
├── backend/                 # Backend приложение (Laravel)
│   ├── src/                 # Исходный код
│   │   ├── Domain/          # Domain слой (бизнес-логика)
│   │   └── Infrastructure/  # Infrastructure слой
│   ├── routes/              # API маршруты
│   ├── database/            # Миграции, seeders
│   └── tests/               # Тесты
├── frontend/                 # Frontend приложение (Vue)
│   ├── src/                 # Исходный код
│   │   ├── components/      # Vue компоненты
│   │   └── services/        # API сервисы
│   └── package.json
├── DOCS/                     # Документация
│   ├── RULES/               # Правила для ИИ-агентов
│   ├── AI/                  # Руководство по выполнению
│   ├── INPROGRESS/          # Текущие задачи
│   └── TASK_ARCHIVE/        # Архив выполненных задач
├── docker/                   # Docker конфигурация
├── Dockerfile                # Production образ
├── Dockerfile.dev            # Development образ
├── Dockerfile.tools          # Tools образ
├── docker-compose.yml        # Docker Compose конфигурация
├── dev                       # Helper-скрипт
└── README.md                 # Основная документация
```

## Полезные команды

### Composer

```bash
./dev composer install          # Установить зависимости
./dev composer update           # Обновить зависимости
./dev composer require <package> # Добавить пакет
./dev composer remove <package> # Удалить пакет
./dev composer dump-autoload    # Пересоздать autoload
```

### Artisan

```bash
./dev artisan list              # Список всех команд
./dev artisan route:list       # Список маршрутов
./dev artisan config:clear      # Очистить кэш конфигурации
./dev artisan cache:clear       # Очистить кэш
./dev artisan view:clear        # Очистить кэш представлений
```

### Git

```bash
# Перед коммитом
./dev phpunit                   # Запустить тесты
./dev phpstan                   # Проверить код
./dev cs-fix                    # Исправить стиль кода
git add .
git commit -m "Описание изменений"
```

## Troubleshooting

### Проблемы с правами доступа

Если возникают проблемы с правами доступа к файлам:

1. Проверьте переменные `UID` и `GID` в `.env`:
```env
UID=1000
GID=1000
```

2. Пересоберите контейнеры:
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Проблемы с зависимостями

Если `composer install` не работает:

1. Убедитесь, что `composer.json` находится в `backend/`
2. Проверьте права доступа к директории `backend/vendor`
3. Попробуйте удалить `composer.lock` и переустановить:
```bash
rm backend/composer.lock
./dev composer install
```

### Проблемы с базой данных

Если миграции не работают:

1. Проверьте настройки БД в `backend/.env`
2. Убедитесь, что контейнер MySQL запущен:
```bash
docker-compose ps mysql
```

3. Попробуйте пересоздать базу данных:
```bash
./dev artisan migrate:fresh
```

## Дополнительные ресурсы

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/)
- [Docker Documentation](https://docs.docker.com/)
- [PHPStan Documentation](https://phpstan.org/)
- [PHP-CS-Fixer Documentation](https://cs.symfony.com/)

## Контакты

Для вопросов и предложений создавайте Issues в репозитории проекта.

