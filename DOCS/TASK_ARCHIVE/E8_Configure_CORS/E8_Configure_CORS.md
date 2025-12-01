# Task E8: Настроить CORS для API

**Статус**: Open  
**Приоритет**: Medium  
**Оценка**: 0.25 дня

## Описание

Браузер блокирует запросы к API из-за CORS политики. Laravel должен отправлять правильные CORS заголовки для разрешения запросов с фронтенда. Настройка должна работать в разных окружениях (dev, production) с разными origin'ами.

## Проблема

**Текущая ситуация**:
- Фронтенд на `http://localhost:5173` пытается обратиться к API на `http://localhost:8080/api/todos`
- Браузер блокирует запрос: "Access to fetch at 'http://localhost:8080/api/todos' from origin 'http://localhost:5173' has been blocked by CORS policy: Response to preflight request doesn't pass access control check: No 'Access-Control-Allow-Origin' header is present on the requested resource."

**Причина**:
- Laravel имеет встроенную поддержку CORS через `HandleCors` middleware
- Но middleware может быть не включен в `bootstrap/app.php`
- Конфигурация CORS (`config/cors.php`) может отсутствовать или быть неправильной
- Нужна настройка для разных окружений (dev: localhost:5173, production: реальный домен)

## Зависимости

- [ ] E6: Исправить перезапуск app контейнера (Open)
- [ ] E7: Исправить 404 ошибки в nginx для API (Open)

**Примечание**: CORS ошибка может быть следствием проблемы E7 (404), так как запросы не доходят до Laravel. После исправления E7 нужно проверить, остается ли проблема CORS.

## Требования

### Цель

Создать системное решение для CORS, которое работает в:
- **Dev окружении**: Разрешить запросы с `http://localhost:5173`
- **Production**: Разрешить запросы с production домена (настраивается через env)
- **CI workflow**: Не требуется (нет браузера)

### Критерии приемки

✅ CORS заголовки отправляются для всех API запросов  
✅ Preflight запросы (OPTIONS) обрабатываются правильно  
✅ Запросы с `http://localhost:5173` разрешены в dev  
✅ CORS настраивается через переменные окружения для production  
✅ CORS ошибки в браузере исчезли

## Системное решение

### Анализ Laravel CORS

Laravel 11 использует встроенный `HandleCors` middleware, который:
- Автоматически применяется к маршрутам, указанным в `config/cors.php` → `paths`
- Читает конфигурацию из `config/cors.php`
- Поддерживает переменные окружения через `env()`

### Подход

1. **Создать `config/cors.php`** (если отсутствует):
   - Скопировать из `vendor/laravel/framework/config/cors.php`
   - Настроить `paths: ['api/*', 'sanctum/csrf-cookie']`
   - Использовать `env()` для `allowed_origins`

2. **Настроить переменные окружения**:
   - Dev: `CORS_ALLOWED_ORIGINS=http://localhost:5173` (или `*` для разработки)
   - Production: `CORS_ALLOWED_ORIGINS=https://yourdomain.com`

3. **Включить middleware** (если не включен автоматически):
   - Проверить `bootstrap/app.php`
   - В Laravel 11 middleware может быть включен автоматически для API маршрутов

### Конфигурация

```php
// config/cors.php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => env('CORS_ALLOWED_ORIGINS', '*') === '*' 
    ? ['*'] 
    : explode(',', env('CORS_ALLOWED_ORIGINS', '')),
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => false,
```

## План выполнения

### Шаг 1: Анализ проблемы
- [ ] Проверить, включен ли `HandleCors` middleware в `bootstrap/app.php`
- [ ] Проверить наличие конфигурации CORS в `config/cors.php`
- [ ] Проверить, что middleware применяется к API маршрутам автоматически

### Шаг 2: Создание конфигурации
- [ ] Создать `backend/config/cors.php` (скопировать из vendor и адаптировать)
- [ ] Настроить `paths` для `api/*`
- [ ] Настроить `allowed_origins` через `env('CORS_ALLOWED_ORIGINS')`
- [ ] Поддержать как `*` (для dev), так и конкретные домены (для production)

### Шаг 3: Настройка переменных окружения
- [ ] Добавить `CORS_ALLOWED_ORIGINS` в `.env.example`
- [ ] Установить значение для dev: `CORS_ALLOWED_ORIGINS=http://localhost:5173` или `*`
- [ ] Документировать настройку для production

### Шаг 4: Включение middleware (если нужно)
- [ ] Проверить, нужно ли явно включать middleware в `bootstrap/app.php`
- [ ] Если нужно - добавить в `withMiddleware()`

### Шаг 5: Тестирование
- [ ] Проверить preflight запросы (OPTIONS) в dev
- [ ] Проверить обычные запросы (GET, POST, etc.) в dev
- [ ] Проверить в браузере, что CORS ошибки исчезли
- [ ] Убедиться, что CI не затронут (нет браузера, CORS не нужен)

## Прогресс

Создано: 2025-01-27  
Обновлено: 2025-01-27 (системный анализ)

