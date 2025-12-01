# Task B5: Реализовать API контроллеры

**Статус**: In Progress  
**Начало**: 2025-01-27  
**Приоритет**: High  
**Оценка**: 2 дня

## Описание

Реализовать RESTful API контроллер `TodoController` с использованием Use Cases из Domain Layer. Все endpoints должны быть покрыты feature тестами (TDD: Red-Green-Refactor).

## Зависимости

- [x] B4: Реализовать Use Cases (Create, Update, Delete, Toggle) (Completed ✅)

## Требования

### TodoController методы

1. **index(): JsonResponse** - GET /api/todos
   - Получает все задачи через GetTodosUseCase
   - Возвращает JSON массив задач

2. **store(CreateTodoRequest $request): JsonResponse** - POST /api/todos
   - Создает задачу через CreateTodoUseCase
   - Возвращает 201 Created с созданной задачей

3. **update(UpdateTodoRequest $request, int $id): JsonResponse** - PUT /api/todos/{id}
   - Обновляет задачу через UpdateTodoUseCase
   - Возвращает 200 OK с обновленной задачей
   - Возвращает 404, если задача не найдена

4. **updateStatus(Request $request, int $id): JsonResponse** - PATCH /api/todos/{id}/status
   - Переключает статус через ToggleTodoStatusUseCase
   - Возвращает 200 OK с обновленной задачей
   - Возвращает 404, если задача не найдена

5. **destroy(int $id): JsonResponse** - DELETE /api/todos/{id}
   - Удаляет задачу через DeleteTodoUseCase
   - Возвращает 204 No Content
   - Возвращает 404, если задача не найдена

6. **destroyCompleted(): JsonResponse** - DELETE /api/todos/completed
   - Удаляет все выполненные задачи через DeleteCompletedTodosUseCase
   - Возвращает 200 OK с количеством удаленных задач

## План выполнения

### Шаг 1: Создать GetTodosUseCase (если не существует)
- [ ] Проверить существование GetTodosUseCase
- [ ] Создать Use Case, если отсутствует
- [ ] Написать unit тесты

### Шаг 2: Реализовать TodoController
- [ ] Написать failing feature тесты для всех endpoints
- [ ] Реализовать метод index()
- [ ] Реализовать метод store()
- [ ] Реализовать метод update()
- [ ] Реализовать метод updateStatus()
- [ ] Реализовать метод destroy()
- [ ] Реализовать метод destroyCompleted()

### Шаг 3: Тестирование
- [ ] Все feature тесты проходят
- [ ] Проверить обработку ошибок (404, валидация)
- [ ] Проверить правильность HTTP статусов

### Шаг 4: Финальная проверка
- [ ] Все тесты проходят
- [ ] PHPStan level 9 без ошибок
- [ ] Код соответствует PSR-12

## Критерии приемки

✅ Все 6 методов контроллера реализованы  
✅ Все endpoints покрыты feature тестами (TDD: Red-Green-Refactor)  
✅ Правильные HTTP статусы возвращаются  
✅ Обработка ошибок (404 для несуществующих задач)  
✅ PHPStan level 9 без ошибок  
✅ Используется Dependency Injection для Use Cases

## Технические детали

### Используемые компоненты
- `App\Domain\UseCases\GetTodosUseCase` - получение всех задач
- `App\Domain\UseCases\CreateTodoUseCase` - создание задачи
- `App\Domain\UseCases\UpdateTodoUseCase` - обновление задачи
- `App\Domain\UseCases\ToggleTodoStatusUseCase` - переключение статуса
- `App\Domain\UseCases\DeleteTodoUseCase` - удаление задачи
- `App\Domain\UseCases\DeleteCompletedTodosUseCase` - удаление выполненных задач
- `App\Infrastructure\Http\Requests\CreateTodoRequest` - валидация создания (будет создана в B6)
- `App\Infrastructure\Http\Requests\UpdateTodoRequest` - валидация обновления (будет создана в B6)

### Структура тестов
Тесты должны быть в `tests/Feature/`:
- `TodoControllerTest.php` - feature тесты для всех endpoints

### Формат ответов
- Успешные ответы: JSON с данными задачи/задач
- Ошибки: JSON с сообщением об ошибке
- HTTP статусы: 200, 201, 204, 404

## Прогресс

Начато: 2025-01-27

## Заметки

- Следовать принципам TDD: сначала feature тесты, потом реализация
- Использовать Laravel Response helpers (response()->json())
- Обрабатывать исключения DomainException и преобразовывать в HTTP 404
- Валидация запросов будет реализована в задаче B6, пока можно использовать Request

## Документирование результатов

### Assumption Log

- **A1**: [Описание предположения] - [Обоснование]

### Успешные решения

- **Решение 1**: [Описание решения] - [Почему оно было эффективным]

### Неверные решения

#### Неверное решение 1: [Краткое название]

**Принятое решение**: [Подробное описание того, что было сделано]

**Обоснование выбора**: [Почему это решение казалось правильным]

**Возникшие проблемы**: 
- [Проблема 1]

**Корректное решение**: [Что было сделано вместо этого]

**Извлеченные уроки**: [Что можно извлечь из этого опыта]

