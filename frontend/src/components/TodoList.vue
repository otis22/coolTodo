<template>
  <div class="todo-list">
    <div v-if="isLoading" class="loading">Загрузка...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="allTodos.length === 0" class="empty">Нет задач</div>
    <template v-else>
      <div class="todos">
        <TodoItem
          v-for="todo in filteredTodos"
          :key="todo.id"
          :todo="todo"
          @updated="handleTodoUpdated"
          @deleted="handleTodoDeleted"
        />
      </div>
      <div class="footer">
        <span class="counter">{{ activeTodosCount }} {{ activeTodosCount === 1 ? 'item' : 'items' }} left</span>
        <div class="footer-actions">
          <button
            class="refresh-btn"
            @click="loadTodos"
            :disabled="isLoading"
            title="Обновить список задач"
          >
            ↻
          </button>
          <button
            v-if="completedTodosCount > 0"
            class="clear-completed-btn"
            @click="handleClearCompleted"
            :disabled="isLoading"
          >
            Clear completed
          </button>
          <div class="filters">
        <button
          class="filter-btn"
          :class="{ active: currentFilter === 'all' }"
          @click="currentFilter = 'all'"
        >
          All
        </button>
        <button
          class="filter-btn"
          :class="{ active: currentFilter === 'active' }"
          @click="currentFilter = 'active'"
        >
          Active
        </button>
        <button
          class="filter-btn"
          :class="{ active: currentFilter === 'completed' }"
          @click="currentFilter = 'completed'"
        >
          Completed
        </button>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, defineExpose } from 'vue';
import { getTodos, deleteCompleted } from '../services/api.js';
import TodoItem from './TodoItem.vue';

const allTodos = ref([]);
const isLoading = ref(false);
const error = ref(null);
const currentFilter = ref('all');

const filteredTodos = computed(() => {
  switch (currentFilter.value) {
    case 'active':
      return allTodos.value.filter((todo) => todo.status === 'active');
    case 'completed':
      return allTodos.value.filter((todo) => todo.status === 'completed');
    case 'all':
    default:
      return allTodos.value;
  }
});

const activeTodosCount = computed(() => {
  return allTodos.value.filter((todo) => todo.status === 'active').length;
});

const completedTodosCount = computed(() => {
  return allTodos.value.filter((todo) => todo.status === 'completed').length;
});

async function loadTodos() {
  isLoading.value = true;
  error.value = null;

  try {
    allTodos.value = await getTodos();
  } catch (err) {
    error.value = err.message || 'Ошибка при загрузке задач';
    console.error('Failed to load todos:', err);
  } finally {
    isLoading.value = false;
  }
}

function handleTodoUpdated(updatedTodo) {
  const index = allTodos.value.findIndex((todo) => todo.id === updatedTodo.id);
  if (index !== -1) {
    allTodos.value[index] = updatedTodo;
  }
}

function handleTodoDeleted(todoId) {
  allTodos.value = allTodos.value.filter((todo) => todo.id !== todoId);
}

async function handleClearCompleted() {
  if (isLoading.value || completedTodosCount.value === 0) return;
  
  if (!confirm(`Удалить ${completedTodosCount.value} завершенную задачу?`)) return;
  
  try {
    isLoading.value = true;
    await deleteCompleted();
    // Удаляем completed задачи из локального списка
    allTodos.value = allTodos.value.filter((todo) => todo.status !== 'completed');
  } catch (error) {
    console.error('Failed to clear completed todos:', error);
    alert(`Ошибка при удалении: ${error.message}`);
  } finally {
    isLoading.value = false;
  }
}

function addTodo(newTodo) {
  // Добавляем новую задачу в начало списка
  allTodos.value.unshift(newTodo);
}

// Экспортируем методы для использования из родительского компонента
defineExpose({
  addTodo,
  refresh: loadTodos,
});

onMounted(() => {
  loadTodos();
});
</script>

<style scoped>
.todo-list {
  max-width: 550px;
  margin: 0 auto;
  background: #fff;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 25px 50px 0 rgba(0, 0, 0, 0.1);
}

.loading,
.error,
.empty {
  padding: 20px;
  text-align: center;
  color: #777;
  font-size: 18px;
}

.error {
  color: #cc0000;
}

.todos {
  list-style: none;
  margin: 0;
  padding: 0;
}

.footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  border-top: 1px solid #e6e6e6;
  background-color: #fff;
  font-size: 14px;
}

.counter {
  color: #777;
}

.footer-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.clear-completed-btn {
  padding: 6px 12px;
  border: 1px solid transparent;
  background: none;
  color: #777;
  font-size: 14px;
  cursor: pointer;
  border-radius: 3px;
  transition: all 0.2s;
}

.clear-completed-btn:hover {
  text-decoration: underline;
  color: #333;
}

.clear-completed-btn:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

.refresh-btn {
  padding: 6px 12px;
  border: 1px solid transparent;
  background: none;
  color: #777;
  font-size: 18px;
  cursor: pointer;
  border-radius: 3px;
  transition: all 0.2s;
  line-height: 1;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.refresh-btn:hover {
  border-color: #e6e6e6;
  color: #333;
  transform: rotate(180deg);
}

.refresh-btn:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

.filters {
  display: flex;
  gap: 8px;
}

.filter-btn {
  padding: 6px 12px;
  border: 1px solid transparent;
  background: none;
  color: #777;
  font-size: 14px;
  cursor: pointer;
  border-radius: 3px;
  transition: all 0.2s;
}

.filter-btn:hover {
  border-color: #e6e6e6;
  color: #333;
}

.filter-btn.active {
  border-color: #ce4646;
  color: #ce4646;
  font-weight: 500;
}
</style>







