# Task A12: Настроить права доступа в Docker контейнерах

**Статус**: Completed ✅  
**Начало**: 2025-01-27  
**Приоритет**: High  
**Оценка**: 0.25 дня

## Описание

Настроить права доступа в Docker контейнерах для работы от имени пользователя хоста. Это обеспечит правильные права доступа к файлам, созданным в контейнере, и позволит избежать проблем с Git и IDE.

## Критерии приемки

✅ Контейнеры работают от имени пользователя хоста  
✅ Файлы создаются с правильными правами  
✅ Git видит изменения, созданные в контейнере  
✅ IDE может редактировать файлы без проблем

## Реализация

### Обновлен docker-compose.yml

**Изменения в сервисе `app`:**
- ✅ Изменен dockerfile с `Dockerfile` на `Dockerfile.dev`
- ✅ Добавлен `user: "${UID:-1000}:${GID:-1000}"` для работы от имени пользователя хоста
- ✅ Добавлен `:cached` к volume для оптимизации на macOS
- ✅ Добавлена переменная окружения `COMPOSER_HOME=/var/www/html/.composer`

**Добавлен новый сервис `tools`:**
- ✅ Использует `Dockerfile.tools`
- ✅ Настроен `user: "${UID:-1000}:${GID:-1000}"`
- ✅ Настроены volumes с `:cached`
- ✅ Рабочая директория: `/var/www/html`

### Создан .env.example

Создан файл `.env.example` в корне проекта с переменными:
```env
# Docker User/Group IDs для правильных прав доступа
# На Linux/macOS: используйте `id -u` и `id -g` для получения ваших UID/GID
# На Windows (WSL2): также используйте `id -u` и `id -g` в WSL терминале
UID=1000
GID=1000
```

### Изменения в docker-compose.yml

**Было:**
```yaml
app:
  build:
    context: .
    dockerfile: Dockerfile
  container_name: cooltodo_app
  restart: unless-stopped
  working_dir: /var/www/html
  volumes:
    - ./backend:/var/www/html
    - ./backend/vendor:/var/www/html/vendor
```

**Стало:**
```yaml
app:
  build:
    context: .
    dockerfile: Dockerfile.dev
  container_name: cooltodo_app
  restart: unless-stopped
  user: "${UID:-1000}:${GID:-1000}"
  working_dir: /var/www/html
  volumes:
    - ./backend:/var/www/html:cached
    - ./backend/vendor:/var/www/html/vendor
  environment:
    - COMPOSER_HOME=/var/www/html/.composer

tools:
  build:
    context: .
    dockerfile: Dockerfile.tools
  container_name: cooltodo_tools
  restart: unless-stopped
  user: "${UID:-1000}:${GID:-1000}"
  working_dir: /var/www/html
  volumes:
    - ./backend:/var/www/html:cached
    - ./backend/vendor:/var/www/html/vendor
```

## Результат

Права доступа настроены:
- Контейнеры `app` и `tools` работают от имени пользователя хоста
- Файлы создаются с правильными правами (не от root)
- Git корректно видит изменения, созданные в контейнере
- IDE может редактировать файлы без проблем с правами
- Используются Dockerfile.dev и Dockerfile.tools вместо production Dockerfile

## Зависимости

- [x] A10: Создать Dockerfile.dev для разработки (Completed ✅)
- [x] A11: Создать Dockerfile.tools для инструментов анализа (Completed ✅)

## Связанные задачи

- A13: Создать helper-скрипты для разработки (следующая задача, теперь доступна)

