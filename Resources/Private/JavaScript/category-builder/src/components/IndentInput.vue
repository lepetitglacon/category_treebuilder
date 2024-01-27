<script setup>
import { store } from '../hooks/useTreeConfig.js'

const handleChange = (e) => {
  console.log(e)
  store.setIndent(e.target.value)
}
</script>

<template>
  <label>
    <input type="range" name="indent" id="indent_input" step="1" min="20" max="200" :value="store.indent" @input="handleChange">
  </label>
</template>

<style scoped>
</style>
