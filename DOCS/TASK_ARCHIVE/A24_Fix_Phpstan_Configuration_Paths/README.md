# Task A24_Fix_Phpstan_Configuration_Paths

**Статус**: Completed ✅

## Краткое описание

Конфигурация PHPStan (`phpstan.neon`) использует пути относительно корня проекта (`backend/src`, `tests`), но при запуске из Docker контейнера tools эти пути неверны, так как контейнер монтирует `./backend` в `/var/www/html`.


