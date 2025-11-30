# CoolTodo

Веб-сервис для управления персональным списком задач (заметками, "todo"). 
Одностраничное веб-приложение (SPA), позволяющее пользователю создавать, просматривать, редактировать и удалять задачи. 
Все изменения немедленно сохраняются на сервере для синхронизации между устройствами.

## Technology Stack

**Backend**:
- PHP 8.3
- Laravel 11.x
- MySQL 8.0/8.4

**Frontend**:
- Vue 3.5
- Vite 7.2

**Development Tools**:
- Docker
- PHPUnit 11.x
- Laravel Dusk 8.x
- PHPStan 2.x (level 9)
- PHP-CS-Fixer 3.x
- Xdebug 3.x

## Getting Started

### Prerequisites

- Docker и Docker Compose
- Git

### Installation

1. Клонировать репозиторий:
```bash
git clone <repository-url>
cd coolTodo
```

2. Скопировать файл окружения:
```bash
cp .env.example .env
```

3. Запустить Docker контейнеры:
```bash
docker-compose up -d
```

4. Установить зависимости backend:
```bash
./dev composer install
```

5. Установить зависимости frontend:
```bash
cd frontend
npm install
```

6. Запустить миграции:
```bash
./dev artisan migrate
```

7. Собрать фронтенд:
```bash
cd frontend
npm run build
```

### Helper-скрипт `dev`

Для упрощения работы с Docker контейнерами используется helper-скрипт `./dev`:

```bash
./dev composer <command>    # Выполнить composer команду
./dev php <command>         # Выполнить php команду
./dev artisan <command>    # Выполнить artisan команду
./dev phpunit [args]       # Запустить PHPUnit тесты
./dev phpstan              # Запустить PHPStan анализ
./dev cs-fix [--dry-run]   # Запустить PHP-CS-Fixer
./dev shell                # Открыть bash shell в контейнере app
./dev tools-shell          # Открыть bash shell в контейнере tools
```

**Примечание**: Скрипт использует `docker compose run --rm`, поэтому контейнеры не должны быть постоянно запущены.

### Running the Project

**Development mode**:

Backend:
```bash
docker-compose up -d
./dev artisan serve
```

Frontend:
```bash
cd frontend
npm run dev
```

Приложение будет доступно по адресу: `http://localhost:5173`

**Production mode**:
```bash
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

### Running Tests

**Unit тесты**:
```bash
./dev phpunit --testsuite=Unit
```

**Feature тесты**:
```bash
./dev phpunit --testsuite=Feature
```

**E2E тесты (Laravel Dusk)**:
```bash
./dev artisan dusk
```

**Все тесты**:
```bash
./dev phpunit
```

**С покрытием кода**:
```bash
./dev phpunit --coverage-text
```

### Code Quality

**PHPStan**:
```bash
./dev phpstan
```

**PHP-CS-Fixer**:
```bash
./dev cs-fix              # Исправить код
./dev cs-fix --dry-run    # Проверить без исправления
```

## Project Structure

```
coolTodo/
├── backend/                    # Backend приложение (Laravel)
│   ├── src/
│   │   ├── Domain/            # Бизнес-логика
│   │   │   ├── UseCases/      # Use Cases
│   │   │   └── Models/        # Domain Models
│   │   └── Infrastructure/    # Инфраструктурный слой
│   │       ├── Repositories/  # Репозитории
│   │       └── Http/          # Контроллеры, Requests
│   ├── app/                   # Laravel app
│   ├── database/              # Миграции, seeders
│   ├── routes/                # Маршруты
│   └── tests/                 # Тесты
├── frontend/                   # Frontend приложение (Vue)
│   ├── src/
│   │   ├── components/        # Vue компоненты
│   │   │   ├── TodoList.vue
│   │   │   └── TodoItem.vue
│   │   └── services/          # API сервисы
│   │       └── api.js
│   └── package.json
├── DOCS/                       # Документация
│   ├── RULES/                 # Правила для ИИ-агентов
│   ├── AI/                    # Руководство по выполнению
│   └── INPROGRESS/            # Текущие задачи
└── docker-compose.yml          # Docker конфигурация
```

## Documentation

Подробная документация находится в директории `DOCS/`:

- `DOCS/DEVELOPMENT.md` - Руководство по разработке
- `DOCS/RULES/` - Правила для ИИ-агентов по разработке
- `DOCS/AI/Execution_Guide/` - Руководство по выполнению проекта
- `DOCS/INPROGRESS/` - Текущие задачи в работе

## Contributing

1. Создать ветку от `main`
2. Реализовать изменения (следуя TDD)
3. Убедиться, что все тесты проходят
4. Проверить код через PHPStan и PHP-CS-Fixer
5. Создать Pull Request

## License

[Указать лицензию]
