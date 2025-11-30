/**
 * Сервис для работы с backend API.
 */

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8080/api';

/**
 * Получить все задачи.
 * @returns {Promise<Array>} Список задач
 */
export async function getTodos() {
  // @todo PDD:30min Реализовать getTodos
  // Details: GET запрос к /api/todos, возвращает массив задач
  throw new Error('Not implemented');
}

/**
 * Создать новую задачу.
 * @param {string} title - Название задачи
 * @returns {Promise<Object>} Созданная задача
 */
export async function createTodo(title) {
  // @todo PDD:30min Реализовать createTodo
  // Details: POST запрос к /api/todos с { title }, возвращает созданную задачу
  throw new Error('Not implemented');
}

/**
 * Обновить задачу.
 * @param {number} id - ID задачи
 * @param {string} title - Новое название
 * @returns {Promise<Object>} Обновленная задача
 */
export async function updateTodo(id, title) {
  // @todo PDD:30min Реализовать updateTodo
  // Details: PUT запрос к /api/todos/{id} с { title }, возвращает обновленную задачу
  throw new Error('Not implemented');
}

/**
 * Изменить статус задачи.
 * @param {number} id - ID задачи
 * @param {string} status - Новый статус ('active' | 'completed')
 * @returns {Promise<Object>} Обновленная задача
 */
export async function toggleStatus(id, status) {
  // @todo PDD:30min Реализовать toggleStatus
  // Details: PATCH запрос к /api/todos/{id}/status с { status }, возвращает обновленную задачу
  throw new Error('Not implemented');
}

/**
 * Удалить задачу.
 * @param {number} id - ID задачи
 * @returns {Promise<void>}
 */
export async function deleteTodo(id) {
  // @todo PDD:30min Реализовать deleteTodo
  // Details: DELETE запрос к /api/todos/{id}
  throw new Error('Not implemented');
}

/**
 * Удалить все выполненные задачи.
 * @returns {Promise<number>} Количество удаленных задач
 */
export async function deleteCompleted() {
  // @todo PDD:30min Реализовать deleteCompleted
  // Details: DELETE запрос к /api/todos/completed, возвращает { deleted: number }
  throw new Error('Not implemented');
}






