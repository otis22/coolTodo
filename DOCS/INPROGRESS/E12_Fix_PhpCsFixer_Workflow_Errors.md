# Task E12: Исправить ошибки PHP-CS-Fixer в CI workflow

**Статус**: Open  
**Приоритет**: Medium  
**Оценка**: 0.25 дня

## Описание

PHP-CS-Fixer находит файлы для исправления в CI workflow, что вызывает exit code 8 и показывает ошибку в логах, даже несмотря на `continue-on-error: true`.

## Проблема

**Текущая ситуация**:
- PHP-CS-Fixer находит 6 файлов для исправления
- Возвращает exit code 8
- В логах показывается ошибка: `##[error]Process completed with exit code 8.`
- Хотя workflow продолжается (`continue-on-error: true`), это создает ложное впечатление об ошибке

**Логи из последнего workflow**:
```
Run PHP-CS-Fixer: Found 6 of 32 files that can be fixed in 0.336 seconds
##[error]Process completed with exit code 8.
```

## Зависимости

- [x] E11: Создать скрипт инициализации проекта (Completed ✅)

## Требования

### Цель

Исправить форматирование кода, чтобы PHP-CS-Fixer не находил файлов для исправления в CI.

### Критерии приемки

✅ PHP-CS-Fixer не находит файлов для исправления в CI  
✅ Нет ошибок в логах workflow  
✅ Все файлы отформатированы согласно PSR-12  
✅ Локально `./dev cs-fix` не находит файлов для исправления  
✅ PHPStan level 9 без ошибок  
✅ Все тесты проходят

## План выполнения

### Шаг 1: Локальное исправление форматирования
- [ ] Запустить `./dev cs-fix` локально
- [ ] Проверить, какие файлы нужно исправить
- [ ] Исправить форматирование всех файлов
- [ ] Проверить, что `./dev cs-fix --dry-run` не находит файлов

### Шаг 2: Проверка в CI
- [ ] Сделать commit и push
- [ ] Проверить, что PHP-CS-Fixer в CI не находит файлов
- [ ] Убедиться, что нет ошибок в логах

## Технические детали

### Текущая конфигурация в workflow

```yaml
- name: Run PHP-CS-Fixer
  working-directory: backend
  run: vendor/bin/php-cs-fixer fix --dry-run --diff --config=../.php-cs-fixer.php
  continue-on-error: true
```

### Команда для локального исправления

```bash
./dev cs-fix
```

## Прогресс

Создано: 2025-12-01

