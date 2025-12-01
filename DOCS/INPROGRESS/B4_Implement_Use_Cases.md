# Task B4: Реализовать Use Cases (Create, Update, Delete, Toggle)

**Статус**: In Progress  
**Начало**: 2025-01-27  
**Приоритет**: High  
**Оценка**: 3 дня

## Описание

Реализовать Use Cases для управления задачами:
- `CreateTodoUseCase` - создание новой задачи
- `UpdateTodoUseCase` - обновление задачи
- `DeleteTodoUseCase` - удаление задачи
- `ToggleTodoStatusUseCase` - переключение статуса задачи

Все Use Cases должны быть реализованы с использованием TDD (Red-Green-Refactor) и покрыты тестами >90%.

## Зависимости

- [x] B1: Создать модели данных (Task, TaskStatus) (Completed ✅)
- [x] B3: Реализовать TodoRepository (Completed ✅)

## Требования

### CreateTodoUseCase
- Метод: `execute(string $title): Task`
- Создает новую задачу со статусом 'active'
- Использует `TodoRepositoryInterface::save()`
- Возвращает созданную задачу с ID

### UpdateTodoUseCase
- Метод: `execute(int $id, string $title): Task`
- Находит задачу по ID через репозиторий
- Обновляет title задачи
- Сохраняет изменения через репозиторий
- Возвращает обновленную задачу
- Выбрасывает исключение, если задача не найдена

### DeleteTodoUseCase
- Метод: `execute(int $id): void`
- Находит задачу по ID через репозиторий
- Удаляет задачу через репозиторий
- Выбрасывает исключение, если задача не найдена

### ToggleTodoStatusUseCase
- Метод: `execute(int $id): Task`
- Находит задачу по ID через репозиторий
- Переключает статус (active ↔ completed) через метод `toggleStatus()`
- Сохраняет изменения через репозиторий
- Возвращает обновленную задачу
- Выбрасывает исключение, если задача не найдена

## План выполнения

### Шаг 1: CreateTodoUseCase
- [x] Написать failing unit тест для CreateTodoUseCase
- [x] Реализовать минимальный код для прохождения теста
- [x] Рефакторинг (если необходимо)
- [x] Проверить покрытие тестами

### Шаг 2: UpdateTodoUseCase
- [x] Написать failing unit тест для UpdateTodoUseCase
- [x] Реализовать минимальный код для прохождения теста
- [x] Рефакторинг (если необходимо)
- [x] Проверить покрытие тестами

### Шаг 3: DeleteTodoUseCase
- [x] Написать failing unit тест для DeleteTodoUseCase
- [x] Реализовать минимальный код для прохождения теста
- [x] Рефакторинг (если необходимо)
- [x] Проверить покрытие тестами

### Шаг 4: ToggleTodoStatusUseCase
- [x] Написать failing unit тест для ToggleTodoStatusUseCase
- [x] Реализовать минимальный код для прохождения теста
- [x] Рефакторинг (если необходимо)
- [x] Проверить покрытие тестами

### Шаг 5: Финальная проверка
- [x] Все тесты проходят
- [x] Покрытие тестами >90%
- [x] PHPStan level 9 без ошибок
- [x] Код соответствует PSR-12

## Критерии приемки

✅ Все Use Cases реализованы  
✅ Все Use Cases покрыты unit тестами >90%  
✅ Все тесты проходят (TDD: Red-Green-Refactor)  
✅ PHPStan level 9 без ошибок  
✅ Код соответствует PSR-12  
✅ Используется Dependency Injection для репозитория

## Технические детали

### Используемые компоненты
- `App\Domain\Models\Task` - модель задачи
- `App\Domain\Models\TaskStatus` - Value Object для статуса
- `App\Domain\Repositories\TodoRepositoryInterface` - интерфейс репозитория

### Структура тестов
Тесты должны быть в `backend/tests/Unit/Domain/UseCases/`:
- `CreateTodoUseCaseTest.php`
- `UpdateTodoUseCaseTest.php`
- `DeleteTodoUseCaseTest.php`
- `ToggleTodoStatusUseCaseTest.php`

### Обработка ошибок
- Если задача не найдена (для Update, Delete, Toggle), выбросить исключение
- Использовать подходящий тип исключения (например, `DomainException` или создать кастомное)

## Прогресс

Начато: 2025-01-27  
Завершено: 2025-01-27

### Реализованные Use Cases

1. **CreateTodoUseCase** ✅
   - Метод `execute(string $title): Task`
   - Создает задачу со статусом 'active'
   - Тесты: 2 теста, 9 assertions

2. **UpdateTodoUseCase** ✅
   - Метод `execute(int $id, string $title): Task`
   - Обновляет title задачи
   - Выбрасывает DomainException, если задача не найдена
   - Тесты: 3 теста, 13 assertions

3. **DeleteTodoUseCase** ✅
   - Метод `execute(int $id): void`
   - Удаляет задачу по ID
   - Выбрасывает DomainException, если задача не найдена
   - Тесты: 2 теста, 7 assertions

4. **ToggleTodoStatusUseCase** ✅
   - Метод `execute(int $id): Task`
   - Переключает статус (active ↔ completed)
   - Выбрасывает DomainException, если задача не найдена
   - Тесты: 3 теста, 16 assertions

### Результаты тестирования

- **Всего тестов**: 10
- **Всего assertions**: 45
- **Статус**: ✅ Все тесты проходят
- **PHPStan**: ✅ Level 9 без ошибок
- **Покрытие**: Все методы Use Cases покрыты тестами

## Заметки

- Следовать принципам TDD: сначала тест, потом реализация
- Использовать моки для репозитория в unit тестах
- Обеспечить строгую типизацию (PHP 8.3 strict types)

