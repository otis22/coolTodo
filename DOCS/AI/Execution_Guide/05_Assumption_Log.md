# Assumption Log

## Purpose

This log tracks assumptions made during development, failed approaches, and lessons learned to prevent repeating mistakes and inform future decisions.

## Log Entries

### Entry Template

| Date | Task ID | Assumption/Approach | Outcome | Lesson Learned | Alternative Taken |
|------|---------|---------------------|---------|----------------|-------------------|
| YYYY-MM-DD | [Task ID] | [What was tried] | [Success/Failure] | [Why it failed/succeeded] | [What was done instead] |

### Example Entry

| Date | Task ID | Assumption/Approach | Outcome | Lesson Learned | Alternative Taken |
|------|---------|---------------------|---------|----------------|-------------------|
| 2025-10-05 | A3 | Assumed JWT tokens could be stored in localStorage | Failed | Security vulnerability: XSS attacks can steal tokens | Switched to httpOnly cookies |

### Entry 1: A22 - Автоматическое форматирование кода

| Date | Task ID | Assumption/Approach | Outcome | Lesson Learned | Alternative Taken |
|------|---------|---------------------|---------|----------------|-------------------|
| 2025-01-27 | A22 | Предполагалось, что задача A22 требует ручного исправления форматирования 11 файлов | Success (Auto-resolved) | Задача была автоматически решена при выполнении предыдущих задач (A27, A28), которые настраивали PHP-CS-Fixer и применяли форматирование | Проверка показала, что все файлы уже отформатированы (0 из 17 требуют исправления). Задача помечена как Completed без дополнительных действий |

### Entry 2: B4 - Приоритизация задач по приоритету над фазой

| Date | Task ID | Assumption/Approach | Outcome | Lesson Learned | Alternative Taken |
|------|---------|---------------------|---------|----------------|-------------------|
| 2025-01-27 | B4 | При выборе следующей задачи применена иерархия: приоритет (High > Low) важнее фазы (Phase A vs Phase B) | Success | Задача B4 (High, Phase B) выбрана вместо A22 (Low, Phase A), так как приоритет имеет больший вес в алгоритме выбора. Это правильное решение, так как B4 блокирует последующие задачи (B5-B7) | Выбрана задача B4: Реализовать Use Cases (Create, Update, Delete, Toggle) для продвижения основного функционала проекта |

## Active Assumptions

(Список текущих предположений, которые требуют валидации)

- [ ] Assumption 1: [Описание]
- [ ] Assumption 2: [Описание]

## Notes

Этот журнал должен обновляться после каждой задачи, где были сделаны важные предположения или обнаружены неудачные подходы.

**Правило**: Если подход не сработал или предположение оказалось неверным, **ОБЯЗАТЕЛЬНО** добавить запись в этот журнал.







