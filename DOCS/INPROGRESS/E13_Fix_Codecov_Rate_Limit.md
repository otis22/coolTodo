# Task E13: Исправить проблему с Codecov rate limit

**Статус**: Open  
**Приоритет**: Low  
**Оценка**: 0.25 дня

## Описание

Codecov возвращает ошибку 429 (Rate limit reached) при попытке загрузить coverage отчеты в CI workflow.

## Проблема

**Текущая ситуация**:
- Codecov API возвращает ошибку 429 (Rate limit reached)
- Сообщение: "Rate limit reached. Please upload with the Codecov repository upload token to resolve issue. Expected time to availability: 2246s."
- Coverage не загружается в Codecov
- Это не критично для работы проекта, но мешает отслеживанию покрытия кода

**Логи из последнего workflow**:
```
Upload coverage to Codecov: Error uploading to https://codecov.io: Error: There was an error fetching the storage URL during POST: 429 - {"message":"Rate limit reached. Please upload with the Codecov repository upload token to resolve issue. Expected time to availability: 2246s."}
```

## Зависимости

- [x] D4: Настроить покрытие кода (Completed ✅)

## Требования

### Цель

Настроить Codecov для работы с repository upload token, чтобы избежать проблем с rate limit.

### Критерии приемки

✅ Codecov загружает coverage отчеты без ошибок  
✅ Нет ошибок 429 в логах workflow  
✅ Coverage отчеты доступны в Codecov dashboard  
✅ PHPStan level 9 без ошибок  
✅ PHP-CS-Fixer не находит файлов для исправления  
✅ Все тесты проходят

## План выполнения

### Шаг 1: Получение Codecov token
- [ ] Зарегистрироваться/войти в Codecov
- [ ] Получить repository upload token для проекта
- [ ] Добавить token в GitHub Secrets как `CODECOV_TOKEN`

### Шаг 2: Обновление workflow
- [ ] Обновить шаг "Upload coverage to Codecov" в `.github/workflows/ci.yml`
- [ ] Добавить использование `CODECOV_TOKEN` в конфигурацию
- [ ] Проверить синтаксис workflow файла

### Шаг 3: Проверка
- [ ] Сделать commit и push
- [ ] Проверить, что coverage загружается без ошибок
- [ ] Убедиться, что отчеты доступны в Codecov

## Технические детали

### Текущая конфигурация в workflow

```yaml
- name: Upload coverage to Codecov
  if: always() && hashFiles('coverage.xml') != ''
  uses: codecov/codecov-action@v3
  with:
    file: ./coverage.xml
    flags: unittests
    name: codecov-umbrella
  continue-on-error: true
```

### Рекомендуемая конфигурация

```yaml
- name: Upload coverage to Codecov
  if: always() && hashFiles('coverage.xml') != ''
  uses: codecov/codecov-action@v3
  with:
    file: ./coverage.xml
    flags: unittests
    name: codecov-umbrella
    token: ${{ secrets.CODECOV_TOKEN }}
  continue-on-error: true
```

## Альтернативные решения

1. **Использовать GitHub Actions artifact** вместо Codecov (если не нужен внешний сервис)
2. **Увеличить интервал между загрузками** (не решает проблему полностью)
3. **Использовать Codecov только для PR** (уменьшить количество запросов)

## Прогресс

Создано: 2025-12-01

## Примечания

- Это не критичная проблема, так как coverage генерируется локально
- Можно отложить до момента, когда понадобится отслеживание покрытия в Codecov
- Приоритет Low, так как не влияет на функциональность проекта

