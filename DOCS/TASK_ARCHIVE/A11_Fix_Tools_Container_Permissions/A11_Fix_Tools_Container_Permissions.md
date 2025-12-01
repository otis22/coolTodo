# Task A11-Fix: Исправление прав доступа в tools контейнере

**Статус**: Completed ✅  
**Начало**: 2025-01-27  
**Приоритет**: High  
**Связанная задача**: A11 (Создать Dockerfile.tools для инструментов анализа)

## Описание проблемы

При тестировании tools контейнера обнаружена проблема с правами доступа к инструментам PHPStan и PHP-CS-Fixer. Контейнер запускается от имени пользователя хоста (`user: "${UID:-1000}:${GID:-1000}"`), но инструменты установлены в `/root/.composer/vendor/bin/`, к которому пользователь хоста не имеет доступа.

## Симптомы проблемы

1. **Ошибка Permission denied**:
   ```
   /usr/local/bin/docker-php-entrypoint: 9: exec: phpstan: Permission denied
   /usr/local/bin/docker-php-entrypoint: 9: exec: php-cs-fixer: Permission denied
   ```

2. **Ошибка при доступе к vendor директории**:
   ```
   ls: cannot access '/root/.composer/vendor/bin/': Permission denied
   ```

3. **Ошибка при прямом вызове**:
   ```
   /usr/local/bin/docker-php-entrypoint: 9: exec: /root/.composer/vendor/bin/phpstan: Permission denied
   ```

## Предпринятые попытки решения

### Попытка 1: Копирование скриптов в /usr/local/bin

**Действия**:
- Изменил Dockerfile.tools: вместо символических ссылок использовал копирование файлов
- Скопировал `phpstan` и `php-cs-fixer` в `/usr/local/bin/` с установкой прав на выполнение

**Результат**: ❌ Не сработало
- Скрипты используют относительные пути для включения vendor файлов
- Ошибка: `Failed to open stream: No such file or directory` при попытке загрузить зависимости

**Ошибка**:
```
Warning: include(/usr/local/bin/../phpstan/phpstan/phpstan): Failed to open stream
```

### Попытка 2: Копирование всей vendor директории

**Действия**:
- Скопировал всю vendor директорию в `/usr/local/lib/composer/`
- Попытался исправить пути в скриптах через `sed`

**Результат**: ❌ Не сработало
- Sed не смог правильно исправить все пути в скриптах
- Структура зависимостей сложная, требует точного соответствия путей

### Попытка 3: Изменение прав доступа к /root/.composer

**Действия**:
- Добавил `chmod -R 755 /root/.composer` после установки инструментов
- Использовал символические ссылки на `/root/.composer/vendor/bin/`

**Результат**: ❌ Не сработало
- Проблема не в правах на чтение, а в том, что пользователь хоста не может выполнять файлы из `/root/.composer/vendor/bin/`
- Символические ссылки не решают проблему доступа к исходным файлам

### Попытка 4: Установка COMPOSER_HOME в доступную директорию

**Действия**:
- Установил `ENV COMPOSER_HOME=/var/www/html/.composer`
- Установил инструменты в `/var/www/html/.composer/` (доступная директория через volume)
- Создал символические ссылки на `/var/www/html/.composer/vendor/bin/`

**Результат**: ⚠️ Частично работает
- Образ собирается успешно
- Но при запуске контейнера: `phpstan: not found`
- Проблема: volume монтируется поверх директории `.composer`, перезаписывая установленные инструменты

## Текущее состояние

**Dockerfile.tools** (текущая версия):
```dockerfile
ENV COMPOSER_HOME=/var/www/html/.composer
RUN mkdir -p /var/www/html/.composer && \
    composer global require \
    phpstan/phpstan \
    friendsofphp/php-cs-fixer \
    --no-interaction --prefer-dist && \
    chmod -R 755 /var/www/html/.composer

RUN ln -sf /var/www/html/.composer/vendor/bin/phpstan /usr/local/bin/phpstan && \
    ln -sf /var/www/html/.composer/vendor/bin/php-cs-fixer /usr/local/bin/php-cs-fixer
```

**Проблема**: Volume `./backend:/var/www/html:cached` монтируется поверх директории `.composer`, удаляя установленные инструменты при запуске контейнера.

## Анализ проблемы

### Корневая причина

1. **Конфликт volumes**: Volume монтирует `./backend` в `/var/www/html`, перезаписывая все содержимое, включая `.composer`
2. **Права доступа**: Пользователь хоста не имеет доступа к `/root/.composer`
3. **Структура зависимостей**: PHPStan и PHP-CS-Fixer используют относительные пути для загрузки зависимостей

### Возможные решения

#### Решение 1: Использовать отдельный volume для .composer
- Создать отдельный volume для `/var/www/html/.composer`
- Исключить `.composer` из основного volume

#### Решение 2: Установить инструменты в системную директорию
- Установить инструменты в `/usr/local/lib/` с правильной структурой
- Обновить пути в скриптах или использовать wrapper скрипты

