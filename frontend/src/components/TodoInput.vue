<template>
  <div class="todo-input">
    <input
      ref="inputRef"
      v-model="title"
      type="text"
      class="todo-input-field"
      placeholder="What needs to be done?"
      @keydown.enter="handleSubmit"
      :disabled="isLoading"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { createTodo } from '../services/api.js';

const emit = defineEmits(['created']);

const title = ref('');
const isLoading = ref(false);
const inputRef = ref(null);

async function handleSubmit() {
  const trimmedTitle = title.value.trim();
  
  if (trimmedTitle === '') {
    return;
  }
  
  if (isLoading.value) {
    return;
  }
  
  try {
    isLoading.value = true;
    const newTodo = await createTodo(trimmedTitle);
    emit('created', newTodo);
    title.value = '';
    // Возвращаем фокус на поле ввода
    if (inputRef.value) {
      inputRef.value.focus();
    }
  } catch (error) {
    console.error('Failed to create todo:', error);
    alert(`Ошибка при создании задачи: ${error.message}`);
  } finally {
    isLoading.value = false;
  }
}
</script>

<style scoped>
.todo-input {
  max-width: 550px;
  margin: 0 auto 0 auto;
  background: #fff;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 25px 50px 0 rgba(0, 0, 0, 0.1);
  padding: 16px;
  border-bottom: 1px solid #e6e6e6;
}

.todo-input-field {
  width: 100%;
  font-size: 24px;
  line-height: 1.4;
  color: #4d4d4d;
  padding: 16px 16px 16px 60px;
  border: none;
  background: rgba(0, 0, 0, 0.003);
  box-shadow: inset 0 -2px 1px rgba(0, 0, 0, 0.03);
  outline: none;
  box-sizing: border-box;
}

.todo-input-field::placeholder {
  color: #e6e6e6;
  font-style: italic;
}

.todo-input-field:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.todo-input-field:focus {
  box-shadow: inset 0 -2px 1px rgba(0, 0, 0, 0.03), 0 0 2px rgba(0, 0, 0, 0.1);
}
</style>

