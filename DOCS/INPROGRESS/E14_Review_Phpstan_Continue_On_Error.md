# Task E14: Пересмотреть continue-on-error для PHPStan в CI

**Статус**: Open  
**Приоритет**: Medium  
**Оценка**: 0.1 дня

## Описание

PHPStan имеет `continue-on-error: true` в CI workflow, что может скрывать критические ошибки статического анализа.

## Проблема

**Текущая ситуация**:
- PHPStan настроен с `continue-on-error: true` в workflow
- Это означает, что даже если PHPStan найдет ошибки, workflow продолжит выполнение
- Статический анализ должен быть критичным для качества кода
- Скрытие ошибок PHPStan может привести к проблемам в production

**Текущая конфигурация**:
```yaml
- name: Run PHPStan
  working-directory: backend
  run: vendor/bin/phpstan analyse --memory-limit=1G --configuration=../phpstan.neon
  continue-on-error: true
```

## Зависимости

- [x] D5.4: Проверить и исправить оставшиеся предупреждения PHPStan (Completed ✅)

## Требования

### Цель

Убедиться, что PHPStan является критичным шагом в CI workflow, и убрать `continue-on-error: true`, если все ошибки исправлены.

### Критерии приемки

✅ PHPStan не имеет `continue-on-error: true` (если все ошибки исправлены)  
✅ Или обосновано, почему `continue-on-error: true` необходим  
✅ Workflow останавливается при ошибках PHPStan (если это критично)  
✅ PHP-CS-Fixer не находит файлов для исправления  
✅ Все тесты проходят

## План выполнения

### Шаг 1: Проверка текущего состояния
- [ ] Проверить, есть ли ошибки PHPStan в последних workflow runs
- [ ] Запустить PHPStan локально: `./dev phpstan`
- [ ] Убедиться, что PHPStan проходит без ошибок

### Шаг 2: Принятие решения
- [ ] Если PHPStan проходит без ошибок → убрать `continue-on-error: true`
- [ ] Если есть известные проблемы → задокументировать их и оставить `continue-on-error: true` с комментарием

### Шаг 3: Обновление workflow
- [ ] Обновить `.github/workflows/ci.yml`
- [ ] Убрать или обосновать `continue-on-error: true`
- [ ] Добавить комментарий, если оставляем `continue-on-error: true`

### Шаг 4: Проверка
- [ ] Сделать commit и push
- [ ] Проверить, что workflow работает корректно
- [ ] Убедиться, что PHPStan блокирует workflow при ошибках (если убрали continue-on-error)

## Технические детали

### Текущая конфигурация

```yaml
- name: Run PHPStan
  working-directory: backend
  run: vendor/bin/phpstan analyse --memory-limit=1G --configuration=../phpstan.neon
  continue-on-error: true
```

### Рекомендуемая конфигурация (если все ошибки исправлены)

```yaml
- name: Run PHPStan
  working-directory: backend
  run: vendor/bin/phpstan analyse --memory-limit=1G --configuration=../phpstan.neon
  # continue-on-error: true  # Убрано, так как PHPStan должен быть критичным
```

## Прогресс

Создано: 2025-12-01

## Примечания

- Согласно последним логам, PHPStan проходит без ошибок: `[OK] No errors`
- Можно безопасно убрать `continue-on-error: true`
- Это улучшит качество кода, так как ошибки PHPStan будут блокировать merge

