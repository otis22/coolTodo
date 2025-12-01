# Task E15: Исправить проблему с tinker (psysh config path)

**Статус**: Open  
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
- [ ] Добавить переменную окружения `HOME` в `docker-compose.yml` для сервисов `app` и `tools`
- [ ] Установить `HOME=/var/www/project/backend` или `HOME=/home/app` (создать пользователя)
- [ ] Проверить, что директория существует и доступна для записи
- [ ] Обновить документацию, если необходимо

### Шаг 3: Проверка
- [ ] Запустить `./dev artisan tinker --execute="echo 'test';"`
- [ ] Убедиться, что нет ошибок
- [ ] Проверить, что конфигурация создается в правильном месте
- [ ] Проверить, что PHP-FPM все еще работает

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

