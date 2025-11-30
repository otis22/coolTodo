# Task A20: Исправить скрипт post-autoload-dump в composer.json

**Статус**: Open  
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
- [ ] A17: Исправить пути в CI для PHPUnit (блокируется этой задачей)

## Связанные задачи

- A16: Исправить расположение composer.json (привело к проблеме с путями)
- A17: Исправить пути в CI для PHPUnit (блокируется этой задачей)

