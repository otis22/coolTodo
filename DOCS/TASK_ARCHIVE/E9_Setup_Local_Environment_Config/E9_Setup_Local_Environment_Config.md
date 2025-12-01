# Task E9: Настроить локальное окружение (.env)

**Статус**: Completed ✅  
**Приоритет**: High  
**Оценка**: 0.25 дня

## Описание

Локальное окружение не работает из-за отсутствия файла `.env`. Laravel не может получить конфигурацию (APP_ENV, DB_HOST и другие переменные), что приводит к 500 ошибкам при обращении к API.

## Проблема

**Текущая ситуация**:
- Файл `backend/.env` отсутствует
- Файл `backend/.env.example` отсутствует
- Laravel не может получить конфигурацию из переменных окружения
- API возвращает 500 ошибку: `SQLSTATE[HY000] [2002] Connection refused`
- Переменные окружения не установлены в контейнере (APP_ENV, DB_HOST не установлены)

**Ошибка в логах**:
```
SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, SQL: select * from `todos`)
```

**Причина**:
- Laravel пытается подключиться к БД, но не знает параметры подключения (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Без .env файла Laravel использует значения по умолчанию из config/database.php, которые не подходят для Docker окружения

## Зависимости

- [x] A3: Настроить Docker окружение (Completed ✅)
- [x] E6: Исправить перезапуск app контейнера (Completed ✅)

## Требования

### Цель

Создать `.env.example` и настроить локальное окружение для работы с Docker.

### Критерии приемки

✅ Файл `backend/.env.example` создан с правильными значениями для Docker  
✅ Файл `backend/.env` создается из `.env.example` при первом запуске (или документирован процесс)  
✅ Laravel может подключиться к MySQL через Docker  
✅ API endpoints работают без 500 ошибок  
✅ Переменные окружения правильно настроены для dev окружения

## Системное решение

### Анализ конфигурации

**Docker-compose MySQL сервис**:
```yaml
mysql:
  environment:
    MYSQL_DATABASE: cooltodo
    MYSQL_ROOT_PASSWORD: root
    MYSQL_PASSWORD: root
    MYSQL_USER: cooltodo
```

**Требуемые переменные для Laravel**:
- `DB_CONNECTION=mysql`
- `DB_HOST=mysql` (имя сервиса в docker-compose)
- `DB_PORT=3306`
- `DB_DATABASE=cooltodo`
- `DB_USERNAME=cooltodo`
- `DB_PASSWORD=root`

**Другие необходимые переменные**:
- `APP_ENV=local` (для разработки)
- `APP_DEBUG=true` (для отладки)
- `APP_KEY=` (будет сгенерирован через `php artisan key:generate`)
- `CORS_ALLOWED_ORIGINS=http://localhost:5173` (для CORS)

### Подход

1. Создать `backend/.env.example` с правильными значениями для Docker
2. Документировать процесс создания `.env` из `.env.example`
3. Убедиться, что `.env` в `.gitignore`
4. Возможно, добавить автоматическое создание `.env` в скрипт инициализации

## План выполнения

### Шаг 1: Создание .env.example
- [ ] Создать `backend/.env.example` с базовыми переменными
- [ ] Настроить переменные для Docker окружения (DB_HOST=mysql, etc.)
- [ ] Добавить комментарии для понимания каждой переменной
- [ ] Убедиться, что APP_KEY пустой (будет сгенерирован)

### Шаг 2: Документация
- [ ] Документировать процесс создания `.env` из `.env.example`
- [ ] Добавить инструкции в README или DEVELOPMENT.md
- [ ] Убедиться, что `.env` в `.gitignore`

### Шаг 3: Тестирование
- [ ] Создать `.env` из `.env.example`
- [ ] Выполнить `php artisan key:generate`
- [ ] Проверить подключение к БД
- [ ] Выполнить миграции
- [ ] Проверить работу API endpoints

### Шаг 4: Автоматизация (опционально)
- [ ] Рассмотреть добавление скрипта инициализации
- [ ] Или добавить проверку наличия `.env` в `./dev` скрипт

## Прогресс

Создано: 2025-01-27

