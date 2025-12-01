# Task B1: Создать модели данных (Task, TaskStatus)

## Summary

Созданы модели данных Task и TaskStatus для Domain Layer. TaskStatus реализован как Value Object согласно принципам DDD. Модели покрыты тестами с использованием TDD подхода (Red-Green-Refactor).

## Source Requirements

- `DOCS/AI/Execution_Guide/04_TODO_Workplan.md` - Task B1
- `DOCS/INPROGRESS/B1_Create_Domain_Models.md` - Детали задачи

## Objectives

1. Создать TaskStatus как Value Object
2. Обновить Task для использования TaskStatus
3. Покрыть модели тестами (TDD: Red-Green-Refactor)
4. Обеспечить PHPStan level 9 без ошибок

## Dependencies

- A5: Создать структуру Domain Layer (Completed ✅)

## In-Scope / Out of Scope

**In-Scope**:
- Создание TaskStatus как Value Object
- Обновление Task для использования TaskStatus
- Unit тесты для обеих моделей
- PHPStan level 9 проверка

**Out of Scope**:
- Миграции БД (B2)
- Repository реализация (B3)

## Implementation Details

### TaskStatus Value Object

- Final class с readonly properties
- Статические методы `active()` и `completed()`
- Метод `fromString()` с валидацией
- Методы `isActive()`, `isCompleted()`, `getValue()`
- Метод `toggle()` для переключения статуса
- Метод `equals()` для сравнения

### Task Model

- Использует TaskStatus вместо строк
- Метод `getStatus()` возвращает TaskStatus
- Метод `getStatusValue()` для обратной совместимости (deprecated)
- Метод `toggleStatus()` использует TaskStatus::toggle()

## Acceptance Criteria

✅ Модели Task и TaskStatus созданы  
✅ Модели покрыты тестами (TDD: Red-Green-Refactor)  
✅ PHPStan level 9 без ошибок  
✅ Все тесты проходят

## Files Changed

- `backend/src/Domain/Models/TaskStatus.php` - новый файл
- `backend/src/Domain/Models/Task.php` - обновлен
- `tests/Unit/Domain/Models/TaskStatusTest.php` - новый файл
- `tests/Unit/Domain/Models/TaskTest.php` - обновлен

## Testing

- **TaskStatus**: 6 тестов, все проходят
- **Task**: 7 тестов, все проходят
- **Всего**: 13 тестов, 32 assertions

## Notes

- TaskStatus реализован как immutable Value Object
- Обратная совместимость сохранена через deprecated методы
- PHPStan level 9 проходит без ошибок

