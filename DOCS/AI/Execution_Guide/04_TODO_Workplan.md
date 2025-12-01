# TODO Workplan: CoolTodo

## Phase A: Foundations (Основа проекта)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| A1 | Инициализировать проект | High | 1 | None | Composer, Laravel, Vue, Vite | Проект собирается успешно, структура директорий создана | Completed ✅ |
| A2 | Настроить CI пайплайн | High | 1.5 | A1 | GitHub Actions | CI запускается на PR, все шаги проходят | Completed ✅ |
| A3 | Настроить Docker окружение | High | 1 | A1 | Docker, Docker Compose | Приложение запускается в Docker, доступно по http://localhost | Completed ✅ |
| A4 | Настроить инструменты качества кода | High | 1 | A1 | PHPStan, PHP-CS-Fixer | PHPStan level 9 настроен, PHP-CS-Fixer применяется | Completed ✅ |
| A5 | Создать структуру Domain Layer | High | 0.5 | A1 | PHP | Директории Domain/UseCases, Domain/Models созданы | Completed ✅ |
| A6 | Создать структуру Infrastructure Layer | High | 0.5 | A1 | PHP | Директории Infrastructure/Repositories, Infrastructure/Http созданы | Completed ✅ |
| A7 | Создать структуру Frontend | High | 0.5 | A1 | Vue, Vite | Директории frontend/src/components, frontend/src/services созданы | Completed ✅ |
| A8 | Обновить зависимости проекта | Medium | 0.5 | A1 | npm, composer | Все зависимости проверены и обновлены до актуальных версий, тесты проходят | Completed ✅ |
| A9 | Обновить Xdebug в Dockerfile | Medium | 0.25 | A3 | Docker | Xdebug обновлен до последней версии 3.x, Docker образ собирается | Completed ✅ |
| A10 | Создать Dockerfile.dev для разработки | High | 0.5 | A3 | Docker | Dockerfile.dev создан, содержит PHP 8.3-cli, Composer, Xdebug | Completed ✅ |
| A11 | Создать Dockerfile.tools для инструментов анализа | High | 0.5 | A3 | Docker | Dockerfile.tools создан, содержит PHPStan и PHP-CS-Fixer | Completed ✅ |
| A12 | Настроить права доступа в Docker контейнерах | High | 0.25 | A10, A11 | Docker | Контейнеры работают от имени пользователя хоста, файлы создаются с правильными правами | Completed ✅ |
| A13 | Создать helper-скрипты для разработки | Medium | 0.25 | A12 | Bash | Скрипт `dev` создан, все команды работают | Completed ✅ |
| A14 | Обновить документацию по разработке | Medium | 0.25 | A13 | Markdown | README.md и DOCS/DEVELOPMENT.md обновлены | Completed ✅ |
| A17 | Исправить пути в CI для PHPUnit | High | 0.25 | A16 | PHPUnit, CI | PHPUnit находит все тесты, coverage генерируется | Completed ✅ |
| A18 | Удалить старый composer.json из корня проекта | Medium | 0.1 | A16 | Git | Старый composer.json удален, CI работает корректно | Completed ✅ |
| A19 | Исправить путь к coverage.xml в CI | Low | 0.1 | A17 | CI, Codecov | Coverage.xml генерируется в правильном месте, Codecov находит файл | Completed ✅ |
| A20 | Исправить скрипт post-autoload-dump в composer.json | High | 0.1 | A16 | Composer, Laravel | composer install выполняется успешно, пакеты обнаруживаются | Completed ✅ |
| A21 | Исправить путь к larastan extension.neon в phpstan.neon | High | 0.1 | A17 | PHPStan, CI | PHPStan находит extension.neon, запускается без ошибок | Completed ✅ |
| A22 | Исправить форматирование кода PHP-CS-Fixer | Low | 0.25 | A11, A13 | PHP-CS-Fixer | PHP-CS-Fixer не находит файлов для исправления | Completed ✅ |
| A23 | Исправить права доступа для composer install | High | 0.25 | A12, A16 | Docker, Composer | composer install выполняется успешно, vendor принадлежит пользователю хоста | Completed ✅ |
| A24 | Исправить пути в конфигурации PHPStan | Medium | 0.25 | A11, A13 | PHPStan | PHPStan работает в Docker контейнере, анализирует все файлы | Completed ✅ |
| A25 | Исправить пути к конфигурационным файлам в скрипте dev | High | 0.25 | A13, A17, A21 | Bash, Docker | PHPUnit и PHPStan работают с правильными конфигурациями через ./dev | Completed ✅ |
| A26 | Исправить пути PHPStan системно (без поломки CI) | High | 0.5 | A24, A25 | PHPStan, Docker, CI | Одна конфигурация работает и в CI, и локально, без дублирования | Completed ✅ |
| A27 | Исправить пути конфигурации PHP-CS-Fixer для Docker | Medium | 0.25 | A11, A13 | PHP-CS-Fixer, Docker | PHP-CS-Fixer работает с правильной конфигурацией в Docker | Completed ✅ |
| A28 | Унифицировать пути конфигураций инструментов системно | High | 1 | A26, A27 | PHPUnit, PHPStan, PHP-CS-Fixer, Docker, CI | Все инструменты работают с едиными конфигурациями в CI и локально | Completed ✅ |

