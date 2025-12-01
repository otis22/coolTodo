# Task B6: Реализовать валидацию запросов

**Статус**: Completed ✅  
**Завершено**: 2025-01-27

## Краткое описание

Реализована валидация запросов для API endpoints используя Laravel Form Requests. Все валидации покрыты тестами (TDD: Red-Green-Refactor).

## Основные результаты

- ✅ Созданы Form Request классы: `CreateTodoRequest` и `UpdateTodoRequest`
- ✅ Валидация правил: `title` - required, string, max:255, min:1
- ✅ Обновлен `TodoController` для использования Form Requests
- ✅ Добавлено 6 новых тестов для валидации
- ✅ Все тесты проходят (17 тестов, 47 assertions)
- ✅ PHPStan level 9 без ошибок

## Ключевые решения

1. **Form Request классы**:
   - `CreateTodoRequest` для POST /api/todos
   - `UpdateTodoRequest` для PUT /api/todos/{id}
   - Оба используют одинаковые правила валидации

2. **Обработка ошибок**:
   - Ошибки валидации возвращают статус 422
   - Laravel автоматически форматирует ответы с ошибками валидации

3. **TDD подход**:
   - Сначала написаны failing тесты
   - Затем созданы Form Request классы
   - Все тесты проходят

## Тесты валидации

- `test_store_returns_422_when_title_missing`
- `test_store_returns_422_when_title_empty`
- `test_store_returns_422_when_title_too_long`
- `test_update_returns_422_when_title_missing`
- `test_update_returns_422_when_title_empty`
- `test_update_returns_422_when_title_too_long`

