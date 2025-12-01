# Task B6: Реализовать валидацию запросов

**Статус**: Completed ✅  
**Начало**: 2025-01-27  
**Завершено**: 2025-01-27  
**Приоритет**: High  
**Оценка**: 1 день

## Описание

Реализовать валидацию запросов для API endpoints используя Laravel Form Requests. Все валидации должны быть покрыты тестами (TDD: Red-Green-Refactor).

## Зависимости

- [x] B5: Реализовать API контроллеры (Completed ✅)

## Требования

### Request классы для создания

1. **CreateTodoRequest** - POST /api/todos
   - Валидация: `title` - required, string, max:255, min:1

2. **UpdateTodoRequest** - PUT /api/todos/{id}
   - Валидация: `title` - required, string, max:255, min:1

### Текущая ситуация

В `TodoController` сейчас используется базовая проверка:
```php
$title = $request->input('title');
if (!is_string($title) || $title === '') {
    return response()->json(['error' => 'Title is required'], 400);
}
```

Нужно заменить на Form Requests для правильной валидации и обработки ошибок.

## План выполнения

### Шаг 1: Создать Form Request классы (TDD: Red)
- [x] Написать failing тесты для валидации ✅
- [x] Создать `CreateTodoRequest` ✅
- [x] Создать `UpdateTodoRequest` ✅

### Шаг 2: Обновить контроллер
- [x] Заменить ручную проверку на `CreateTodoRequest` в методе `store()` ✅
- [x] Заменить ручную проверку на `UpdateTodoRequest` в методе `update()` ✅
- [x] Убрать старую валидацию ✅

### Шаг 3: Тестирование
- [x] Все тесты проходят ✅ (17 тестов, 47 assertions)
- [x] Проверить обработку ошибок валидации (422 статус) ✅
- [x] Проверить правильность сообщений об ошибках ✅

## Критерии приемки

✅ Созданы Form Request классы для всех endpoints, требующих валидации  
✅ Валидация работает корректно  
✅ Ошибки валидации возвращают статус 422  
✅ Все тесты проходят (TDD: Red-Green-Refactor)  
✅ PHPStan level 9 без ошибок

## Технические детали

### Структура Form Request

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTodoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:1'],
        ];
    }
}
```

### Использование в контроллере

```php
public function store(CreateTodoRequest $request): JsonResponse
{
    $validated = $request->validated();
    $task = $this->createTodoUseCase->execute($validated['title']);
    // ...
}
```

### Тесты валидации

Нужно проверить:
- Успешная валидация
- Отсутствие title
- Пустой title
- Title слишком длинный (>255 символов)
- Неверный тип данных

## Прогресс

Начато: 2025-01-27  
Завершено: 2025-01-27

### Выполненные действия

1. **Созданы Form Request классы** ✅
   - `CreateTodoRequest` - валидация для создания задачи
   - `UpdateTodoRequest` - валидация для обновления задачи
   - Правила: `title` - required, string, max:255, min:1

2. **Обновлен контроллер** ✅
   - Метод `store()` использует `CreateTodoRequest`
   - Метод `update()` использует `UpdateTodoRequest`
   - Удалена ручная проверка валидации

3. **Написаны тесты валидации** ✅
   - Тесты для отсутствующего title
   - Тесты для пустого title
   - Тесты для слишком длинного title (>255 символов)
   - Все тесты проверяют статус 422 и ошибки валидации

### Результаты

- ✅ Созданы Form Request классы для всех endpoints, требующих валидации
- ✅ Валидация работает корректно
- ✅ Ошибки валидации возвращают статус 422
- ✅ Все тесты проходят (17 тестов, 47 assertions)
- ✅ PHPStan level 9 без ошибок

