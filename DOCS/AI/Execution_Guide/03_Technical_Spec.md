# Technical Specification: CoolTodo

## Architecture

### Общая архитектура

Проект следует принципам **Clean Architecture** с четким разделением на слои:

```
┌─────────────────────────────────────┐
│         Frontend (Vue 3.5)          │
│  ┌──────────┐  ┌──────────┐        │
│  │TodoList  │  │TodoItem  │        │
│  └────┬─────┘  └────┬─────┘        │
│       │            │               │
│       └─────┬──────┘               │
│             │                      │
│       ┌─────▼─────┐                │
│       │ API Service│                │
│       └─────┬─────┘                │
└─────────────┼──────────────────────┘
              │ HTTP/REST
┌─────────────▼──────────────────────┐
│      Backend (Laravel 11.x)        │
│  ┌──────────────────────────────┐ │
│  │  Infrastructure Layer         │ │
│  │  ┌──────────┐  ┌──────────┐  │ │
│  │  │Controller│  │Repository│  │ │
│  │  └────┬─────┘  └────┬─────┘  │ │
│  └───────┼─────────────┼────────┘ │
│          │             │           │
│  ┌───────▼─────────────▼────────┐ │
│  │      Domain Layer            │ │
│  │  ┌──────────┐  ┌──────────┐ │ │
│  │  │ Use Case │  │  Model   │ │ │
│  │  └──────────┘  └──────────┘ │ │
│  └──────────────────────────────┘ │
│          │                        │
│  ┌───────▼──────────────────────┐ │
│  │      Database (MySQL)         │ │
│  └──────────────────────────────┘ │
└────────────────────────────────────┘
```

### Domain Layer (Бизнес-логика)

**Расположение**: `backend/src/Domain/`

**Компоненты**:
- **UseCases**: Бизнес-логика для управления задачами
  - `CreateTodoUseCase`
  - `UpdateTodoUseCase`
  - `DeleteTodoUseCase`
  - `ToggleTodoStatusUseCase`
  - `GetTodosUseCase`
  - `DeleteCompletedTodosUseCase`

- **Models**: Сущности домена
  - `Task` (Domain Model)
  - `User` (Domain Model, для будущего расширения)

- **Value Objects**: Неизменяемые объекты-значения
  - `TaskStatus` (active|completed)

**Принципы**:
- Не зависит от Laravel или внешних сервисов
- Чистая бизнес-логика без инфраструктурных деталей
- Использует интерфейсы для доступа к данным

### Infrastructure Layer

**Расположение**: `backend/src/Infrastructure/`

**Компоненты**:
- **Repositories**: Реализация доступа к данным
  - `TodoRepository` (реализует `TodoRepositoryInterface` из Domain)

- **Http/Controllers**: API контроллеры
  - `TodoController` (RESTful endpoints)

- **Http/Requests**: Валидация запросов
  - `CreateTodoRequest`
  - `UpdateTodoRequest`

**Принципы**:
- Реализует интерфейсы из Domain Layer
- Использует возможности Laravel (Eloquent, Validation)
- Изолирован от бизнес-логики

### Frontend Layer

**Расположение**: `frontend/src/`

**Компоненты**:
- **components/TodoList**: Компонент списка задач
  - Отображает список задач
  - Управляет фильтрацией
  - Показывает счетчик активных задач

- **components/TodoItem**: Компонент отдельной задачи
  - Отображает одну задачу
  - Обрабатывает редактирование (double-click)
  - Обрабатывает изменение статуса (чекбокс)
  - Обрабатывает удаление (кнопка ×)

- **services/api**: Сервис для работы с backend API
  - `TodoApiService` (методы для CRUD операций)

**Принципы**:
- Композиция компонентов (Vue 3 Composition API)
- Реактивность через Vue reactivity system
- Оптимистичные обновления UI

## Data Contracts

### API Endpoints

#### GET /api/todos
Получить список всех задач.

**Response**:
```json
[
  {
    "id": 1,
    "title": "Задача 1",
    "status": "active",
    "created_at": "2025-11-29T10:00:00Z",
    "updated_at": "2025-11-29T10:00:00Z"
  }
]
```

#### POST /api/todos
Создать новую задачу.

**Request**:
```json
{
  "title": "Новая задача"
}
```

**Response**: `201 Created`
```json
{
  "id": 1,
  "title": "Новая задача",
  "status": "active",
  "created_at": "2025-11-29T10:00:00Z",
  "updated_at": "2025-11-29T10:00:00Z"
}
```

#### PUT /api/todos/{id}
Обновить задачу.

**Request**:
```json
{
  "title": "Обновленный текст"
}
```

**Response**: `200 OK`
```json
{
  "id": 1,
  "title": "Обновленный текст",
  "status": "active",
  "created_at": "2025-11-29T10:00:00Z",
  "updated_at": "2025-11-29T10:05:00Z"
}
```

#### PATCH /api/todos/{id}/status
Изменить статус задачи.

**Request**:
```json
{
  "status": "completed"
}
```

**Response**: `200 OK`
```json
{
  "id": 1,
  "title": "Задача",
  "status": "completed",
  "created_at": "2025-11-29T10:00:00Z",
  "updated_at": "2025-11-29T10:10:00Z"
}
```

#### DELETE /api/todos/{id}
Удалить задачу.

**Response**: `204 No Content`

#### DELETE /api/todos/completed
Удалить все выполненные задачи.

**Response**: `200 OK`
```json
{
  "deleted": 3
}
```

### Database Schema

#### Таблица `todos`

