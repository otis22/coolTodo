# Task A23_Fix_Composer_Install_Permissions

**Статус**: Completed ✅

## Краткое описание

При выполнении `composer install` в Docker контейнере возникает ошибка создания директории `/var/www/html/vendor/composer`. Директория `vendor` существует, но принадлежит root, что блокирует установку зависимостей от имени пользователя хоста.


