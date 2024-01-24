<script setup>
import { BaseTree, Draggable, pro, OpenIcon } from '@he-tree/vue'
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import Icons from '@typo3/backend/icons.js';
import { ref, onMounted } from 'vue';

import '@he-tree/vue/style/default.css'
import '@he-tree/vue/style/material-design.css'

Icons.getIcon('mimetypes-x-sys_category', Icons.sizes.small, null, 'disabled').then((icon) => {
  category_icon.value = icon
});

let counter = ref(0)
let category_icon = ref('')
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
  console.log(res)
  const data = await res.resolve()
  elements.value = data.tree
  console.log(data)
})

const handleClick = (e) => {
  console.log(e)
  counter.value += 4
}
const handleChange = (e) => {
  console.log(e)
}
</script>

<template>
  <header>
  </header>

  <main>
    <Draggable
        indent="110"
        class="mtl-tree"
        v-model="elements"
        treeLine
        @change="handleChange"
        @after-drop="handleChange"
        @before-drag-start="handleChange"
    >
      <template #default="{ node, stat }">
        <OpenIcon
            v-if="stat.children.length"
            :open="stat.open"
            class="mtl-mr"
            @click.native="stat.open = !stat.open"
        />
        <input
            class="mtl-checkbox mtl-mr"
            type="checkbox"
            v-model="stat.checked"
        />
        <span v-html="category_icon"></span>
        <span class="mtl-ml">{{ node.title }}</span>
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
