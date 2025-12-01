# Task E15: Исправить проблему с tinker (psysh config path)

**Статус**: Completed ✅  
**Завершено**: 2025-12-01

## Краткое описание

Исправлена проблема с tinker (Laravel REPL), который не мог запуститься из-за неправильной переменной окружения `HOME=/` в контейнерах. Psysh пытался создать конфигурационную директорию в корне файловой системы без прав на запись.

## Решение

Добавлена переменная окружения `HOME=/var/www/project/backend` для сервисов `app` и `tools` в `docker-compose.yml`.

## Результаты

✅ Tinker работает без ошибок  
✅ Psysh создает конфигурацию в правильном месте  
✅ CI workflow не сломан (CI не использует docker-compose)  
✅ Все тесты проходят

## Файлы

- `DOCS/TASK_ARCHIVE/E15_Fix_Tinker_Psysh_Config_Path/E15_Fix_Tinker_Psysh_Config_Path.md` - полная документация задачи

