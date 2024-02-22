<script setup>
import { BaseTree, Draggable, pro, OpenIcon } from '@he-tree/vue'
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import { ref, onMounted } from 'vue';

import '@he-tree/vue/style/default.css'
import '@he-tree/vue/style/material-design.css'
import Category from "@/components/Category.vue";

import { store } from './hooks/useTreeConfig.js'
import IndentInput from "@/components/IndentInput.vue";

let counter = ref(0)
const elements = ref([
  {
    id: 1,
    name: 'titre 1'
  },
  {
    id: 2,
    name: 'titre 2'
  }
])
const options = {}

onMounted(async () => {
  let request = new AjaxRequest(TYPO3.settings.ajaxUrls.category_treebuilder_index);
  const res = await request.get()
  const data = await res.resolve()
  elements.value = data.tree
})

const handleClick = (e) => {
  counter.value += 4
}
const handleChange = (e) => {
}
</script>

<template>
  <header>
  </header>

  <main>
    <IndentInput></IndentInput>
    <Draggable
        :indent="store.indent"
        class="mtl-tree"
        v-model="elements"
        treeLine
        @change="handleChange"
        @after-drop="handleChange"
        @before-drag-start="handleChange"
    >
      <template #default="{ node, stat }">
        <Category :node="node" :stat="stat"/>
      </template>
    </Draggable>
  </main>
</template>

<style scoped>
header {
  line-height: 1.5;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }
}
</style>
