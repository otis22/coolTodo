# Task A25: Исправить пути к конфигурационным файлам в скрипте dev

**Статус**: Completed ✅  
**Приоритет**: High  
**Оценка**: 0.25 дня

## Описание

Скрипт `dev` запускает PHPUnit и PHPStan без указания конфигурационных файлов, что приводит к ошибкам. В CI workflow эти инструменты запускаются с явным указанием конфигурации (`--configuration=../phpunit.xml` и `--configuration=../phpstan.neon`), но локально скрипт `dev` этого не делает.

## Проблема

**Текущая конфигурация скрипта `dev`**:
```bash
phpunit)
    shift
    exec_app vendor/bin/phpunit "$@"
    ;;

phpstan)
    shift
    exec_tools phpstan analyse "$@"
    ;;
```

**Проблемы**:
1. **PHPUnit**: Не указан `--configuration=../phpunit.xml`, поэтому PHPUnit ищет конфигурацию в текущей директории (`/var/www/html`), но `phpunit.xml` находится в корне проекта
2. **PHPStan**: Не указан `--configuration=../phpstan.neon`, поэтому PHPStan не знает, какие файлы анализировать и где искать конфигурацию

**Сравнение с CI workflow**:
- CI: `vendor/bin/phpunit --configuration=../phpunit.xml` (из `working-directory: backend`)
- CI: `vendor/bin/phpstan analyse --configuration=../phpstan.neon` (из `working-directory: backend`)
- Локально: `vendor/bin/phpunit` (без конфигурации)
- Локально: `phpstan analyse` (без конфигурации)

## Причина

После исправления путей в задачах A17 и A21 для CI workflow, скрипт `dev` не был обновлен для использования тех же путей локально. Конфигурационные файлы находятся в корне проекта, а инструменты запускаются из `backend/` (в Docker контейнере это `/var/www/html`).

## Решение

**Обновить скрипт `dev`** для указания конфигурационных файлов:

```bash
phpunit)
    shift
    exec_app vendor/bin/phpunit --configuration=../phpunit.xml "$@"
    ;;

phpstan)
    shift
    exec_tools phpstan analyse --configuration=../phpstan.neon "$@"
    ;;
```

**Обоснование**:
- Контейнеры `app` и `tools` монтируют `./backend` в `/var/www/html`
- Конфигурационные файлы находятся в корне проекта
- Относительный путь `../phpunit.xml` и `../phpstan.neon` будет указывать на корень проекта из `/var/www/html`

## Критерии приемки

✅ `./dev phpunit` работает с правильной конфигурацией  
✅ `./dev phpstan` работает с правильной конфигурацией  
✅ PHPUnit находит все тесты и исходный код  
✅ PHPStan анализирует все файлы из `backend/src` и `tests`  
✅ Поведение локально соответствует CI workflow

## Зависимости

- [x] A13: Создать helper-скрипты для разработки (Completed ✅)
- [x] A17: Исправить пути в CI для PHPUnit (Completed ✅)
- [x] A21: Исправить путь к larastan extension.neon в phpstan.neon (Completed ✅)

## Реализация

### Выполненные шаги

#### 1. Обновлен скрипт `dev`

✅ **PHPUnit**: Добавлен `--configuration=../phpunit.xml`
```bash
phpunit)
    shift
    exec_app vendor/bin/phpunit --configuration=../phpunit.xml "$@"
    ;;
```

✅ **PHPStan**: Добавлен `--configuration=../phpstan.neon`
```bash
phpstan)
    shift
    exec_tools phpstan analyse --configuration=../phpstan.neon "$@"
    ;;
```

#### 2. Обновлен docker-compose.yml

✅ **Проблема**: Конфигурационные файлы находятся в корне проекта, а контейнеры монтируют только `./backend` в `/var/www/html`. Путь `../phpunit.xml` и `../phpstan.neon` не работал, так как файлы были вне монтированного volume.

✅ **Решение**: Добавлено монтирование конфигурационных файлов в оба контейнера:

**Сервис `app`**:
```yaml
volumes:
  - ./backend:/var/www/html:cached
  - ./backend/vendor:/var/www/html/vendor
  - ./phpunit.xml:/var/www/html/../phpunit.xml:ro
```

**Сервис `tools`**:
```yaml
volumes:
  - ./backend:/var/www/html:cached
  - ./backend/vendor:/var/www/html/vendor
  - ./phpstan.neon:/var/www/html/../phpstan.neon:ro
  - ./phpunit.xml:/var/www/html/../phpunit.xml:ro
```

**Примечание**: Использован флаг `:ro` (read-only) для конфигурационных файлов, так как они не должны изменяться в контейнере.

#### 3. Проверка доступности конфигурационных файлов

✅ Проверено:
- `phpunit.xml` доступен из контейнера `app` по пути `/var/www/html/../phpunit.xml`
- `phpstan.neon` доступен из контейнера `tools` по пути `/var/www/html/../phpstan.neon`

### Созданные/обновленные файлы

- ✅ `dev` - обновлен для использования конфигурационных файлов
- ✅ `docker-compose.yml` - добавлено монтирование конфигурационных файлов

## Проблемы и решения

### Проблема 1: Конфигурационные файлы недоступны из контейнера

**Симптомы**: 
- `phpunit.xml недоступен` при проверке из контейнера
- `phpstan.neon недоступен` при проверке из контейнера

**Причина**: Конфигурационные файлы находятся в корне проекта, а контейнеры монтируют только `./backend` в `/var/www/html`. Путь `../phpunit.xml` указывал на директорию вне монтированного volume.

**Решение**: Добавлено монтирование конфигурационных файлов в `docker-compose.yml`:
- `./phpunit.xml:/var/www/html/../phpunit.xml:ro` для контейнера `app`
- `./phpstan.neon:/var/www/html/../phpstan.neon:ro` и `./phpunit.xml:/var/www/html/../phpunit.xml:ro` для контейнера `tools`

**Реализовано**: Конфигурационные файлы теперь доступны из контейнеров.

## Связанные задачи

- A24: Исправить пути в конфигурации PHPStan (эта задача решает проблему на уровне скрипта)
- A23: Исправить права доступа для composer install (нужно для установки зависимостей)

