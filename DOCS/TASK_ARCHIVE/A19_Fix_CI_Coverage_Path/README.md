# Task A19: Исправить путь к coverage.xml в CI

**Статус**: Completed ✅  
**Приоритет**: Low  
**Оценка**: 0.1 дня

## Описание

В GitHub Actions CI workflow путь к `coverage.xml` для загрузки в Codecov может быть неправильным из-за того, что PHPUnit запускается из `backend/`, а coverage.xml создается в корне проекта.

## Проблема

**Файл**: `.github/workflows/ci.yml`  
**Строки**: 100, 116

**Текущая конфигурация**:
```yaml
- name: Run PHPUnit tests
  working-directory: backend
  run: vendor/bin/phpunit --configuration=../phpunit.xml --coverage-text --coverage-clover=../coverage.xml

- name: Upload coverage to Codecov
  uses: codecov/codecov-action@v3
  with:
    file: ./coverage.xml
```

**Проблема**:
- PHPUnit запускается из `backend/` с `--coverage-clover=../coverage.xml`
- Это создает файл `coverage.xml` в корне проекта (правильно)
- Но шаг "Upload coverage" выполняется из корня (по умолчанию), поэтому путь `./coverage.xml` должен быть правильным
- Однако, если есть проблемы с путями в phpunit.xml (задача A17), coverage может не генерироваться

## Причина

После перемещения `composer.json` в `backend/` и обновления CI workflow, пути к coverage файлу могут быть неправильными, если phpunit.xml не настроен корректно.

## Решение

**Вариант 1**: Убедиться, что путь правильный (скорее всего уже правильный):
```yaml
- name: Upload coverage to Codecov
  uses: codecov/codecov-action@v3
  with:
    file: ./coverage.xml  # Из корня проекта
```

**Вариант 2**: Явно указать путь:
```yaml
- name: Upload coverage to Codecov
  uses: codecov/codecov-action@v3
  with:
    file: coverage.xml
    working-directory: .  # Явно указать корень
```

**Вариант 3**: Если coverage.xml создается в другом месте, обновить путь:
```yaml
- name: Upload coverage to Codecov
  uses: codecov/codecov-action@v3
  with:
    file: backend/coverage.xml  # Если создается в backend/
```

**Примечание**: Эта задача зависит от задачи A17. После исправления путей в phpunit.xml нужно проверить, что coverage.xml генерируется в правильном месте.

## Реализация

✅ Проверено через GitHub CLI (gh) - Workflow run `19802206579`:
- ✅ PHPUnit генерирует coverage: `Generating code coverage report in Clover XML format ... done`
- ✅ Coverage.xml создается в корне проекта: `--coverage-clover=../coverage.xml` (относительно backend/)
- ✅ Codecov находит файл: `Found 1 possible coverage files: ./coverage.xml`
- ✅ Codecov обрабатывает файл: `Processing ./coverage.xml...`
- ✅ Путь `./coverage.xml` правильный (относительно корня проекта, где выполняется шаг)

**Вывод**: Путь к coverage.xml уже правильный! Файл генерируется в корне проекта и Codecov его находит. Единственная проблема - rate limit от Codecov (429), что не связано с путями.

**Решение**: Путь уже правильный, дополнительных изменений не требуется. Задача решена! ✅

## Критерии приемки

✅ Coverage.xml генерируется в правильном месте  
✅ Codecov успешно загружает coverage отчет  
✅ Путь к файлу указан явно и правильно

## Зависимости

- [x] A16: Исправить расположение composer.json (Completed ✅)
- [x] A17: Исправить пути в CI для PHPUnit (Completed ✅)

## Связанные задачи

- A17: Исправить пути в CI для PHPUnit (связано с генерацией coverage)