```sql
CREATE TABLE todos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    status ENUM('active', 'completed') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Component Responsibilities

### Backend Components

#### Use Cases

**CreateTodoUseCase**:
- Валидирует входные данные
- Создает новую задачу со статусом "active"
- Сохраняет через репозиторий
- Возвращает созданную задачу

**UpdateTodoUseCase**:
- Находит задачу по ID
- Обновляет title
- Сохраняет изменения
- Возвращает обновленную задачу

**ToggleTodoStatusUseCase**:
- Находит задачу по ID
- Меняет статус (active ↔ completed)
- Сохраняет изменения
- Возвращает обновленную задачу

**DeleteTodoUseCase**:
- Находит задачу по ID
- Удаляет задачу
- Возвращает успешный результат

**GetTodosUseCase**:
- Получает все задачи через репозиторий
- Возвращает список задач

**DeleteCompletedTodosUseCase**:
- Находит все задачи со статусом "completed"
- Удаляет их
- Возвращает количество удаленных

#### Repository

**TodoRepository**:
- `findAll(): array` - получить все задачи
- `findById(int $id): ?Task` - найти задачу по ID
- `save(Task $task): Task` - сохранить задачу
- `delete(Task $task): void` - удалить задачу
- `deleteCompleted(): int` - удалить все выполненные задачи

#### Controller

**TodoController**:
- `index(): JsonResponse` - GET /api/todos
- `store(CreateTodoRequest $request): JsonResponse` - POST /api/todos
- `update(UpdateTodoRequest $request, int $id): JsonResponse` - PUT /api/todos/{id}
- `updateStatus(Request $request, int $id): JsonResponse` - PATCH /api/todos/{id}/status
- `destroy(int $id): JsonResponse` - DELETE /api/todos/{id}
- `destroyCompleted(): JsonResponse` - DELETE /api/todos/completed

### Frontend Components

#### TodoList Component

**Ответственность**:
- Отображение списка задач
- Управление фильтрацией (All/Active/Completed)
- Отображение счетчика активных задач
- Кнопка "Clear completed"

**Props**: Нет

**Events**: Нет

**State**:
- `todos: Todo[]` - список всех задач
- `filter: 'all' | 'active' | 'completed'` - текущий фильтр

#### TodoItem Component

**Ответственность**:
- Отображение одной задачи
- Редактирование текста (double-click)
- Изменение статуса (чекбокс)
- Удаление задачи (кнопка ×)

**Props**:
- `todo: Todo` - объект задачи

**Events**:
- `@update` - обновление задачи
- `@delete` - удаление задачи

**State**:
- `isEditing: boolean` - режим редактирования
- `editTitle: string` - текст при редактировании

#### TodoApiService

**Методы**:
- `getTodos(): Promise<Todo[]>` - получить все задачи
- `createTodo(title: string): Promise<Todo>` - создать задачу
- `updateTodo(id: number, title: string): Promise<Todo>` - обновить задачу
- `toggleStatus(id: number, status: string): Promise<Todo>` - изменить статус
- `deleteTodo(id: number): Promise<void>` - удалить задачу
- `deleteCompleted(): Promise<number>` - удалить выполненные

## Technology Stack Details

### Backend

**PHP 8.3**:
- Строгая типизация (`declare(strict_types=1)`)
- Типизированные свойства классов
- Union types, intersection types

**Laravel 11.x**:
- Eloquent ORM для работы с БД
- Request Validation для валидации
- Route Model Binding
- Service Container для DI

**MySQL 8.0/8.4**:
- InnoDB engine
- UTF8MB4 charset
- Индексы на полях id, status

### Frontend

**Vue 3.5**:
- Composition API
- `<script setup>` syntax
- Reactive system
- TypeScript support (опционально)

**Vite 7.2**:
- Hot Module Replacement (HMR)
- Fast builds
- Optimized production builds

### Development Tools

**PHPUnit 11.x**:
- Unit tests для Domain Layer
- Feature tests для API
- Coverage reports

**Laravel Dusk 8.x**:
- E2E тесты в браузере
- Headless Chrome
- User journey tests

**PHPStan 2.x**:
- Level 9 (максимальный)
- Larastan для Laravel
- CI integration

**PHP-CS-Fixer 3.x**:
- PSR-12 стандарт
- Автоматическое исправление
- Pre-commit hooks

**Xdebug 3.x**:
- Отладка в dev окружении
- Code coverage
- Profiling

### DevOps

**Docker**:
- PHP 8.3 FPM (production)
- PHP 8.3 CLI (development)
- Nginx
- MySQL 8.0
- Node.js для фронтенда

**GitHub Actions**:
- CI пайплайн
- Автоматические тесты
- Code quality checks

### Development Environment

**Изоляция инструментов разработки**:
- Все инструменты разработки работают только в Docker-контейнерах
- Локальная машина не требует установки PHP, Composer, линтеров
- Контейнер `app`: PHP 8.3-cli, Composer, PHPUnit, Laravel, Xdebug
- Контейнер `tools`: PHPStan, PHP-CS-Fixer
- Настройка прав доступа через UID/GID пользователя хоста
- Синхронизация файлов через Docker volumes с оптимизацией для macOS (`:cached`)

**Helper-скрипты**:
- Скрипт `dev` для упрощения работы с контейнерами
- Команды: `composer`, `php`, `artisan`, `phpunit`, `phpstan`, `cs-fix`, `shell`

## Database Migrations

Все миграции должны быть обратимыми:

```php
// database/migrations/xxxx_create_todos_table.php
public function up(): void
{
    Schema::create('todos', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->enum('status', ['active', 'completed'])->default('active');
        $table->timestamps();
        $table->index('status');
    });
}

public function down(): void
{
    Schema::dropIfExists('todos');
}
```

## Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=cooltodo
DB_USERNAME=root
DB_PASSWORD=

# Application
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Queue
QUEUE_CONNECTION=database
```





