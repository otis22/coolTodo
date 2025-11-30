# Task A4-Fix-CI-Workflow-Env-Paths: Исправление путей .env в GitHub Actions CI workflow

**Статус**: Open (готово к выполнению)
**Начало**: 2025-01-27
**Приоритет**: High
**Оценка**: 0.5 дня

## Описание

Исправить GitHub Actions CI workflow для корректной работы с нестандартной структурой проекта (Laravel в `backend/` директории). Основная проблема - неправильное создание и использование `.env` файла в workflow.

## Проблемы

### Проблема 1: Неправильное расположение .env файла в workflow

**Проблема**:
- В workflow (строка 41) `.env` создается в корне проекта: `cp .env.example .env`
- Laravel ожидает `.env` файл в `backend/` директории
- Копирование `.env` в `backend/` происходит позже (строка 61), после `composer install`, который может требовать `.env` файл
- Это может приводить к ошибкам при выполнении composer скриптов

**Текущий код в workflow**:
```yaml
- name: Copy .env
  run: |
    cp .env.example .env
    php -r "file_put_contents('.env', str_replace('DB_DATABASE=cooltodo', 'DB_DATABASE=cooltodo_test', file_get_contents('.env')));"
    php -r "file_put_contents('.env', str_replace('DB_HOST=mysql', 'DB_HOST=127.0.0.1', file_get_contents('.env')));"
```

### Проблема 2: Отсутствие .env.example файла

**Проблема**:
- Workflow пытается скопировать `.env.example`, но файл может отсутствовать
- Нужно проверить наличие `.env.example` в `backend/` директории
- Если файл отсутствует, нужно создать его или изменить workflow для создания `.env` без шаблона

### Проблема 3: Дублирование логики создания .env

**Проблема**:
- `.env` создается в корне (строка 41)
- Затем копируется в `backend/` (строка 61)
- Это избыточно и может приводить к рассинхронизации

## Зависимости

- [x] A2: Настроить CI пайплайн (Completed ✅)
- [x] A2-Fix: Устранение ошибок CI пайплайна (Completed ✅)
- [x] A2-Fix-Artisan: Исправление пути к artisan в CI (Completed ✅)
- [ ] A3: Исправление ошибок в GitHub Actions CI workflow (может выполняться параллельно)

## План выполнения

### Шаг 1: Проверить наличие .env.example

- [ ] Проверить наличие `backend/.env.example` файла
- [ ] Если файл отсутствует, создать его с базовой конфигурацией для тестирования
- [ ] Убедиться, что файл содержит необходимые переменные окружения

### Шаг 2: Исправить шаг "Copy .env" в workflow

- [ ] Изменить шаг "Copy .env" для создания `.env` сразу в `backend/` директории
- [ ] Обновить пути в командах замены значений (DB_DATABASE, DB_HOST)
- [ ] Добавить fallback на случай отсутствия `.env.example`
- [ ] Убрать дублирование логики создания `.env`

### Шаг 3: Обновить шаг "Setup Laravel environment"

- [ ] Убрать дублирование логики создания `.env` из шага "Setup Laravel environment"
- [ ] Убедиться, что шаг только генерирует ключ приложения
- [ ] Проверить, что переменные окружения передаются корректно

### Шаг 4: Тестирование

- [ ] Запустить CI пайплайн локально (если возможно) или через GitHub Actions
- [ ] Убедиться, что `.env` создается в правильном месте
- [ ] Проверить, что все шаги workflow выполняются успешно
- [ ] Убедиться, что тесты и миграции работают корректно

## Решение

### Обновить workflow для работы с backend/ директорией

Изменить шаг "Copy .env" в workflow:

```yaml
- name: Copy .env
  run: |
    # Создаем .env в backend/ директории
    if [ -f backend/.env.example ]; then
      cp backend/.env.example backend/.env
    elif [ -f .env.example ]; then
      cp .env.example backend/.env
    else
      # Создаем базовый .env файл если шаблон отсутствует
      cat > backend/.env << EOF
APP_NAME=CoolTodo
APP_ENV=testing
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cooltodo_test
DB_USERNAME=root
DB_PASSWORD=root
EOF
    fi
    # Обновляем значения для тестовой БД
    php -r "file_put_contents('backend/.env', str_replace('DB_DATABASE=cooltodo', 'DB_DATABASE=cooltodo_test', file_get_contents('backend/.env')));"
    php -r "file_put_contents('backend/.env', str_replace('DB_HOST=mysql', 'DB_HOST=127.0.0.1', file_get_contents('backend/.env')));"
```

Также нужно обновить шаг "Setup Laravel environment" для удаления дублирования:

```yaml
- name: Setup Laravel environment
  working-directory: backend
  run: |
    # .env уже создан на предыдущем шаге, просто генерируем ключ
    php artisan key:generate --ansi || echo "Key generation skipped"
```

## Критерии приемки

✅ Файл `.env` создается сразу в `backend/` директории
✅ Workflow не пытается создать `.env` в корне проекта
✅ Все шаги workflow работают с `backend/.env`
✅ CI пайплайн проходит шаг создания `.env` без ошибок
✅ Нет дублирования логики создания `.env`
✅ Если `.env.example` отсутствует, workflow создает базовый `.env` файл
✅ Все шаги CI пайплайна выполняются успешно
✅ Тесты и миграции работают корректно

## Заметки

- Проект использует нестандартную структуру с Laravel в `backend/` директории
- Это требует особого внимания к путям в GitHub Actions workflow
- Важно создать `.env` до выполнения `composer install`, так как некоторые composer скрипты могут требовать его наличия
- Если `.env.example` отсутствует, нужно либо создать его, либо добавить fallback в workflow

## Связанные задачи

- A3: Исправление ошибок в GitHub Actions CI workflow (может выполняться параллельно)
- A2-Fix-Artisan: Исправление пути к artisan в CI (Completed ✅)
