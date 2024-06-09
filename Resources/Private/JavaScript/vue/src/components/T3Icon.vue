<template>
  <span v-html="getIcon(name)"></span>
</template>

<script setup>
import useT3Icons from '../composables/useT3Icons.js'
const {getIcon} = useT3Icons()

const props = defineProps({
  name: undefined
})
</script>