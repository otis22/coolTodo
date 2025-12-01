# Task B7: Настроить API routes

**Статус**: Completed ✅  
**Завершено**: 2025-01-27

## Краткое описание

Проверена и подтверждена корректная настройка всех API routes. Все 6 routes зарегистрированы, работают и покрыты тестами.

## Основные результаты

- ✅ Все 6 API routes зарегистрированы и работают
- ✅ Routes имеют правильные ограничения (where для id)
- ✅ Routes подключены в bootstrap/app.php
- ✅ Все routes покрыты feature тестами (17 tests, 47 assertions)
- ✅ PHPStan level 9 без ошибок

## Зарегистрированные routes

1. `GET /api/todos` - получить список всех задач
2. `POST /api/todos` - создать новую задачу
3. `PUT /api/todos/{id}` - обновить задачу
4. `PATCH /api/todos/{id}/status` - переключить статус задачи
5. `DELETE /api/todos/{id}` - удалить задачу
6. `DELETE /api/todos/completed` - удалить все выполненные задачи

## Ключевые особенности

- Routes с параметром `{id}` имеют ограничение `->where('id', '[0-9]+')` для валидации
- Все routes автоматически используют 'api' middleware group
- Routes доступны через префикс `/api/todos`
- Все endpoints покрыты feature тестами с проверкой успешных сценариев и ошибок

