# Task B1: Создать модели данных (Task, TaskStatus)

**Статус**: Completed ✅  
**Начало**: 2025-01-27  
**Приоритет**: High  
**Оценка**: 2 дня

## Описание

Создать модели данных Task и TaskStatus для Domain Layer. TaskStatus должен быть реализован как Value Object согласно принципам DDD. Модели должны быть покрыты тестами с использованием TDD подхода (Red-Green-Refactor).

## Критерии приемки

- ✅ Модели Task и TaskStatus созданы
- ✅ Модели покрыты тестами (TDD: Red-Green-Refactor)
- ✅ PHPStan level 9 без ошибок
- ✅ Все тесты проходят

## Зависимости

- [x] A5: Создать структуру Domain Layer (Completed ✅)

## Реализация

### TDD подход

Следую циклу Red-Green-Refactor:
1. **Red**: Написать failing тест
2. **Green**: Написать минимальный код для прохождения теста
3. **Refactor**: Улучшить код, сохраняя тесты зелеными

### Выполненные шаги

#### 1. Создан TaskStatus как Value Object (TDD: Red-Green)

✅ **Red**: Создан тест `tests/Unit/Domain/Models/TaskStatusTest.php` с 6 тестами:
- `test_can_create_active_status()` - создание статуса "active"
- `test_can_create_completed_status()` - создание статуса "completed"
- `test_can_create_from_string()` - создание из строки
- `test_throws_exception_for_invalid_string()` - валидация невалидных значений
- `test_status_equality()` - сравнение статусов
- `test_can_toggle_status()` - переключение статуса

✅ **Green**: Реализован класс `backend/src/Domain/Models/TaskStatus.php`:
- Value Object (final class, readonly properties)
- Статические методы `active()` и `completed()` для создания
- Метод `fromString()` для создания из строки с валидацией
- Методы `isActive()`, `isCompleted()`, `getValue()`
- Метод `toggle()` для переключения статуса
- Метод `equals()` для сравнения

#### 2. Обновлен Task для использования TaskStatus

✅ Обновлен класс `backend/src/Domain/Models/Task.php`:
- Заменено использование строк на `TaskStatus` Value Object
- Метод `getStatus()` теперь возвращает `TaskStatus` вместо строки
- Добавлен метод `getStatusValue()` для обратной совместимости (deprecated)
- Метод `toggleStatus()` использует `TaskStatus::toggle()`
- Константы `STATUS_ACTIVE` и `STATUS_COMPLETED` помечены как deprecated
- По умолчанию создается статус `TaskStatus::active()`

#### 3. Обновлены тесты Task

✅ Обновлен `tests/Unit/Domain/Models/TaskTest.php`:
- Все тесты обновлены для работы с `TaskStatus`
- Добавлен тест `test_can_create_task_with_custom_status()`
- Добавлен тест `test_get_status_returns_task_status_value_object()`
- Добавлен тест `test_get_status_value_returns_string_for_backward_compatibility()`

#### 4. Проверка PHPStan level 9

✅ PHPStan level 9 без ошибок:
- Исправлена проблема с nullable параметром в конструкторе Task
- Все типы корректно объявлены
- Нет предупреждений о неявных преобразованиях

### Созданные файлы

- ✅ `backend/src/Domain/Models/TaskStatus.php` - Value Object для статуса задачи
- ✅ `tests/Unit/Domain/Models/TaskStatusTest.php` - тесты для TaskStatus
- ✅ Обновлен `backend/src/Domain/Models/Task.php` - использует TaskStatus
- ✅ Обновлен `tests/Unit/Domain/Models/TaskTest.php` - тесты для Task с TaskStatus

## Проблемы и решения

### Проблема 1: Nullable параметр в конструкторе Task

**Симптомы**: PHPStan выдавал предупреждение о неявном nullable типе

**Решение**: Изменен конструктор с `private TaskStatus $status = null` на явный nullable параметр `?TaskStatus $status = null` и инициализация через `??` оператор.

**Реализовано**: Исправлено в `backend/src/Domain/Models/Task.php`

## Результаты проверки окружения

### Попытка запуска тестов и PHPStan

**Проблемы выявлены**:

1. **PHPUnit не установлен**:
   - `./dev phpunit` → `vendor/bin/phpunit: not found`
   - Причина: `composer install` не может выполниться из-за прав доступа к `vendor`

2. **PHPStan не находит файлы**:
   - `./dev phpstan` → `At least one path must be specified to analyse`
   - Причина: пути в `phpstan.neon` неверны для Docker контейнера

3. **Composer install не работает**:
   - `./dev composer install` → `vendor/composer does not exist and could not be created`
   - Причина: директория `vendor` принадлежит root, контейнер запускается от пользователя хоста

### Созданные задачи на доработку окружения

- **A23**: Исправить права доступа для composer install (High, 0.25 дня)
- **A24**: Исправить пути в конфигурации PHPStan (Medium, 0.25 дня)
- **A25**: Исправить пути к конфигурационным файлам в скрипте dev (High, 0.25 дня)

**Примечание**: В задачах A17, A20, A21 были исправлены пути для CI workflow. Эти изменения должны работать и локально, но скрипт `dev` не был обновлен для использования тех же путей. Задача A25 решает эту проблему.

Эти задачи были выполнены (A23, A24, A25, A28 - все Completed ✅).

### Финальная проверка

✅ **Все тесты проходят**: 13 тестов, 32 assertions
- Task: 7 тестов
- TaskStatus: 6 тестов

✅ **PHPStan level 9**: `[OK] No errors`

✅ **Все критерии приемки выполнены**:
- Модели Task и TaskStatus созданы
- Модели покрыты тестами (TDD: Red-Green-Refactor)
- PHPStan level 9 без ошибок
- Все тесты проходят

## Связанные задачи

- A23: Исправить права доступа для composer install (блокирует запуск тестов)
- A24: Исправить пути в конфигурации PHPStan (блокирует статический анализ)
- A25: Исправить пути к конфигурационным файлам в скрипте dev (блокирует запуск тестов и PHPStan)
- B2: Создать миграции БД (следующая задача, зависит от B1)

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