#### Решение 3: Использовать установку через apt/pip
- Если доступны пакеты через системный менеджер пакетов
- Но для PHPStan и PHP-CS-Fixer это не вариант (только через Composer)

#### Решение 4: Изменить подход к volumes
- Не монтировать весь `./backend`, а только необходимые поддиректории
- Или использовать named volume для `.composer`

## Рекомендуемое решение

**Вариант A: Использовать named volume для .composer**

```dockerfile
# В docker-compose.yml добавить:
tools:
  volumes:
    - ./backend:/var/www/html:cached
    - composer_tools:/var/www/html/.composer
  # ...
volumes:
  composer_tools:
```

**Вариант B: Установить инструменты в /usr/local/lib с правильной структурой** ✅ РЕАЛИЗОВАНО

**Реализация**:
```dockerfile
# Установка PHPStan и PHP-CS-Fixer через Composer (временная установка в /root/.composer)
RUN composer global require \
    phpstan/phpstan \
    friendsofphp/php-cs-fixer \
    --no-interaction --prefer-dist

# Копирование vendor директории в /usr/local/lib/composer для глобального доступа
# Это позволяет избежать конфликта с volume, который монтируется в /var/www/html
RUN mkdir -p /usr/local/lib/composer && \
    cp -r /root/.composer/vendor/* /usr/local/lib/composer/ && \
    chmod -R 755 /usr/local/lib/composer

# Создание символических ссылок на bin скрипты из vendor
# Эти скрипты правильно настроены Composer и используют autoload.php
RUN ln -sf /usr/local/lib/composer/bin/phpstan /usr/local/bin/phpstan && \
    ln -sf /usr/local/lib/composer/bin/php-cs-fixer /usr/local/bin/php-cs-fixer
```

**Результат**: ✅ Работает
- PHPStan 2.1.32 - работает корректно
- PHP-CS-Fixer 3.91.0 - работает корректно
- Оба инструмента доступны через `/usr/local/bin/`
- Инструменты установлены в `/usr/local/lib/composer`, который не перезаписывается volume

**Вариант C: Использовать установку при первом запуске (entrypoint script)**

- Создать entrypoint скрипт, который проверяет наличие инструментов
- Устанавливать их при первом запуске в доступную директорию
- Кэшировать установку через volume

## Реализованное решение

✅ **Вариант B реализован и протестирован**

### Шаги реализации:
1. ✅ Установка инструментов через `composer global require` в `/root/.composer`
2. ✅ Копирование всей vendor директории в `/usr/local/lib/composer`
3. ✅ Создание символических ссылок на bin скрипты в `/usr/local/bin/`
4. ✅ Установка прав доступа `chmod -R 755` на `/usr/local/lib/composer`

### Тестирование:
- ✅ `phpstan --version` → PHPStan 2.1.32
- ✅ `php-cs-fixer --version` → PHP CS Fixer 3.91.0
- ✅ `phpstan analyse --help` → работает
- ✅ `php-cs-fixer fix --help` → работает
- ✅ `which phpstan php-cs-fixer` → `/usr/local/bin/phpstan`, `/usr/local/bin/php-cs-fixer`

### Преимущества решения:
- Инструменты установлены в `/usr/local/lib/composer`, который не перезаписывается volume
- Используются оригинальные bin скрипты от Composer с правильной настройкой autoload.php
- Не требуется создание wrapper скриптов или изменение путей
- Работает с пользователем хоста благодаря правильным правам доступа

### Финальное тестирование:
```bash
$ docker compose run --rm tools phpstan --version
PHPStan - PHP Static Analysis Tool 2.1.32

$ docker compose run --rm tools php-cs-fixer --version
PHP CS Fixer 3.91.0 Folding Bike by Fabien Potencier, Dariusz Ruminski and contributors.
PHP runtime: 8.3.28

$ docker compose run --rm tools which phpstan php-cs-fixer
/usr/local/bin/phpstan
/usr/local/bin/php-cs-fixer
```

**Вывод**: ✅ Решение работает корректно. Оба инструмента доступны и функционируют.

## Следующие шаги

1. ✅ Реализовано решение B
2. ✅ Протестирована работа инструментов
3. ⏸️ Обновить документацию (опционально)

## Зависимости

- A11: Создать Dockerfile.tools для инструментов анализа (Completed ✅)
- A12: Настроить права доступа в Docker контейнерах (Completed ✅)

## Связанные задачи

- A13: Создать helper-скрипты для разработки (может потребовать исправления tools контейнера)

## Документирование результатов

### Assumption Log

- **A1**: [Описание предположения] - [Обоснование]

### Успешные решения

- **Решение 1**: [Описание решения] - [Почему оно было эффективным]

### Неверные решения

#### Неверное решение 1: [Краткое название]

**Принятое решение**: [Подробное описание того, что было сделано]

**Обоснование выбора**: [Почему это решение казалось правильным]

**Возникшие проблемы**: 
- [Проблема 1]

**Корректное решение**: [Что было сделано вместо этого]

**Извлеченные уроки**: [Что можно извлечь из этого опыта]

