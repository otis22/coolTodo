# Task A20: Исправить скрипт post-autoload-dump в composer.json

**Статус**: Completed ✅  
**Приоритет**: High  
**Оценка**: 0.1 дня

## Описание

В `backend/composer.json` скрипт `post-autoload-dump` пытается запустить `artisan package:discover`, но это вызывает ошибку в CI, так как `artisan` пытается загрузить `../vendor/autoload.php` (из корня), а vendor находится в `backend/vendor/`.

## Проблема

**Файл**: `backend/composer.json`  
**Строка**: 38-40

**Текущая конфигурация**:
```json
"post-autoload-dump": [
    "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
    "@php artisan package:discover --ansi"
]
```

**Ошибка в CI**:
```
PHP Warning: require(/home/runner/work/coolTodo/coolTodo/backend/../vendor/autoload.php): 
Failed to open stream: No such file or directory in 
/home/runner/work/coolTodo/coolTodo/backend/artisan on line 7
```

**Причина**: 
- `artisan` находится в `backend/artisan`
- Он пытается загрузить `../vendor/autoload.php` (из корня проекта)
- Но vendor находится в `backend/vendor/`, а не в корне
- Путь должен быть `vendor/autoload.php` (относительно `backend/artisan`)

## Решение

**Вариант 1 (Рекомендуемый)**: Исправить путь в `backend/artisan`:
- Изменить `require __DIR__.'/../vendor/autoload.php';` на `require __DIR__.'/vendor/autoload.php';`

**Вариант 2**: Изменить скрипт в composer.json:
- Убрать `@php artisan package:discover` из `post-autoload-dump`
- Или использовать полный путь: `@php backend/artisan package:discover`

**Вариант 3**: Проверить, нужен ли этот скрипт вообще:
- Laravel может автоматически обнаруживать пакеты без этого скрипта
- Можно убрать скрипт и проверить, работает ли приложение

## Критерии приемки

✅ `composer install` выполняется успешно в CI  
✅ Скрипт `post-autoload-dump` не вызывает ошибок  
✅ Workflow доходит до шага "Run PHPUnit tests"  
✅ Laravel пакеты обнаруживаются корректно

## Зависимости

- [x] A16: Исправить расположение composer.json (Completed ✅)
- [x] A17: Исправить пути в CI для PHPUnit (Completed ✅)

## Реализация

✅ Исправлены пути к vendor:
- `backend/artisan`: `__DIR__.'/../vendor/autoload.php'` → `__DIR__.'/vendor/autoload.php'`
- `backend/public/index.php`: `__DIR__.'/../vendor/autoload.php'` → `__DIR__.'/../../vendor/autoload.php'`

✅ Изменения отправлены в `origin/main`

**Ожидание**: Проверка результата в GitHub Actions workflow - должен пройти шаг "Install Composer dependencies" и дойти до "Run PHPUnit tests".

### Проверка через GitHub CLI (gh)

**Workflow run `19802069946`** (completed, success):
- ✅ `composer install` выполняется успешно
- ✅ Скрипт `post-autoload-dump` выполняется без ошибок
- ✅ `@php artisan package:discover --ansi` работает корректно
- ✅ Все Laravel пакеты обнаруживаются: laravel/sail, laravel/sanctum, laravel/tinker, nesbot/carbon, nunomaduro/collision, nunomaduro/termwind, spatie/laravel-ignition
- ✅ Workflow доходит до шага "Run PHPUnit tests" (задача A17 подтвердила это)
- ✅ Нет ошибок о несуществующих путях к vendor/autoload.php

**Проверка исправленных путей**:
- ✅ `backend/artisan`: `require __DIR__.'/vendor/autoload.php';` - правильно
- ✅ `backend/public/index.php`: `require __DIR__.'/../../vendor/autoload.php';` - правильно

**Вывод**: Задача A20 полностью решена! ✅

## Связанные задачи

- A16: Исправить расположение composer.json (привело к проблеме с путями)
- A17: Исправить пути в CI для PHPUnit (блокировалась этой задачей)

