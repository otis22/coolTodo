/**
 * Сервис для работы с backend API.
 */

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8080/api';

/**
 * Получить все задачи.
 * @returns {Promise<Array>} Список задач
 */
export async function getTodos() {
  const response = await fetch(`${API_BASE_URL}/todos`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
    },
  });

  if (!response.ok) {
    throw new Error(`Failed to fetch todos: ${response.statusText}`);
  }

  return await response.json();
}

/**
 * Создать новую задачу.
 * @param {string} title - Название задачи
 * @returns {Promise<Object>} Созданная задача
 */
export async function createTodo(title) {
  const response = await fetch(`${API_BASE_URL}/todos`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ title }),
  });

  if (!response.ok) {
    if (response.status === 422) {
      const errors = await response.json();
      throw new Error(`Validation failed: ${JSON.stringify(errors)}`);
    }
    throw new Error(`Failed to create todo: ${response.statusText}`);
  }

  return await response.json();
}

/**
 * Обновить задачу.
 * @param {number} id - ID задачи
 * @param {string} title - Новое название
 * @returns {Promise<Object>} Обновленная задача
 */
export async function updateTodo(id, title) {
  const response = await fetch(`${API_BASE_URL}/todos/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ title }),
  });

  if (!response.ok) {
    if (response.status === 404) {
      const error = await response.json();
      throw new Error(error.error || 'Todo not found');
    }
    if (response.status === 422) {
      const errors = await response.json();
      throw new Error(`Validation failed: ${JSON.stringify(errors)}`);
    }
    throw new Error(`Failed to update todo: ${response.statusText}`);
  }

  return await response.json();
}

/**
 * Переключить статус задачи.
 * @param {number} id - ID задачи
 * @returns {Promise<Object>} Обновленная задача
 */
export async function toggleStatus(id) {
  const response = await fetch(`${API_BASE_URL}/todos/${id}/status`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
    },
  });

  if (!response.ok) {
    if (response.status === 404) {
      const error = await response.json();
      throw new Error(error.error || 'Todo not found');
    }
    throw new Error(`Failed to toggle todo status: ${response.statusText}`);
  }

  return await response.json();
}

/**
 * Удалить задачу.
 * @param {number} id - ID задачи
 * @returns {Promise<void>}
 */
export async function deleteTodo(id) {
  const response = await fetch(`${API_BASE_URL}/todos/${id}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
    },
  });

  if (!response.ok) {
    if (response.status === 404) {
      const error = await response.json();
      throw new Error(error.error || 'Todo not found');
    }
    throw new Error(`Failed to delete todo: ${response.statusText}`);
  }
}

/**
 * Удалить все выполненные задачи.
 * @returns {Promise<number>} Количество удаленных задач
 */
export async function deleteCompleted() {
  const response = await fetch(`${API_BASE_URL}/todos/completed`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
    },
  });

  if (!response.ok) {
    throw new Error(`Failed to delete completed todos: ${response.statusText}`);
  }

  const result = await response.json();
  return result.deleted || 0;
}







