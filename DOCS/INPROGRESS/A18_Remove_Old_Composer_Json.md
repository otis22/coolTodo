# Task A18: Удалить старый composer.json из корня проекта

**Статус**: In Progress  
**Приоритет**: Medium  
**Оценка**: 0.1 дня

## Описание

В корне проекта остался старый `composer.json` с неправильными путями (использует `backend/src/` вместо `src/`). Это может вызвать проблемы, если CI или другие инструменты случайно используют его вместо правильного `backend/composer.json`.

## Проблема

**Текущая структура**:
```
coolTodo/
├── composer.json          # ← старый файл с неправильными путями
└── backend/
    └── composer.json      # ← правильный файл
```

**Проблемы в старом composer.json**:

1. **Неправильные пути в autoload**:
   ```json
   "autoload": {
       "psr-4": {
           "App\\": "backend/src/",  // ← неправильно
           "Database\\Factories\\": "backend/database/factories/",
           "Database\\Seeders\\": "backend/database/seeders/"
       }
   }
   ```
   В правильном `backend/composer.json` пути: `"App\\": "src/"`

2. **Неправильные пути в scripts**:
   ```json
   "@php backend/artisan package:discover --ansi"  // ← неправильно
   ```
   В правильном `backend/composer.json`: `"@php artisan package:discover --ansi"`

3. **Риск использования неправильного файла**:
   - CI может случайно использовать корневой `composer.json`
   - Инструменты могут найти неправильный файл
   - Может вызвать проблемы с autoload и путями

## Причина

После перемещения `composer.json` в `backend/` (задача A16), старый файл в корне не был удален. Это создало дублирование и потенциальную путаницу.

## Решение

**Вариант 1 (Рекомендуемый)**: Удалить старый `composer.json` из корня:
```bash
rm composer.json
```

**Вариант 2**: Переместить в архив (если нужна история):
```bash
mv composer.json DOCS/TASK_ARCHIVE/A16_Fix_Composer_Json_Location/
```

**Перед удалением проверить**:
- Убедиться, что CI использует `backend/composer.json` (уже проверено в A16)
- Убедиться, что Dockerfile использует `backend/composer.json` (уже обновлено в A16)
- Убедиться, что нет других зависимостей от корневого файла

## Критерии приемки

✅ Старый `composer.json` удален из корня проекта  
✅ Все инструменты используют `backend/composer.json`  
✅ CI работает корректно  
✅ Docker сборка работает корректно

## Зависимости

- [x] A16: Исправить расположение composer.json (Completed ✅)

## Реализация

✅ Проверено, что корневой `composer.json` содержит старые пути:
- `"App\\": "backend/src/"` вместо `"App\\": "src/"`
- `"@php backend/artisan"` вместо `"@php artisan"`

✅ Проверено, что все инструменты используют `backend/composer.json`:
- CI workflow использует `working-directory: backend`
- Dockerfile использует `COPY backend/composer.json ./`

✅ Старый `composer.json` удален из корня проекта

**Ожидание**: Проверка, что CI и Docker сборка работают корректно после удаления.

## Связанные задачи

- A16: Исправить расположение composer.json (старый файл остался в корне)

