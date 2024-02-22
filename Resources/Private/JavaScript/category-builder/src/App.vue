<script setup>
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import { ref, onMounted } from 'vue';

import Category from "@/components/Category.vue";
import Nested from "@/components/Nested.vue";

const drag = ref(false)
const options = {}
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

onMounted(async () => {
  let request = new AjaxRequest(TYPO3.settings.ajaxUrls.category_treebuilder_index);
  const res = await request.get()
  const data = await res.resolve()
  addRoot(data.tree)
  elements.value = data.tree
})

function addRoot(tree) {
	tree.unshift({
		uid: 0,
		title: 'Root',
		parent: null
	})
}

const onTreeUpdate = (e) => {

}
</script>

<template>
  <header>
  </header>

  <main>

	  <Nested :children="elements" />

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
