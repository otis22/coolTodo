# Task A17: Исправить пути в CI для PHPUnit

**Статус**: In Progress  
**Приоритет**: High  
**Оценка**: 0.25 дня

## Описание

В GitHub Actions CI workflow PHPUnit запускается с `working-directory: backend`, но `phpunit.xml` находится в корне проекта и содержит пути относительно корня. Это приводит к тому, что PHPUnit не может найти тесты и исходный код.

## Проблема

**Файл**: `.github/workflows/ci.yml`  
**Строка**: 98-100

**Текущая конфигурация**:
```yaml
- name: Run PHPUnit tests
  working-directory: backend
  run: vendor/bin/phpunit --configuration=../phpunit.xml --coverage-text --coverage-clover=../coverage.xml
```

**Проблемы**:

1. **Пути к тестам в phpunit.xml**:
   - В `phpunit.xml` указаны пути `tests/Unit` и `tests/Feature` (относительно корня)
   - Но PHPUnit запускается из `backend/`, поэтому эти пути неверны
   - Должны быть `../tests/Unit` и `../tests/Feature`

2. **Bootstrap путь в phpunit.xml**:
   - `bootstrap="vendor/autoload.php"` - это путь относительно phpunit.xml
   - Если phpunit.xml в корне, а vendor в backend/, то путь должен быть `backend/vendor/autoload.php`
   - Или если запускать из backend/, то bootstrap должен быть `vendor/autoload.php` (относительно backend/)

3. **Путь к исходному коду в phpunit.xml**:
   - `<directory>backend/src</directory>` - это путь относительно phpunit.xml (в корне)
   - Если запускать из backend/, то путь должен быть `src/`

## Причина

После перемещения `composer.json` в `backend/` (задача A16), CI workflow был обновлен для работы из `backend/`, но `phpunit.xml` остался в корне с путями относительно корня. Это создало несоответствие.

## Решение

**Вариант 1 (Рекомендуемый)**: Обновить пути в `phpunit.xml` для работы из `backend/`:
```xml
<phpunit bootstrap="vendor/autoload.php" ...>
    <testsuites>
        <testsuite name="Unit">
            <directory>../tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>../tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>src</directory>
        </include>
    </source>
</phpunit>
```

**Вариант 2**: Переместить `phpunit.xml` в `backend/` и обновить пути:
- Переместить `phpunit.xml` → `backend/phpunit.xml`
- Обновить пути в файле
- Обновить CI workflow: `--configuration=phpunit.xml`

**Вариант 3**: Запускать PHPUnit из корня проекта:
- Убрать `working-directory: backend` из CI
- Обновить команду: `cd backend && vendor/bin/phpunit --configuration=../phpunit.xml`

## Критерии приемки

✅ PHPUnit находит все тесты в CI  
✅ PHPUnit находит исходный код для coverage  
✅ Coverage отчет генерируется корректно  
✅ Тесты запускаются успешно в CI

## Зависимости

- [x] A16: Исправить расположение composer.json (Completed ✅)

## Реализация

✅ Обновлены пути в `phpunit.xml`:
- Тесты: `tests/Unit` → `../tests/Unit`
- Тесты: `tests/Feature` → `../tests/Feature`
- Исходный код: `backend/src` → `src/`
- Bootstrap: `vendor/autoload.php` (остается, так как относительно backend/)

✅ Изменения отправлены в `origin/main`

**Ожидание**: Проверка результата в GitHub Actions workflow.

### Проблема с текущим решением

**Важно**: PHPUnit разрешает пути в конфигурационном файле относительно расположения самого конфигурационного файла, а не относительно рабочей директории, из которой запускается команда.

**Текущая ситуация**:
- `phpunit.xml` находится в корне проекта
- PHPUnit запускается из `backend/` с `--configuration=../phpunit.xml`
- Все пути в `phpunit.xml` разрешаются относительно корня проекта (где находится phpunit.xml)

**Проблемы с текущими путями**:
1. `bootstrap="vendor/autoload.php"` → ищет `vendor/autoload.php` в корне проекта, но он находится в `backend/vendor/autoload.php`
2. `<directory>src</directory>` → ищет `src/` в корне проекта, но он находится в `backend/src/`
3. `<directory>../tests/Unit</directory>` → правильно, ищет `tests/Unit` в корне проекта

**Решение**: Исправить пути относительно расположения `phpunit.xml` (корень проекта):
- `bootstrap="backend/vendor/autoload.php"` (относительно корня)
- `<directory>backend/src</directory>` (относительно корня)
- `<directory>../tests/Unit</directory>` → `<directory>tests/Unit</directory>` (относительно корня)

## Связанные задачи

- A16: Исправить расположение composer.json (привело к несоответствию путей)

