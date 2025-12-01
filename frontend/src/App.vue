<template>
  <div id="app">
    <h1>CoolTodo</h1>
    <TodoInput @created="handleTodoCreated" />
    <TodoList ref="todoListRef" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import TodoInput from './components/TodoInput.vue';
import TodoList from './components/TodoList.vue';

const todoListRef = ref(null);

function handleTodoCreated(newTodo) {
  // Уведомляем TodoList о новой задаче
  if (todoListRef.value) {
    todoListRef.value.addTodo(newTodo);
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>






