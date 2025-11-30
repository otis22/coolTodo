# Task A3-Fix-CI: Исправление ошибок в GitHub Actions CI workflow

**Статус**: Open (готово к выполнению)
**Начало**: 2025-11-29
**Приоритет**: High
**Оценка**: 1 день

## Описание

Исправить ошибки в GitHub Actions CI workflow, которые приводят к неудачному завершению пайплайна. Проблемы связаны с нестандартной структурой проекта (Laravel в `backend/` директории) и неправильными путями в скриптах.

## Проблемы

### Проблема 1: Ошибка при выполнении post-autoload-dump скрипта

**Ошибка**:
```
Could not open input file: artisan
Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1
```

**Причина**: 
- В `composer.json` скрипты используют `@php artisan`, но файл `artisan` должен находиться в `backend/artisan`
- Composer выполняет скрипты из корня проекта, где файл `artisan` отсутствует
- Проект использует нестандартную структуру (Laravel в `backend/`, а не в корне)

### Проблема 2: Неправильное расположение .env файла

**Проблема**:
- В workflow `.env` создается в корне проекта (строка 41)
- Laravel ожидает `.env` файл в `backend/` директории
- Хотя есть шаг копирования `.env` в `backend/` (строка 61), это происходит после `composer install`, который может требовать `.env` файл

### Проблема 3: Неправильные пути в composer.json скриптах

**Проблема**:
- Все скрипты в `composer.json` используют `@php artisan` без указания пути `backend/artisan`
- Скрипты `post-update-cmd` и `post-create-project-cmd` также требуют исправления путей

### Проблема 4: Отсутствует package-lock.json для npm ci

**Ошибка**:
```
npm error The `npm ci` command can only install with an existing package-lock.json or
npm error npm-shrinkwrap.json with lockfileVersion >= 1.
```

**Причина**:
- В CI workflow используется команда `npm ci` (строка 50), которая требует наличия `package-lock.json`
- В проекте отсутствует файл `package-lock.json` в директории `frontend/`
- `npm ci` предназначен для детерминированной установки зависимостей в CI/CD окружениях

**Решение**:
- Создать `package-lock.json` путем выполнения `npm install` в директории `frontend/`
- Закоммитить `package-lock.json` в репозиторий
- Альтернатива: изменить `npm ci` на `npm install` в CI workflow (менее предпочтительно)

## Зависимости

- [x] A2: Настроить CI пайплайн (Completed ✅)
- [x] A2-Fix: Устранение ошибок CI пайплайна (конфликт версий PHPStan) (Completed ✅)
- [ ] A2-Fix-Artisan: Исправление пути к artisan в CI (In Progress)

## План выполнения

### Шаг 1: Исправить пути в composer.json

- [ ] Обновить скрипт `post-autoload-dump` - изменить `@php artisan` на `@php backend/artisan`
- [ ] Обновить скрипт `post-update-cmd` - изменить `@php artisan` на `@php backend/artisan`
- [ ] Обновить скрипт `post-create-project-cmd` - изменить `@php artisan` на `@php backend/artisan`
- [ ] Обновить скрипт `post-root-package-install` - изменить пути для `.env.example` и `.env` на `backend/.env.example` и `backend/.env`

### Шаг 2: Исправить workflow файл

- [ ] Обновить шаг "Copy .env" - создавать `.env` сразу в `backend/` директории
- [ ] Убедиться, что все шаги, требующие `.env`, имеют правильные пути
- [ ] Проверить, что все команды Laravel используют правильные пути к `backend/artisan`

### Шаг 2.1: Создать package-lock.json для npm

- [x] Выполнить `npm install` в директории `frontend/` для создания `package-lock.json`
- [x] Обновить `@vitejs/plugin-vue` до версии `^6.0.0` для совместимости с Vite 7
- [ ] Закоммитить `package-lock.json` и обновленный `package.json` в репозиторий
- [ ] Убедиться, что `npm ci` в CI workflow работает корректно

### Шаг 3: Проверить наличие файла artisan

- [ ] Убедиться, что файл `backend/artisan` существует
- [ ] Если файл отсутствует, создать его или исправить структуру проекта

### Шаг 4: Тестирование

- [ ] Запустить локально `composer install` и проверить, что скрипты выполняются без ошибок
- [ ] Запустить CI пайплайн и убедиться, что все шаги проходят успешно
- [ ] Проверить, что тесты выполняются корректно

## Решение

### Вариант 1: Обновить пути в composer.json (Рекомендуется)

Изменить скрипты в `composer.json`:

```json
"scripts": {
    "post-autoload-dump": [
        "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
        "@php backend/artisan package:discover --ansi"
    ],
    "post-update-cmd": [
        "@php backend/artisan vendor:publish --tag=laravel-assets --ansi --force"
    ],
    "post-root-package-install": [
        "@php -r \"file_exists('backend/.env') || copy('backend/.env.example', 'backend/.env');\""
    ],
    "post-create-project-cmd": [
        "@php backend/artisan key:generate --ansi"
    ]
}
```

### Вариант 2: Обновить workflow для работы с backend/ директорией

Изменить шаг "Copy .env" в workflow:

```yaml
- name: Copy .env
  run: |
    cp backend/.env.example backend/.env || cp .env.example backend/.env
    php -r "file_put_contents('backend/.env', str_replace('DB_DATABASE=cooltodo', 'DB_DATABASE=cooltodo_test', file_get_contents('backend/.env')));"
    php -r "file_put_contents('backend/.env', str_replace('DB_HOST=mysql', 'DB_HOST=127.0.0.1', file_get_contents('backend/.env')));"
```

## Критерии приемки

✅ Composer успешно выполняет все post-autoload-dump скрипты без ошибок
✅ Файл `.env` создается в правильном месте (`backend/.env`)
✅ CI пайплайн проходит шаг установки зависимостей без ошибок
✅ Все шаги CI пайплайна выполняются успешно
✅ Тесты выполняются корректно
✅ Файл `artisan` доступен по пути `backend/artisan`
✅ `package-lock.json` создан и закоммичен в репозиторий
✅ `npm ci` успешно выполняется в CI workflow

## Заметки

Проект использует нестандартную структуру с Laravel в `backend/` директории. Это требует особого внимания к путям в:
- Скриптах Composer (`composer.json`)
- GitHub Actions workflow (`.github/workflows/ci.yml`)
- Конфигурационных файлах (phpunit.xml, phpstan.neon)

## Связанные задачи

- A2-Fix-Artisan: Исправление пути к artisan в CI (связанная задача, может быть объединена)

