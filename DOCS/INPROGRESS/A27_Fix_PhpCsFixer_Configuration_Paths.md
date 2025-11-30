# Task A27: Исправить пути конфигурации PHP-CS-Fixer для Docker

**Статус**: Completed ✅ (решено через A28)  
**Приоритет**: Medium  
**Оценка**: 0.25 дня

## Описание

PHP-CS-Fixer в CI использует `--config=../.php-cs-fixer.php`, но в локальном окружении (скрипт `dev`) конфигурация не указана, и конфигурационный файл не монтируется в контейнер tools. Нужно исправить это для согласованности с CI.

## Проблема

**В CI** (`.github/workflows/ci.yml`):
```yaml
- name: Run PHP-CS-Fixer
  working-directory: backend
  run: vendor/bin/php-cs-fixer fix --dry-run --diff --config=../.php-cs-fixer.php
```

**В локальном окружении** (`dev` скрипт):
```bash
cs-fix)
    shift
    exec_tools php-cs-fixer fix "$@"
    ;;
```

**Проблемы**:
1. Конфигурация не указана в скрипте `dev` (`--config` отсутствует)
2. Конфигурационный файл `.php-cs-fixer.php` не монтируется в контейнер tools
3. PHP-CS-Fixer не может найти конфигурацию и предлагает создать новую

**Текущая конфигурация** (`.php-cs-fixer.php`):
```php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/backend/src')  // Относительно корня проекта
    ->in(__DIR__ . '/tests')        // Относительно корня проекта
```

**Проблема с путями**:
- В CI: PHP-CS-Fixer запускается из `working-directory: backend` с `--config=../.php-cs-fixer.php`
  - `__DIR__` в конфигурации = корень проекта
  - `__DIR__ . '/backend/src'` → `/home/runner/work/coolTodo/coolTodo/backend/src` ✅
  - `__DIR__ . '/tests'` → `/home/runner/work/coolTodo/coolTodo/tests` ✅
  
- В Docker: PHP-CS-Fixer запускается из `/var/www/html` (backend/) без конфигурации
  - Конфигурация не найдена
  - Если указать `--config=../.php-cs-fixer.php`, `__DIR__` будет корень проекта
  - `__DIR__ . '/backend/src'` → `/var/www/html/../backend/src` = `/var/www/backend/src` ❌
  - Нужны пути `src` и `../tests` относительно `/var/www/html`

## Решение

**Вариант 1 (Рекомендуемый)**: Исправить пути в `.php-cs-fixer.php` для работы в обоих случаях

Использовать условную логику или проверку существования путей:
```php
$baseDir = __DIR__;
$srcPath = is_dir($baseDir . '/backend/src') ? $baseDir . '/backend/src' : $baseDir . '/src';
$testsPath = is_dir($baseDir . '/tests') ? $baseDir . '/tests' : $baseDir . '/../tests';

$finder = PhpCsFixer\Finder::create()
    ->in($srcPath)
    ->in($testsPath)
```

**Вариант 2**: Монтировать конфигурацию и указать в скрипте

1. Добавить монтирование `.php-cs-fixer.php` в `docker-compose.yml`
2. Обновить скрипт `dev` для использования `--config=../.php-cs-fixer.php`
3. Исправить пути в конфигурации для работы из Docker

**Вариант 3**: Создать отдельную конфигурацию для Docker (не рекомендуется - дублирование)

## Критерии приемки

✅ `./dev cs-fix` работает с правильной конфигурацией  
✅ PHP-CS-Fixer находит все файлы в `backend/src` и `tests`  
✅ Конфигурация работает как в контейнере, так и в CI  
✅ CI workflow не сломан

## Зависимости

- [x] A11: Создать Dockerfile.tools для инструментов анализа (Completed ✅)
- [x] A13: Создать helper-скрипты для разработки (Completed ✅)
- [ ] A26: Исправить пути PHPStan системно (связано - аналогичная проблема)

## Примечания

Задача решена через A28 - унификация структуры Docker. PHP-CS-Fixer теперь работает с правильной конфигурацией в обоих окружениях. Конфигурация указана в скрипте `dev` (`--config=../.php-cs-fixer.php`) и работает корректно.

## Связанные задачи

- A22: Исправить форматирование кода PHP-CS-Fixer (теперь конфигурация работает)
- A26: Исправить пути PHPStan системно (решено через A28)
- A28: Унифицировать пути конфигураций инструментов системно (Completed ✅ - решило эту задачу)

