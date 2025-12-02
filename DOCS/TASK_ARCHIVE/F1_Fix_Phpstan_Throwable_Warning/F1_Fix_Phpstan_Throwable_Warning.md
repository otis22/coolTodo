# Task F1: Исправить предупреждение PHPStan о use Throwable

**Статус**: Completed ✅  
**Создано**: 2025-01-27  
**Приоритет**: Low  
**Оценка**: 0.1 дня

## Описание

PHPStan выдает предупреждение:
```
Warning: The use statement with non-compound name 'Throwable' has no effect in /var/www/project/backend/bootstrap/app.php on line 8
```

## Проблема

`Throwable` - это глобальный интерфейс PHP, который доступен без импорта. Использование `use Throwable;` не имеет эффекта и вызывает предупреждение PHPStan.

## Решение

Удалить строку `use Throwable;` из `backend/bootstrap/app.php`, так как `Throwable` доступен глобально.

## План выполнения

- [x] Создать задачу
- [x] Удалить `use Throwable;` из `backend/bootstrap/app.php`
- [x] Проверить, что PHPStan больше не выдает предупреждение
- [x] Проверить, что тесты проходят
- [x] Закоммитить изменения

## Прогресс

Создано: 2025-01-27  
Завершено: 2025-01-27

### Выполненные действия

1. ✅ Удалена строка `use Throwable;` из `backend/bootstrap/app.php`
2. ✅ Проверено, что PHPStan больше не выдает предупреждение
3. ✅ Проверено, что все тесты проходят (52 tests, 156 assertions)

## Результат

Предупреждение PHPStan устранено. `Throwable` доступен глобально в PHP, поэтому импорт не требуется.

