# Task A2-Fix-Artisan: Исправление пути к artisan в CI

**Статус**: Completed ✅
**Начало**: 2025-11-29
**Приоритет**: High
**Оценка**: 0.5 дня

## Описание

Исправить ошибку в CI пайплайне, связанную с тем, что Composer не может найти файл `artisan` при выполнении post-autoload-dump скрипта.

## Проблема

CI пайплайн падает с ошибкой:

```
Could not open input file: artisan
Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1
```

**Причина**: 
- Composer выполняет скрипт `@php artisan package:discover --ansi` из корня проекта
- Но файл `artisan` находится в директории `backend/`
- Проект использует нестандартную структуру (Laravel в `backend/`, а не в корне)

## Зависимости

- [x] A2: Настроить CI пайплайн (Completed ✅)
- [x] A2-Fix: Устранение ошибок CI пайплайна (конфликт версий PHPStan) (Completed ✅)

## План выполнения

- [x] Проверить расположение файла `artisan` (должен быть в `backend/artisan`)
- [x] Обновить `composer.json` - изменить пути в скриптах post-autoload-dump
- [x] Создать файл `backend/artisan` (отсутствовал в проекте)
- [x] Обновить все скрипты Composer для работы с правильными путями
- [ ] Запустить CI пайплайн и убедиться, что ошибка исправлена

## Решение

### Вариант 1: Обновить пути в composer.json

Изменить скрипты в `composer.json`:
```json
"post-autoload-dump": [
    "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
    "@php backend/artisan package:discover --ansi"
]
```

### Вариант 2: Создать симлинк или скопировать artisan

Создать `artisan` в корне проекта, который будет указывать на `backend/artisan`.

### Вариант 3: Обновить CI пайплайн

Изменить рабочую директорию в CI пайплайне на `backend/` перед выполнением composer install.

## Критерии приемки

✅ Composer успешно выполняет post-autoload-dump скрипты
✅ CI пайплайн проходит шаг установки зависимостей без ошибок
✅ Файл `artisan` находится и доступен из корня проекта или пути исправлены

## Заметки

Проект использует нестандартную структуру с Laravel в `backend/` директории. Это требует особого внимания к путям в скриптах Composer и CI пайплайне.





