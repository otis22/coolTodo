# Task A21: Исправить путь к larastan extension.neon в phpstan.neon

**Статус**: Completed ✅  
**Приоритет**: High  
**Оценка**: 0.1 дня

## Описание

В `phpstan.neon` указан неправильный путь к `larastan/larastan/extension.neon`. PHPStan запускается из `backend/` с `--configuration=../phpstan.neon`, но путь в конфигурации разрешается относительно расположения `phpstan.neon` (корень проекта), а vendor находится в `backend/vendor/`.

## Проблема

**Файл**: `phpstan.neon`  
**Строка**: 2

**Текущая конфигурация**:
```yaml
includes:
    - ./vendor/larastan/larastan/extension.neon
```

**Ошибка в CI**:
```
File '/home/runner/work/coolTodo/coolTodo/backend/.././vendor/larastan/larastan/extension.neon' is missing or is not readable.
```

**Причина**: 
- `phpstan.neon` находится в корне проекта
- PHPStan запускается из `backend/` с `--configuration=../phpstan.neon`
- Пути в `phpstan.neon` разрешаются относительно расположения самого файла (корень проекта)
- Текущий путь `./vendor/larastan/larastan/extension.neon` ищет vendor в корне проекта
- Но vendor находится в `backend/vendor/`, а не в корне
- Правильный путь должен быть `backend/vendor/larastan/larastan/extension.neon` (относительно корня)

## Решение

**Вариант 1 (Рекомендуемый)**: Исправить путь относительно расположения `phpstan.neon`:
```yaml
includes:
    - backend/vendor/larastan/larastan/extension.neon
```

**Вариант 2**: Переместить `phpstan.neon` в `backend/` и обновить пути:
- Переместить `phpstan.neon` → `backend/phpstan.neon`
- Обновить путь: `vendor/larastan/larastan/extension.neon`
- Обновить CI workflow: `--configuration=phpstan.neon`
- Обновить другие пути в конфигурации

## Критерии приемки

✅ PHPStan находит `larastan/larastan/extension.neon`  
✅ PHPStan запускается без ошибок в CI  
✅ Конфигурация работает корректно

## Зависимости

- [x] A16: Исправить расположение composer.json (Completed ✅)
- [x] A17: Исправить пути в CI для PHPUnit (Completed ✅)

## Реализация

### Попытка 1: Исправление пути в phpstan.neon (УСПЕШНАЯ)

**Коммит**: `096a6db`  
**Изменения**:
- `./vendor/larastan/larastan/extension.neon` → `backend/vendor/larastan/larastan/extension.neon`

**Обоснование**: Пути в `phpstan.neon` разрешаются относительно расположения самого файла (корень проекта), а vendor находится в `backend/vendor/`.

**Результат проверки** (Workflow run `19802370120` - completed, success):
- ✅ PHPStan запустился успешно
- ✅ PHPStan нашел `extension.neon`: нет ошибок о missing/not readable
- ✅ PHPStan выполнил анализ: `11/11 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%`
- ⚠️ Предупреждение: `Ignored error pattern #PHPDoc tag @var# was not matched in reported errors` (не критично, это предупреждение о неиспользуемом паттерне игнорирования)
- ⚠️ Предупреждение: `You're using a deprecated config option checkMissingIterableValueType` (не критично, это устаревшая опция)

**Вывод**: Задача A21 полностью решена! ✅ Путь к extension.neon исправлен, PHPStan работает корректно.

## Связанные задачи

- A16: Исправить расположение composer.json (привело к проблеме с путями)
- A17: Исправить пути в CI для PHPUnit (аналогичная проблема с путями)

