# Task B4: Реализовать Use Cases (Create, Update, Delete, Toggle)

## Summary

Реализованы все Use Cases для управления задачами с использованием TDD подхода (Red-Green-Refactor). Все Use Cases покрыты unit тестами >90% и соответствуют PHPStan level 9.

## Source Requirements

- `DOCS/AI/Execution_Guide/04_TODO_Workplan.md` - Task B4
- `DOCS/INPROGRESS/B4_Implement_Use_Cases.md` - Детали задачи

## Objectives

1. Реализовать CreateTodoUseCase
2. Реализовать UpdateTodoUseCase
3. Реализовать DeleteTodoUseCase
4. Реализовать ToggleTodoStatusUseCase
5. Реализовать DeleteCompletedTodosUseCase
6. Покрыть все Use Cases тестами >90%

## Dependencies

- B1: Создать модели данных (Task, TaskStatus) (Completed ✅)
- B3: Реализовать TodoRepository (Completed ✅)

## In-Scope / Out of Scope

**In-Scope**:
- Реализация всех Use Cases
- Unit тесты для всех Use Cases
- PHPStan level 9 проверка

**Out of Scope**:
- API контроллеры (B5)
- Валидация запросов (B6)

## Implementation Details

### Реализованные Use Cases

1. **CreateTodoUseCase**
   - Метод: `execute(string $title): Task`
   - Создает задачу со статусом 'active'
   - Тесты: 2 теста, 9 assertions

2. **UpdateTodoUseCase**
   - Метод: `execute(int $id, string $title): Task`
   - Обновляет title задачи
   - Выбрасывает DomainException, если задача не найдена
   - Тесты: 3 теста, 13 assertions

3. **DeleteTodoUseCase**
   - Метод: `execute(int $id): void`
   - Удаляет задачу по ID
   - Выбрасывает DomainException, если задача не найдена
   - Тесты: 2 теста, 7 assertions

4. **ToggleTodoStatusUseCase**
   - Метод: `execute(int $id): Task`
   - Переключает статус (active ↔ completed)
   - Выбрасывает DomainException, если задача не найдена
   - Тесты: 3 теста, 16 assertions

5. **DeleteCompletedTodosUseCase**
   - Метод: `execute(): int`
   - Удаляет все задачи со статусом 'completed'
   - Возвращает количество удаленных задач
   - Тесты: 2 теста, 4 assertions

## Acceptance Criteria

✅ Все Use Cases реализованы  
✅ Все Use Cases покрыты unit тестами >90%  
✅ Все тесты проходят (TDD: Red-Green-Refactor)  
✅ PHPStan level 9 без ошибок  
✅ Используется Dependency Injection для репозитория

## Files Changed

- `backend/src/Domain/UseCases/CreateTodoUseCase.php` - реализация
- `backend/src/Domain/UseCases/UpdateTodoUseCase.php` - реализация
- `backend/src/Domain/UseCases/DeleteTodoUseCase.php` - реализация
- `backend/src/Domain/UseCases/ToggleTodoStatusUseCase.php` - реализация
- `backend/src/Domain/UseCases/DeleteCompletedTodosUseCase.php` - реализация
- `tests/Unit/Domain/UseCases/CreateTodoUseCaseTest.php` - тесты
- `tests/Unit/Domain/UseCases/UpdateTodoUseCaseTest.php` - тесты
- `tests/Unit/Domain/UseCases/DeleteTodoUseCaseTest.php` - тесты
- `tests/Unit/Domain/UseCases/ToggleTodoStatusUseCaseTest.php` - тесты
- `tests/Unit/Domain/UseCases/DeleteCompletedTodosUseCaseTest.php` - тесты

## Testing

- **Всего тестов**: 12 тестов, 49 assertions
- **Статус**: ✅ Все тесты проходят
- **PHPStan**: ✅ Level 9 без ошибок
- **Покрытие**: Все методы Use Cases покрыты тестами

## Notes

- Все Use Cases реализованы по TDD (Red-Green-Refactor)
- Используются моки для репозитория в unit тестах
- Обеспечена строгая типизация (PHP 8.3 strict types)
- Все Use Cases используют Dependency Injection

