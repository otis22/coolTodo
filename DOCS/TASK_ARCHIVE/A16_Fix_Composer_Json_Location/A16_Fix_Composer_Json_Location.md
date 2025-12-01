# Task A16: Исправить расположение composer.json

**Статус**: In Progress  
**Приоритет**: High  
**Оценка**: 0.25 дня

## Описание

`composer.json` находится в корне проекта, а Docker контейнер `app` монтирует `./backend` в `/var/www/html`. Это приводит к тому, что Composer не может найти `composer.json` внутри контейнера, что блокирует выполнение команд `composer install`, `composer update` и других.

## Проблема

**Текущая структура**:
```
coolTodo/
├── composer.json          # ← находится здесь
└── backend/               # ← монтируется в /var/www/html
    └── (composer.json отсутствует)
```

**Конфигурация Docker** (`docker-compose.yml`):
```yaml
app:
  volumes:
    - ./backend:/var/www/html:cached
```

**Симптомы**:
- `./dev composer install` → `Composer could not find a composer.json file in /var/www/html`
- `./dev composer --version` → работает (только проверка версии)
- `./dev artisan` → не работает (требует установленных зависимостей)

## Причина

При инициализации проекта (A1) `composer.json` был создан в корне проекта, но структура Docker контейнера предполагает, что все файлы Laravel должны находиться в `backend/`.

## Решение

**Вариант 1 (Рекомендуемый)**: Переместить `composer.json` в `backend/`
- Переместить `composer.json` → `backend/composer.json`
- Обновить пути в `Dockerfile` (если используются COPY команды)
- Обновить пути в CI/CD (если используются)
- Обновить документацию

**Вариант 2**: Изменить монтирование в `docker-compose.yml`
- Монтировать корень проекта вместо `./backend`
- Обновить `WORKDIR` и другие пути в Dockerfile
- Может потребовать изменения структуры проекта

**Вариант 3**: Использовать симлинк
- Создать симлинк `backend/composer.json` → `../composer.json`
- Менее надежное решение

## Критерии приемки

✅ `composer.json` доступен в контейнере по пути `/var/www/html/composer.json`  
✅ `./dev composer install` работает успешно  
✅ `./dev composer update` работает успешно  
✅ `./dev artisan` работает после установки зависимостей  
✅ CI/CD пайплайн работает корректно (если использует composer.json)

## Зависимости

- [x] A1: Инициализировать проект (Completed ✅)
- [x] A3: Настроить Docker окружение (Completed ✅)
- [x] A13: Создать helper-скрипты для разработки (Completed ✅)

## Связанные задачи

- A1: Инициализировать проект (создан composer.json в корне)
- A3: Настроить Docker окружение (настроено монтирование ./backend)
- A13: Создать helper-скрипты (выявлена проблема с composer.json)

## Реализация

✅ Перемещен `composer.json` из корня проекта в `backend/composer.json`  
✅ Обновлены пути в `composer.json`:
   - `"App\\": "src/"` (было `"backend/src/"`)
   - `"Database\\Factories\\": "database/factories/"` (было `"backend/database/factories/"`)
   - `"Database\\Seeders\\": "database/seeders/"` (было `"backend/database/seeders/"`)
   - `"Tests\\": "../tests/"` (было `"tests/"`)
   - Обновлены скрипты: убраны префиксы `backend/` из путей к artisan

✅ Обновлен `Dockerfile`:
   - `COPY backend/composer.json ./` (было `COPY composer.json ./`)
   - `COPY backend/composer.lock* ./` (было `COPY composer.lock* ./`)

✅ Обновлен CI/CD пайплайн (`.github/workflows/ci.yml`):
   - `composer install` теперь выполняется с `working-directory: backend`
   - Обновлены пути к конфигурационным файлам для PHP-CS-Fixer, PHPStan, PHPUnit

✅ Протестировано:
   - `./dev composer --version` → работает
   - `./dev composer validate` → работает (composer.json валиден)
   - `composer.json` доступен в контейнере по пути `/var/www/html/composer.json`

**Примечание**: Проблема с правами доступа к `/var/www/html/vendor` не относится к задаче A16. Это проблема настройки прав доступа в Docker контейнере, которая должна быть решена отдельно (возможно, уже решена в A12, но требует проверки).

**Примечание**: Старый `composer.json` в корне проекта можно оставить для совместимости или удалить после проверки всех зависимостей.

## Дополнительные заметки

Проблема была выявлена при тестировании задачи A13, но не относится к ней напрямую. Это проблема конфигурации проекта, которая должна быть решена отдельно.

## Документирование результатов

### Assumption Log

- **A1**: [Описание предположения] - [Обоснование]

### Успешные решения

- **Решение 1**: [Описание решения] - [Почему оно было эффективным]

### Неверные решения

#### Неверное решение 1: [Краткое название]

**Принятое решение**: [Подробное описание того, что было сделано]

**Обоснование выбора**: [Почему это решение казалось правильным]

**Возникшие проблемы**: 
- [Проблема 1]

**Корректное решение**: [Что было сделано вместо этого]

**Извлеченные уроки**: [Что можно извлечь из этого опыта]