## Phase B: Core Features (Основной функционал)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| B1 | Создать модели данных (Task, TaskStatus) | High | 2 | A5 | PHP, PHPUnit | Модели созданы, покрыты тестами (TDD: Red-Green-Refactor), PHPStan level 9 | Completed ✅ |
| B2 | Создать миграции БД | High | 1 | B1 | Laravel Migrations | Таблица todos создана, миграция обратима | Completed ✅ |
| B3 | Реализовать TodoRepository | High | 2 | B1, B2 | PHP, Eloquent, PHPUnit | Repository реализован, покрыт тестами (TDD: Red-Green-Refactor) | Completed ✅ |
| B4 | Реализовать Use Cases (Create, Update, Delete, Toggle) | High | 3 | B1, B3 | PHP, PHPUnit | Все Use Cases реализованы, покрыты тестами >90% (TDD: Red-Green-Refactor) | Completed ✅ |
| B5 | Реализовать API контроллеры | High | 2 | B4 | Laravel, PHPUnit | Все endpoints работают, покрыты feature тестами (TDD: Red-Green-Refactor) | Completed ✅ |
| B5.1 | Исправить окружение БД для feature тестов | High | 0.5 | B5 | Laravel, PHPUnit, MySQL | Feature тесты могут выполнять миграции, RefreshDatabase работает корректно | Completed ✅ |
| B5.2 | Универсальная конфигурация БД для тестов | High | 0.25 | B5.1 | Laravel, PHPUnit, CI | Единая конфигурация работает локально и в CI без дублирования | Completed ✅ |
| B5.3 | Проверить и исправить CI workflow для тестов с БД | High | 0.25 | B5.2 | CI, GitHub Actions | Тесты с БД проходят в CI, все переменные окружения настроены | Completed ✅ |
| B5.4 | Системное решение для coverage.xml в локальном и CI окружении | High | 0.5 | B5.3 | Docker, Xdebug, CI | Coverage.xml генерируется локально и в CI, Xdebug настроен | Completed ✅ |
| B5.5 | Исправить форматирование кода PHP-CS-Fixer | Medium | 0.25 | B5 | PHP-CS-Fixer | Все файлы отформатированы согласно PSR-12 | Completed ✅ |
| B5.6 | Улучшить обработку ошибок в CI workflow | Medium | 0.5 | B5.3 | CI, GitHub Actions | Критические шаги останавливают workflow, некритические продолжаются | Completed ✅ |
| B5.7 | Добавить проверку готовности MySQL перед миграциями | Medium | 0.25 | B5.3 | CI, MySQL | MySQL проверяется на готовность, нет race conditions | Completed ✅ |
| B5.8 | Исправить генерацию coverage при падении тестов | Low | 0.25 | B5.4, B5.6 | CI, Coverage | Coverage генерируется даже при ошибках тестов | Completed ✅ |
| B5.9 | Исправить доступность MySQL client в CI | Medium | 0.25 | B5.7 | CI, MySQL | MySQL client доступен, проверка готовности работает | Completed ✅ |
| B5.10 | Исправить оставшиеся проблемы форматирования PHP-CS-Fixer | Low | 0.25 | B5.5 | PHP-CS-Fixer | Все файлы отформатированы, CI не показывает ошибки | Completed ✅ |
| B6 | Реализовать валидацию запросов | High | 1 | B5 | Laravel Requests | Валидация работает, тесты проходят (TDD: Red-Green-Refactor) | Completed ✅ |
| B7 | Настроить API routes | High | 0.5 | B5 | Laravel Routes | Все routes зарегистрированы, работают | Completed ✅ |

