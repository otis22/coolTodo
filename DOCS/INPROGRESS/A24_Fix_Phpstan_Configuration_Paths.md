# Task A24: Исправить пути в конфигурации PHPStan

**Статус**: Completed ✅ (решено через A28)  
**Приоритет**: Medium  
**Оценка**: 0.25 дня

## Описание

Конфигурация PHPStan (`phpstan.neon`) использует пути относительно корня проекта (`backend/src`, `tests`), но при запуске из Docker контейнера tools эти пути неверны, так как контейнер монтирует `./backend` в `/var/www/html`.

## Проблема

**Текущая конфигурация** (`phpstan.neon`):
```yaml
parameters:
    paths:
        - backend/src    # ← неверный путь в контейнере
        - tests          # ← неверный путь в контейнере
```

**Симптомы**:
- `./dev phpstan` → `At least one path must be specified to analyse`
- `phpstan analyse backend/src tests` → `Path /var/www/html/backend/src does not exist`
- PHPStan не может найти файлы для анализа

**Причина**:
- Контейнер `tools` монтирует `./backend` в `/var/www/html`
- В контейнере структура: `/var/www/html/src`, `/var/www/html/../tests`
- Конфигурация использует пути `backend/src` и `tests`, которые не существуют в контейнере

## Решение

**Вариант 1 (Рекомендуемый)**: Исправить пути в `phpstan.neon` для работы в контейнере
- Изменить `backend/src` → `src`
- Изменить `tests` → `../tests` (или использовать абсолютный путь)
- Обновить путь к `larastan/extension.neon`: `backend/vendor` → `vendor`

**Вариант 2**: Создать отдельную конфигурацию для контейнера
- `phpstan.docker.neon` с путями для контейнера
- Обновить скрипт `dev` для использования правильной конфигурации

**Вариант 3**: Использовать относительные пути от рабочей директории
- Настроить рабочую директорию в контейнере
- Использовать относительные пути в конфигурации

## Критерии приемки

✅ `./dev phpstan` работает успешно  
✅ PHPStan анализирует все файлы в `backend/src` и `tests`  
✅ Конфигурация работает как в контейнере, так и локально (если возможно)  
✅ CI/CD пайплайн работает корректно

## Зависимости

- [x] A11: Создать Dockerfile.tools для инструментов анализа (Completed ✅)
- [x] A13: Создать helper-скрипты для разработки (Completed ✅)

## Изменения из CI workflow (задачи A17, A21)

В задачах A17 и A21 были исправлены пути для работы в CI:

**A21 - Исправлен путь к larastan extension**:
- ✅ `phpstan.neon`: `backend/vendor/larastan/larastan/extension.neon` (уже исправлено)
- ✅ Пути в `phpstan.neon`: `backend/src` и `tests` (относительно корня проекта)

**Проблема для локального окружения**:
- В CI PHPStan запускается из `working-directory: backend` с `--configuration=../phpstan.neon`
- Пути в `phpstan.neon` разрешаются относительно расположения файла (корень проекта)
- В Docker контейнере `tools` монтируется `./backend` в `/var/www/html`
- Конфигурация `phpstan.neon` находится в корне проекта (не монтируется в контейнер)
- Скрипт `dev` запускает `phpstan analyse` без указания конфигурации и путей

**Решение**:

**Вариант 1 (Рекомендуемый)**: Обновить скрипт `dev` для указания конфигурации и путей:
```bash
phpstan)
    shift
    exec_tools phpstan analyse --configuration=../phpstan.neon "$@"
    ;;
```

**Вариант 2**: Монтировать корень проекта в контейнер tools:
```yaml
tools:
  volumes:
    - ./backend:/var/www/html:cached
    - ./backend/vendor:/var/www/html/vendor
    - ./phpstan.neon:/var/www/html/../phpstan.neon  # Монтировать конфигурацию
    - ./phpunit.xml:/var/www/html/../phpunit.xml     # Для PHPUnit тоже
```

**Вариант 3**: Создать конфигурацию для Docker с путями относительно `/var/www/html`:
- Создать `phpstan.docker.neon` с путями `src` и `../tests`
- Обновить скрипт `dev` для использования этой конфигурации

**Также нужно обновить PHPUnit в скрипте `dev`**:
```bash
phpunit)
    shift
    exec_app vendor/bin/phpunit --configuration=../phpunit.xml "$@"
    ;;
```

**Аналогичная проблема с PHPUnit** (решенная в A17):
- `phpunit.xml` использует пути относительно корня: `backend/vendor/autoload.php`, `backend/src`, `tests/Unit`
- В CI PHPUnit запускается из `backend/` с `--configuration=../phpunit.xml`
- Локально нужно запускать аналогично: из `backend/` с указанием конфигурации

## Проблема с текущим решением

**Временное решение** (создано в процессе выполнения):
- Создан `phpstan.docker.neon` с путями для Docker
- Скрипт `dev` использует `phpstan.docker.neon`
- Это работает локально, но создает дублирование конфигурации

**Проблемы**:
- Дублирование конфигурации (`phpstan.neon` и `phpstan.docker.neon`)
- Риск рассинхронизации между конфигурациями
- Не является системным решением

**Решение**: Задача решена через A28 - унификация структуры Docker. PHPStan теперь работает корректно в обоих окружениях.

## Связанные задачи

- A17: Исправить пути в CI для PHPUnit (Completed ✅ - аналогичная проблема решена)
- A21: Исправить путь к larastan extension.neon в phpstan.neon (Completed ✅ - путь исправлен для CI)
- A23: Исправить права доступа для composer install (Completed ✅)
- A25: Исправить пути к конфигурационным файлам в скрипте dev (Completed ✅ - но создало проблему дублирования)
- A26: Исправить пути PHPStan системно (без поломки CI) (новая задача для системного решения)
- B1: Создать модели данных (требует проверки PHPStan level 9)

