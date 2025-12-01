# Task A25_Fix_Dev_Script_Configuration_Paths

**Статус**: Completed ✅

## Краткое описание

Скрипт `dev` запускает PHPUnit и PHPStan без указания конфигурационных файлов, что приводит к ошибкам. В CI workflow эти инструменты запускаются с явным указанием конфигурации (`--configuration=../phpunit.xml` и `--configuration=../phpstan.neon`), но локально скрипт `dev` этого не делает.