## Phase C: Frontend (Интерфейс)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| C1 | Создать API сервис (TodoApiService) | High | 1 | B7 | Vue, Axios/Fetch | Сервис работает, тесты проходят (TDD: Red-Green-Refactor) | Completed ✅ |
| C2 | Реализовать компонент TodoItem | High | 2 | C1 | Vue 3, Vite | Компонент отображает задачу, обрабатывает события | Completed ✅ |
| C3 | Реализовать компонент TodoList | High | 2 | C2 | Vue 3, Vite | Компонент отображает список, фильтрация работает | Completed ✅ |
| C4 | Реализовать фильтрацию (All/Active/Completed) | High | 1 | C3 | Vue 3 | Фильтрация работает на клиенте | Completed ✅ |
| C5 | Реализовать счетчик активных задач | High | 0.5 | C3 | Vue 3 | Счетчик отображается и обновляется | Completed ✅ |
| C6 | Реализовать редактирование задачи (double-click) | High | 1.5 | C2 | Vue 3 | Редактирование работает (Enter/Escape) | Completed ✅ |
| C7 | Реализовать кнопку "Clear completed" | High | 0.5 | C3 | Vue 3 | Кнопка удаляет все completed задачи | Completed ✅ |
| C8 | Интегрировать фронтенд с бэкендом | High | 1 | C1-C7 | Vue, Laravel | Все операции работают через API | Completed ✅ |

## Phase D: Testing & Quality (Тестирование и качество)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| D1 | Написать Unit тесты для Domain Layer | High | 3 | B4 | PHPUnit | Покрытие >90%, все тесты проходят (TDD: Red-Green-Refactor) | Completed ✅ |
| D2 | Написать Integration тесты для API | High | 2 | B7 | PHPUnit, Laravel | Все endpoints покрыты тестами (TDD: Red-Green-Refactor) | Completed ✅ |
| D3.1 | Настроить Laravel Dusk для E2E тестов | High | 0.5 | C8 | Laravel Dusk | Dusk установлен и настроен, базовый тест работает | Completed ✅ |
| D3.2 | Написать E2E тест для создания задачи | High | 0.5 | D3.1 | Laravel Dusk | Тест создает задачу через UI и проверяет результат | Completed ✅ |
| D3.3 | Написать E2E тест для редактирования задачи | High | 0.5 | D3.1 | Laravel Dusk | Тест редактирует задачу через UI (double-click) | Completed ✅ |
| D3.4 | Написать E2E тест для переключения статуса и фильтрации | High | 0.5 | D3.1 | Laravel Dusk | Тест переключает статус, фильтрует задачи | Completed ✅ |
| D3.5 | Написать E2E тест для удаления задач | High | 0.5 | D3.1 | Laravel Dusk | Тест удаляет одну задачу и все completed задачи | Completed ✅ |
| D4 | Настроить покрытие кода | High | 0.5 | D1-D3 | PHPUnit, Xdebug | Coverage reports генерируются | Completed ✅ |
| D5.1 | Исправить предупреждения PHPStan в Domain Layer | High | 0.5 | B4 | PHPStan | Domain Layer без предупреждений PHPStan level 9 | Completed ✅ |
| D5.2 | Исправить предупреждения PHPStan в Infrastructure Layer | High | 0.5 | B4 | PHPStan | Infrastructure Layer без предупреждений PHPStan level 9 | Completed ✅ |
| D5.3 | Исправить предупреждения PHPStan в Controllers | High | 0.5 | B5 | PHPStan | Controllers без предупреждений PHPStan level 9 | Completed ✅ |
| D5.4 | Проверить и исправить оставшиеся предупреждения PHPStan | High | 0.5 | D5.1-D5.3 | PHPStan | PHPStan level 9 без ошибок во всем проекте | Completed ✅ |

