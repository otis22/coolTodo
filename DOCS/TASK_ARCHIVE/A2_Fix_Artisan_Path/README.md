# Task A2-Fix-Artisan: Исправление пути к artisan в CI

## Summary

Исправлена ошибка в CI пайплайне, связанная с неправильными путями к файлу `artisan` в composer скриптах. Все скрипты обновлены для работы с нестандартной структурой проекта (Laravel в `backend/` директории).

## Source Requirements

- Ошибка CI пайплайна: `Could not open input file: artisan`
- `DOCS/INPROGRESS/A2_Fix_Artisan_Path.md` - исходная задача

## Objectives

1. Исправить пути к `artisan` в composer.json скриптах
2. Создать файл `backend/artisan` если отсутствует
3. Обеспечить корректную работу composer скриптов в CI

## Dependencies

- [x] A2: Настроить CI пайплайн (Completed ✅)
- [x] A2-Fix: Устранение ошибок CI пайплайна (Completed ✅)

## In-Scope / Out of Scope

**In-Scope**:
- Обновление путей в composer.json скриптах
- Создание файла backend/artisan
- Исправление всех composer скриптов для работы с backend/ директорией

**Out of Scope**:
- Изменение структуры проекта
- Исправление других путей в workflow (это задача A4)

## Acceptance Criteria

✅ Composer успешно выполняет post-autoload-dump скрипты
✅ CI пайплайн проходит шаг установки зависимостей без ошибок
✅ Файл `artisan` доступен по пути `backend/artisan`
✅ Все скрипты Composer используют правильные пути

## Implementation Notes

### Проблема

CI пайплайн падал с ошибкой:
```
Could not open input file: artisan
Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1
```

### Решение

Обновлены все скрипты в `composer.json`:
- `post-autoload-dump`: `@php artisan` → `@php backend/artisan`
- `post-update-cmd`: `@php artisan` → `@php backend/artisan`
- `post-create-project-cmd`: `@php artisan` → `@php backend/artisan`
- `post-root-package-install`: обновлены пути для `.env` файлов

Создан файл `backend/artisan` с правильной структурой для Laravel 11.

### Измененные файлы

- `composer.json` - обновлены пути в скриптах
- `backend/artisan` - создан файл

## Lessons Learned

1. **Нестандартная структура**: Проекты с нестандартной структурой требуют особого внимания к путям во всех конфигурационных файлах
2. **Composer скрипты**: Composer выполняет скрипты из корня проекта, поэтому нужно явно указывать пути к файлам в поддиректориях
3. **Раннее обнаружение**: CI пайплайн помог быстро обнаружить проблему с путями

## Immediate Next Steps

Следующий шаг: исправить пути к `.env` файлу в CI workflow (задача A4).

