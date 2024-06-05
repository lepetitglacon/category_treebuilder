<template>
    <div id="tree-div">
      <Nested :children="treeElements" />
    </div>

    <div>
      <button data-action="category_treebuilder_generatefakedata" @click="handleActionButton">Generate fake categories</button>
    </div>
</template>

<script setup>
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import { ref, onMounted } from 'vue';

import Category from "@/components/Category.vue";
import Nested from "@/components/Nested.vue";

const drag = ref(false)
const options = {}
const treeElements = ref([
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
  console.log(data)
  addRoot(data.tree)
  treeElements.value = data.tree
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

const handleActionButton = async (e) => {
  let request = new AjaxRequest(TYPO3.settings.ajaxUrls[e.target.dataset['action']]);
  const res = await request.get()
  const data = await res.resolve()
  console.log(data)
}
</script>

<style>
#app {
  display: flex;

  width: 100vw;
  height: 100vh;
}

#tree-div {
  width: 50vw;
}
</style>
