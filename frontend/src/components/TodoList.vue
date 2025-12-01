<template>
  <div class="todo-list">
    <div v-if="isLoading" class="loading">Загрузка...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="todos.length === 0" class="empty">Нет задач</div>
    <div v-else class="todos">
      <TodoItem
        v-for="todo in todos"
        :key="todo.id"
        :todo="todo"
        @updated="handleTodoUpdated"
        @deleted="handleTodoDeleted"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getTodos } from '../services/api.js';
import TodoItem from './TodoItem.vue';

const todos = ref([]);
const isLoading = ref(false);
const error = ref(null);

async function loadTodos() {
  isLoading.value = true;
  error.value = null;

  try {
    todos.value = await getTodos();
  } catch (err) {
    error.value = err.message || 'Ошибка при загрузке задач';
    console.error('Failed to load todos:', err);
  } finally {
    isLoading.value = false;
  }
}

function handleTodoUpdated(updatedTodo) {
  const index = todos.value.findIndex((todo) => todo.id === updatedTodo.id);
  if (index !== -1) {
    todos.value[index] = updatedTodo;
  }
}

function handleTodoDeleted(todoId) {
  todos.value = todos.value.filter((todo) => todo.id !== todoId);
}

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
</style>







