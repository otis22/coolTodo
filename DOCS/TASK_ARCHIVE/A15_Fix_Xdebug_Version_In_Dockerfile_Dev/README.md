# Task A15_Fix_Xdebug_Version_In_Dockerfile_Dev

**Статус**: Completed ✅

## Краткое описание

В `Dockerfile.dev` используется `pecl install xdebug` без указания версии, что может привести к установке версии 4.x или выше при обновлении PECL. Это нарушает требование задачи A9 о фиксации версии Xdebug 3.x.


