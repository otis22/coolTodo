# Task B2: Создать миграции БД

## Summary

Создана миграция для таблицы `todos` в базе данных согласно технической спецификации. Миграция обратима (rollback работает).

## Source Requirements

- `DOCS/AI/Execution_Guide/04_TODO_Workplan.md` - Task B2
- `DOCS/INPROGRESS/B2_Create_Database_Migrations.md` - Детали задачи

## Objectives

1. Создать миграцию для таблицы `todos`
2. Обеспечить обратимость миграции (rollback)
3. Соответствие структуре из технической спецификации

## Dependencies

- B1: Создать модели данных (Task, TaskStatus) (Completed ✅)

## In-Scope / Out of Scope

**In-Scope**:
- Создание миграции для таблицы `todos`
- Обратимость миграции (rollback)

**Out of Scope**:
- Seed данные
- Другие таблицы

## Implementation Details

### Структура таблицы `todos`

- `id` - BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `title` - VARCHAR(255) NOT NULL
- `status` - ENUM('active', 'completed') NOT NULL DEFAULT 'active'
- `created_at` - TIMESTAMP NULL
- `updated_at` - TIMESTAMP NULL
- `idx_status` - INDEX на поле `status`

### Миграция

- Метод `up()` создает таблицу с указанными полями
- Метод `down()` удаляет таблицу (откат)

## Acceptance Criteria

✅ Таблица `todos` создана  
✅ Миграция обратима (rollback работает)  
✅ Структура таблицы соответствует спецификации

## Files Changed

- `backend/database/migrations/2025_11_30_185442_create_todos_table.php` - новый файл

## Testing

- ✅ Миграция применяется: `./dev artisan migrate`
- ✅ Миграция откатывается: `./dev artisan migrate:rollback`
- ✅ PHPStan: без ошибок

## Notes

- Миграция соответствует технической спецификации
- Индекс на поле `status` для оптимизации запросов

