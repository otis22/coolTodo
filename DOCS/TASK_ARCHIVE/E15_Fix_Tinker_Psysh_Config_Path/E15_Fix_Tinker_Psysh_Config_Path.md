# Task E15: Исправить проблему с tinker (psysh config path)

**Статус**: Completed ✅  
**Начало**: 2025-12-01  
**Завершение**: 2025-12-01  
**Приоритет**: Medium  
**Оценка**: 0.25 дня

## Описание

Tinker (Laravel REPL) не может запуститься из-за проблемы с путями конфигурации psysh. Psysh пытается создать директорию `/.config/psysh` в корне файловой системы, но не имеет прав на запись.

## Проблема

**Текущая ситуация**:
- Команда `./dev artisan tinker --execute="..."` возвращает ошибку:
  ```
  ErrorException: Writing to directory /.config/psysh is not allowed.
  ```
- Переменная окружения `HOME=/` установлена в корень файловой системы
- Psysh пытается создать конфигурационную директорию в `$HOME/.config/psysh`
- Нет прав на запись в корень файловой системы (`/`)

**Логи**:
```
ErrorException 

Writing to directory /.config/psysh is not allowed.

at vendor/psy/psysh/src/ConfigPaths.php:395
```

**Проверка**:
```bash
$ docker compose exec tools env | grep HOME
HOME=/
```

## Зависимости

- [x] E6: Исправить перезапуск app контейнера (Completed ✅)
- [x] E9: Настроить локальное окружение (.env) (Completed ✅)

## Требования

### Цель

Настроить правильную переменную окружения `HOME` для контейнеров, чтобы psysh мог создавать конфигурационные файлы в правильном месте.

### Критерии приемки

✅ Tinker запускается без ошибок  
✅ `./dev artisan tinker --execute="..."` работает  
✅ Psysh создает конфигурацию в правильном месте (например, `/var/www/project/backend/.config/psysh`)  
✅ Нет ошибок "Writing to directory /.config/psysh is not allowed"  
✅ PHP-FPM контейнер работает стабильно  
✅ Все тесты проходят

## План выполнения

### Шаг 1: Анализ проблемы
- [x] Проверить текущее значение `HOME` в контейнерах ✅
- [x] Проверить, где psysh пытается создать конфигурацию ✅
- [x] Определить правильное значение `HOME` для контейнеров ✅

### Шаг 2: Исправление
- [x] Добавить переменную окружения `HOME` в `docker-compose.yml` для сервисов `app` и `tools` ✅
- [x] Установить `HOME=/var/www/project/backend` ✅
- [x] Проверить, что директория существует и доступна для записи ✅
- [ ] Обновить документацию, если необходимо

### Шаг 3: Проверка
- [x] Запустить `./dev artisan tinker --execute="echo 'test';"` ✅
- [x] Убедиться, что нет ошибок ✅
- [x] Проверить, что конфигурация создается в правильном месте ✅
- [x] Проверить, что PHP-FPM все еще работает ✅

## Результаты проверки

✅ **HOME установлен правильно**: `/var/www/project/backend`  
✅ **Tinker работает**: `php artisan tinker --execute="..."` выполняется без ошибок  
✅ **Конфигурация psysh создана**: `/var/www/project/backend/.config/psysh`  
✅ **PHP-FPM работает**: контейнер запущен и доступен  
✅ **API доступен**: `http://localhost:8080/api/todos` возвращает данные  
✅ **Через ./dev скрипт**: `./dev artisan tinker` работает корректно

**Задача выполнена успешно!** ✅

## Выполненные изменения

### Обновлен docker-compose.yml

**Добавлено для сервиса `app`**:
```yaml
environment:
  - COMPOSER_HOME=/var/www/project/backend/.composer
  - HOME=/var/www/project/backend  # Добавлено
  - XDEBUG_MODE=develop,coverage
```

**Добавлено для сервиса `tools`**:
```yaml
environment:
  - HOME=/var/www/project/backend  # Добавлено
```

### Проверка совместимости с CI

✅ **CI workflow не использует docker-compose** - изменения безопасны:
- CI использует нативный PHP через `shivammathur/setup-php@v2`
- CI использует GitHub Actions services для MySQL
- Изменения в docker-compose.yml не влияют на CI workflow

## Технические детали

### Текущая конфигурация

**docker-compose.yml**:
```yaml
services:
  app:
    environment:
      - COMPOSER_HOME=/var/www/project/backend/.composer
      # HOME не установлен, по умолчанию = /
  
  tools:
    environment:
      # HOME не установлен, по умолчанию = /
```

### Рекомендуемая конфигурация

**Вариант 1: Установить HOME в рабочую директорию** (проще):
```yaml
services:
  app:
    environment:
      - COMPOSER_HOME=/var/www/project/backend/.composer
      - HOME=/var/www/project/backend
  
  tools:
    environment:
      - HOME=/var/www/project/backend
```

**Вариант 2: Создать пользователя и домашнюю директорию** (более правильно):
- Создать пользователя в Dockerfile
- Установить `HOME=/home/app` или подобное
- Создать директорию с правильными правами

### Альтернативные решения

1. **Использовать переменную окружения PSYSH_CONFIG_DIR**:
   ```yaml
   environment:
     - PSYSH_CONFIG_DIR=/var/www/project/backend/.config/psysh
   ```

2. **Создать директорию вручную в Dockerfile**:
   ```dockerfile
   RUN mkdir -p /var/www/project/backend/.config/psysh \
       && chown -R 1000:1000 /var/www/project/backend/.config
   ```

3. **Использовать --no-interaction флаг** (не решает проблему полностью)

## Прогресс

Создано: 2025-12-01

### Выполненные действия

1. **Анализ проблемы** ✅
   - Проверено значение `HOME=/` в контейнерах
   - Выявлена причина: psysh пытается создать `/.config/psysh`
   - Определено решение: установить `HOME=/var/www/project/backend`

### Следующие шаги

- [ ] Добавить `HOME` в environment для `app` и `tools`
- [ ] Проверить работу tinker
- [ ] Убедиться, что конфигурация создается в правильном месте

## Примечания

- Это не критичная проблема, но мешает использованию tinker для отладки
- Решение простое - добавить переменную окружения `HOME`
- Приоритет Medium, так как не влияет на основную функциональность, но мешает разработке

