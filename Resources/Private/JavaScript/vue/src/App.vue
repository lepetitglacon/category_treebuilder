<template>
    <div id="tree-div" >
      <Nested v-if="treeElements.length > 0" :children="treeElements" />
      <p v-else>Loading</p>
    </div>

    <div id="actions">
      <button data-action="category_treebuilder_generatefakedata" @click="handleActionButton">Generate fake categories</button>
      <button data-action="category_treebuilder_deleteall" @click="handleActionButton">Delete all categories</button>
    </div>
</template>

<script setup>
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import { ref, onMounted } from 'vue';
import Category from "@/components/Category.vue";
import Nested from "@/components/Nested.vue";
import {useToast} from "vue-toast-notification";
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();

const treeElements = ref([])

onMounted(async () => {
  loadTree()
})

const handleActionButton = async (e) => {
  const data = makeT3Request(e.target.dataset['action'])
  console.log(data)
}

async function loadTree() {
  const data = await makeT3Request('category_treebuilder_index')
  treeElements.value = data.data
}

async function makeT3Request(endpoint) {
  let request = new AjaxRequest(TYPO3.settings.ajaxUrls[endpoint]);
  const res = await request.get()
  const data = await res.resolve()
  if (data.status && data.message) {
    $toast[data.status](data.message)
  }
  if (data.reloadTree) {
    loadTree()
  }
  return data
}
</script>

<style>
#app {
  display: flex;

  width: 100vw;
  height: 100vh;
}

#actions {
  display: flex;
  flex-direction: column;
}

#tree-div {
  width: 50vw;
}
</style>
