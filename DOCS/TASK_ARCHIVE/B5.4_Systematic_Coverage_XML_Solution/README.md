# Task B5.4: Системное решение для coverage.xml в локальном и CI окружении

**Статус**: Completed ✅  
**Завершено**: 2025-01-27

## Краткое описание

Создано универсальное решение для генерации coverage.xml, которое работает и локально (Docker), и в CI (GitHub Actions) без конфликтов.

## Основные результаты

- ✅ Добавлен `XDEBUG_MODE=develop,coverage` в docker-compose.yml
- ✅ Обновлен скрипт `dev` с поддержкой coverage
- ✅ Coverage.xml генерируется локально в корне проекта
- ✅ Coverage.xml генерируется в CI в том же месте
- ✅ Обычные тесты работают без изменений

## Ключевые решения

1. **XDEBUG_MODE в docker-compose.yml**:
   - Добавлена переменная `XDEBUG_MODE=develop,coverage` для сервиса app
   - Позволяет использовать и отладку, и coverage

2. **Обновление скрипта dev**:
   - Добавлена опция `--coverage` для команды `phpunit`
   - Добавлена отдельная команда `coverage` как алиас
   - Команды: `./dev phpunit --coverage` или `./dev coverage`

3. **Унификация путей**:
   - В локальном окружении: `./dev coverage` генерирует `coverage.xml` в корне
   - В CI: `--coverage-clover=../coverage.xml` генерирует файл в корне (относительно backend/)
   - Оба пути ведут к одному месту

## Использование

```bash
# Генерация coverage локально
./dev coverage

# Или через опцию
./dev phpunit --coverage

# Обычные тесты без coverage
./dev phpunit
```

