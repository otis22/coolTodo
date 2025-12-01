<template>
  <div class="todo-item" :class="{ completed: isCompleted }">
    <input
      type="checkbox"
      :checked="isCompleted"
      @change="handleToggleStatus"
      class="todo-checkbox"
      :disabled="isLoading"
    />
    <span class="todo-title">{{ todo.title }}</span>
    <button
      class="todo-delete"
      @click="handleDelete"
      :disabled="isLoading"
      aria-label="Delete todo"
    >
      ×
    </button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { toggleStatus, deleteTodo } from '../services/api.js';

const props = defineProps({
  todo: {
    type: Object,
    required: true,
    validator: (value) => {
      return value.id !== undefined && value.title !== undefined && value.status !== undefined;
    },
  },
});

const emit = defineEmits(['updated', 'deleted']);

const isLoading = ref(false);

const isCompleted = computed(() => props.todo.status === 'completed');

async function handleToggleStatus() {
  if (isLoading.value) return;

  try {
    isLoading.value = true;
    const updatedTodo = await toggleStatus(props.todo.id);
    emit('updated', updatedTodo);
  } catch (error) {
    console.error('Failed to toggle todo status:', error);
    alert(`Ошибка при изменении статуса: ${error.message}`);
  } finally {
    isLoading.value = false;
  }
}

async function handleDelete() {
  if (isLoading.value) return;
  if (!confirm('Удалить задачу?')) return;

  try {
    isLoading.value = true;
    await deleteTodo(props.todo.id);
    emit('deleted', props.todo.id);
  } catch (error) {
    console.error('Failed to delete todo:', error);
    alert(`Ошибка при удалении: ${error.message}`);
  } finally {
    isLoading.value = false;
  }
}
</script>

<style scoped>
.todo-item {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid #e6e6e6;
  transition: background-color 0.2s;
  position: relative;
}

.todo-item:hover {
  background-color: #f5f5f5;
}

.todo-item.completed {
  opacity: 0.6;
}

.todo-checkbox {
  width: 24px;
  height: 24px;
  margin-right: 12px;
  cursor: pointer;
  flex-shrink: 0;
}

.todo-checkbox:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

.todo-title {
  flex: 1;
  font-size: 24px;
  line-height: 1.4;
  color: #4d4d4d;
  word-break: break-word;
  transition: text-decoration 0.2s, color 0.2s;
}

.todo-item.completed .todo-title {
  text-decoration: line-through;
  color: #d9d9d9;
}

.todo-delete {
  display: none;
  width: 32px;
  height: 32px;
  font-size: 28px;
  line-height: 1;
  color: #cc9a9a;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  margin-left: 12px;
  flex-shrink: 0;
  transition: color 0.2s;
}

.todo-item:hover .todo-delete {
  display: block;
}

.todo-delete:hover {
  color: #af5b5e;
}

.todo-delete:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}
</style>







