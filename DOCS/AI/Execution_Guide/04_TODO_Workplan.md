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
| A13 | Создать helper-скрипты для разработки | Medium | 0.25 | A12 | Bash | Скрипт `dev` создан, все команды работают | Open |
| A14 | Обновить документацию по разработке | Medium | 0.25 | A13 | Markdown | README.md и DOCS/DEVELOPMENT.md обновлены | Open |

## Phase B: Core Features (Основной функционал)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| B1 | Создать модели данных (Task, TaskStatus) | High | 2 | A5 | PHP, PHPUnit | Модели созданы, покрыты тестами, PHPStan level 9 | Open |
| B2 | Создать миграции БД | High | 1 | B1 | Laravel Migrations | Таблица todos создана, миграция обратима | Open |
| B3 | Реализовать TodoRepository | High | 2 | B1, B2 | PHP, Eloquent, PHPUnit | Repository реализован, покрыт тестами | Open |
| B4 | Реализовать Use Cases (Create, Update, Delete, Toggle) | High | 3 | B1, B3 | PHP, PHPUnit | Все Use Cases реализованы, покрыты тестами >90% | Open |
| B5 | Реализовать API контроллеры | High | 2 | B4 | Laravel, PHPUnit | Все endpoints работают, покрыты feature тестами | Open |
| B6 | Реализовать валидацию запросов | High | 1 | B5 | Laravel Requests | Валидация работает, тесты проходят | Open |
| B7 | Настроить API routes | High | 0.5 | B5 | Laravel Routes | Все routes зарегистрированы, работают | Open |

## Phase C: Frontend (Интерфейс)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| C1 | Создать API сервис (TodoApiService) | High | 1 | B7 | Vue, Axios/Fetch | Сервис работает, тесты проходят | Open |
| C2 | Реализовать компонент TodoItem | High | 2 | C1 | Vue 3, Vite | Компонент отображает задачу, обрабатывает события | Open |
| C3 | Реализовать компонент TodoList | High | 2 | C2 | Vue 3, Vite | Компонент отображает список, фильтрация работает | Open |
| C4 | Реализовать фильтрацию (All/Active/Completed) | High | 1 | C3 | Vue 3 | Фильтрация работает на клиенте | Open |
| C5 | Реализовать счетчик активных задач | High | 0.5 | C3 | Vue 3 | Счетчик отображается и обновляется | Open |
| C6 | Реализовать редактирование задачи (double-click) | High | 1.5 | C2 | Vue 3 | Редактирование работает (Enter/Escape) | Open |
| C7 | Реализовать кнопку "Clear completed" | High | 0.5 | C3 | Vue 3 | Кнопка удаляет все completed задачи | Open |
| C8 | Интегрировать фронтенд с бэкендом | High | 1 | C1-C7 | Vue, Laravel | Все операции работают через API | Open |

## Phase D: Testing & Quality (Тестирование и качество)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| D1 | Написать Unit тесты для Domain Layer | High | 3 | B4 | PHPUnit | Покрытие >90%, все тесты проходят | Open |
| D2 | Написать Integration тесты для API | High | 2 | B7 | PHPUnit, Laravel | Все endpoints покрыты тестами | Open |
| D3 | Написать E2E тесты (Laravel Dusk) | High | 2 | C8 | Laravel Dusk | Все ключевые сценарии покрыты | Open |
| D4 | Настроить покрытие кода | High | 0.5 | D1-D3 | PHPUnit, Xdebug | Coverage reports генерируются | Open |
| D5 | Исправить все предупреждения PHPStan | High | 2 | B4, C8 | PHPStan | PHPStan level 9 без ошибок | Open |

## Phase E: DevOps & Deployment (Развертывание)

| Task ID | Description | Priority | Effort (days) | Dependencies | Tools | Acceptance Criteria | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| E1 | Настроить production Docker конфигурацию | Medium | 1 | A3 | Docker | Production образ собирается, оптимизирован | Open |
| E2 | Настроить оптимизацию производительности | Medium | 1.5 | E1 | OPCache, Composer | OPCache включен, автозагрузчик оптимизирован | Open |
| E3 | Настроить логирование | Medium | 1 | B5 | Laravel Logging | Логи пишутся, структурированы | Open |
| E4 | Настроить обработку ошибок | Medium | 1 | B5 | Laravel Exception Handler | Ошибки обрабатываются, логируются | Open |
| E5 | Создать документацию API | Medium | 1 | B7 | Markdown/Swagger | API задокументировано | Open |

## Индикаторы статуса

- **Completed ✅**: Задача завершена и заархивирована
- **In Progress**: В процессе выполнения (документирована в `DOCS/INPROGRESS/`)
- **Blocked**: Невозможно продолжить из-за внешних зависимостей
- **Open**: Готова к началу (зависимости удовлетворены)

## Приоритизация

Задачи выполняются в следующем порядке:

1. **По приоритету**: High > Medium > Low
2. **По фазе**: Phase A → Phase B → Phase C → Phase D → Phase E
3. **По ID**: Внутри фазы задачи выполняются по порядку ID

## Обновление статуса

При изменении статуса задачи:

1. Обновить поле **Status** в этой таблице
2. Если задача завершена: создать архив в `DOCS/TASK_ARCHIVE/[TaskID]_[TaskName]/`
3. Если задача заблокирована: добавить запись в `DOCS/INPROGRESS/blocked.md`
4. Если задача в процессе: создать файл в `DOCS/INPROGRESS/[TaskID]_*.md`

