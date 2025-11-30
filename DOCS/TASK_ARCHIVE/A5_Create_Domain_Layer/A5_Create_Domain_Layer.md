# Task A5: Создать структуру Domain Layer

**Статус**: Completed ✅  
**Начало**: 2025-01-27  
**Приоритет**: High  
**Оценка**: 0.5 дня

## Описание

Создать структуру Domain Layer согласно принципам Clean Architecture. Domain Layer содержит бизнес-логику приложения и не зависит от инфраструктурных деталей.

## Критерии приемки

✅ Директории `Domain/UseCases` и `Domain/Models` созданы  
✅ Структура соответствует архитектуре проекта  
✅ Все необходимые компоненты на месте

## Проверка структуры

### Domain/UseCases/

Созданы следующие Use Cases:
- ✅ `CreateTodoUseCase.php` - создание новой задачи
- ✅ `UpdateTodoUseCase.php` - обновление задачи
- ✅ `DeleteTodoUseCase.php` - удаление задачи
- ✅ `ToggleTodoStatusUseCase.php` - переключение статуса задачи
- ✅ `GetTodosUseCase.php` - получение списка задач
- ✅ `DeleteCompletedTodosUseCase.php` - удаление всех завершенных задач

### Domain/Models/

Создана модель:
- ✅ `Task.php` - доменная модель задачи
  - Содержит бизнес-логику (toggleStatus, isCompleted, isActive)
  - Использует константы для статусов (STATUS_ACTIVE, STATUS_COMPLETED)
  - Не зависит от Laravel или Eloquent

### Domain/Repositories/

Создан интерфейс:
- ✅ `TodoRepositoryInterface.php` - интерфейс для доступа к данным
  - Определяет контракт для работы с задачами
  - Реализуется в Infrastructure Layer

## Структура директорий

```
backend/src/Domain/
├── Models/
│   └── Task.php
├── Repositories/
│   └── TodoRepositoryInterface.php
└── UseCases/
    ├── CreateTodoUseCase.php
    ├── DeleteCompletedTodosUseCase.php
    ├── DeleteTodoUseCase.php
    ├── GetTodosUseCase.php
    ├── ToggleTodoStatusUseCase.php
    └── UpdateTodoUseCase.php
```

## Принципы Clean Architecture

✅ Domain Layer не зависит от Laravel  
✅ Используются интерфейсы для доступа к данным  
✅ Чистая бизнес-логика без инфраструктурных деталей  
✅ Модели содержат бизнес-правила

## Результат

Структура Domain Layer полностью создана и соответствует требованиям задачи. Все необходимые директории и базовые компоненты на месте. Use Cases и Models реализованы согласно архитектуре проекта.

## Зависимости

- [x] A1: Инициализировать проект (Completed ✅)

## Связанные задачи

- A6: Создать структуру Infrastructure Layer (следующая задача)
- B1: Создать модели данных (Task, TaskStatus) - использует структуру Domain Layer

