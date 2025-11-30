# Task B2: Создать миграции БД

**Статус**: Completed ✅  
**Приоритет**: High  
**Оценка**: 1 день

## Описание

Создать миграцию для таблицы `todos` в базе данных согласно технической спецификации. Миграция должна быть обратимой (rollback).

## Критерии приемки

- ✅ Таблица `todos` создана
- ✅ Миграция обратима (rollback работает)
- ✅ Структура таблицы соответствует спецификации

## Зависимости

- [x] B1: Создать модели данных (Task, TaskStatus) (Completed ✅)

## Реализация

### Созданная миграция

**Файл**: `backend/database/migrations/2025_11_30_185442_create_todos_table.php`

**Структура таблицы**:
- `id` - BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `title` - VARCHAR(255) NOT NULL
- `status` - ENUM('active', 'completed') NOT NULL DEFAULT 'active'
- `created_at` - TIMESTAMP NULL
- `updated_at` - TIMESTAMP NULL
- `idx_status` - INDEX на поле `status`

**Метод `up()`**:
- Создает таблицу `todos` с указанными полями
- Добавляет индекс на поле `status` для оптимизации запросов

**Метод `down()`**:
- Удаляет таблицу `todos` (откат миграции)

### Проверка

✅ **Миграция применяется**: `./dev artisan migrate` - успешно
✅ **Миграция откатывается**: `./dev artisan migrate:rollback` - успешно
✅ **PHPStan**: без ошибок

## Созданные файлы

- ✅ `backend/database/migrations/2025_11_30_185442_create_todos_table.php` - миграция для создания таблицы todos

## Связанные задачи

- B1: Создать модели данных (Task, TaskStatus) (Completed ✅)
- B3: Реализовать TodoRepository (следующая задача, зависит от B2)

