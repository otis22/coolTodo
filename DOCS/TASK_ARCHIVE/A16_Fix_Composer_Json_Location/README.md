# Task A16_Fix_Composer_Json_Location

**Статус**: Completed ✅

## Краткое описание

`composer.json` находится в корне проекта, а Docker контейнер `app` монтирует `./backend` в `/var/www/html`. Это приводит к тому, что Composer не может найти `composer.json` внутри контейнера, что блокирует выполнение команд `composer install`, `composer update` и других.


