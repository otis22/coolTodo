# Task A27_Fix_PhpCsFixer_Configuration_Paths

**Статус**: Completed ✅

## Краткое описание

PHP-CS-Fixer в CI использует `--config=../.php-cs-fixer.php`, но в локальном окружении (скрипт `dev`) конфигурация не указана, и конфигурационный файл не монтируется в контейнер tools. Нужно исправить это для согласованности с CI.