## Phase E: DevOps & Deployment (Развертывание)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| E1 | Настроить production Docker конфигурацию | Medium | 1 | A3 | Docker | Production образ собирается, оптимизирован | Open |
| E2 | Настроить оптимизацию производительности | Medium | 1.5 | E1 | OPCache, Composer | OPCache включен, автозагрузчик оптимизирован | Open |
| E3 | Настроить логирование | Medium | 1 | B5 | Laravel Logging | Логи пишутся, структурированы | Open |
| E4 | Настроить обработку ошибок | Medium | 1 | B5 | Laravel Exception Handler | Ошибки обрабатываются, логируются | Open |
| E5 | Создать документацию API | Medium | 1 | B7 | Markdown/Swagger | API задокументировано | Open |
| E6 | Исправить перезапуск app контейнера | High | 0.5 | A3 | Docker, PHP-FPM | App контейнер работает стабильно, PHP-FPM доступен | Completed ✅ |
| E7 | Исправить 404 ошибки в nginx для API | High | 0.25 | A3, E6 | Nginx, Laravel | API endpoints доступны через nginx | Completed ✅ |
| E8 | Настроить CORS для API | Medium | 0.25 | E6, E7 | Laravel CORS | CORS заголовки отправляются, запросы с фронтенда разрешены | Completed ✅ |
| E9 | Настроить локальное окружение (.env) | High | 0.25 | A3, E6 | Laravel, Docker | .env.example создан, локальное окружение работает | Completed ✅ |
| E10 | Удалить устаревший атрибут version из docker-compose.yml | Low | 0.05 | A3 | Docker Compose | Предупреждение об устаревшем атрибуте исчезло | Completed ✅ |
| E11 | Создать скрипт инициализации проекта | Medium | 0.5 | E9 | Bash, Docker | Скрипт автоматически настраивает проект для разработки | Completed ✅ |
| E12 | Исправить ошибки PHP-CS-Fixer в CI workflow | Medium | 0.25 | E11 | PHP-CS-Fixer, CI | PHP-CS-Fixer не находит файлов для исправления в CI | Completed ✅ |
| E13 | Исправить проблему с Codecov rate limit | Low | 0.25 | D4 | Codecov, CI | Codecov загружает coverage без ошибок 429 | Completed ✅ |
| E14 | Пересмотреть continue-on-error для PHPStan в CI | Medium | 0.1 | D5.4 | PHPStan, CI | PHPStan является критичным шагом или обоснован continue-on-error | Completed ✅ |
| E15 | Исправить проблему с tinker (psysh config path) | Medium | 0.25 | E6, E9 | Laravel, Docker | Tinker запускается без ошибок, psysh создает конфигурацию в правильном месте | Completed ✅ |
| E16 | Исправить стабильность app контейнера (PHP-FPM) | High | 0.5 | A3, E6 | Docker, PHP-FPM | App контейнер запускается стабильно, PHP-FPM доступен, нет 502 ошибок | Completed ✅ |

## Индикаторы статуса

- **Completed ✅**: Задача завершена и заархивирована
- **In Progress**: В процессе выполнения (документирована в `DOCS/INPROGRESS/`)
- **Blocked**: Невозможно продолжить из-за внешних зависимостей
- **Open**: Готова к началу (зависимости удовлетворены)

## Приоритизация

Задачи выполняются в следующем порядке:

1. **По приоритету**: High > Medium > Low
2. **По фазе**: Phase A → Phase B → Phase C → Phase D → Phase E
3. **По ID**: Внутри фазы задачи выполняются по порядку ID (включая подзадачи, например D3.1, D3.2, D3.3...)

## Обновление статуса

При изменении статуса задачи:

1. Обновить поле **Status** в этой таблице
2. Если задача завершена: создать архив в `DOCS/TASK_ARCHIVE/[TaskID]_[TaskName]/`
3. Если задача заблокирована: добавить запись в `DOCS/INPROGRESS/blocked.md`
4. Если задача в процессе: создать файл в `DOCS/INPROGRESS/[TaskID]_*.md`

