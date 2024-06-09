<template>

  <DialogRoot>

    <div id="tree-div" >
          <!-- TODO -->
<!--      <div>-->
<!--        <button @click="collapse">Collapse</button>-->
<!--        <button @click="expend">Expend</button>-->
<!--      </div>-->

      <Nested v-if="tree.length > 0" :children="tree.filter(filterBySearch)" />
      <p v-else>Loading Tree</p>
    </div>

    <div id="actions">
      <button data-action="category_treebuilder_generatefakedata" @click="handleActionButton">Generate fake categories</button>
      <button data-action="category_treebuilder_deleteall" @click="handleActionButton">Delete all categories</button>
    </div>

    <UpdatePopin/>

  </DialogRoot>
</template>

<script setup>
import './assets/main.css';
import 'vue-toast-notification/dist/theme-sugar.css';
import { ref, onMounted } from 'vue';
import Nested from "@/components/Nested.vue";
import useT3Api from "@/composables/useT3Api.js";
import {useCategoryTree} from "@/composables/useCategoryTree.js";
import {useCollapsibleRefs} from "@/composables/useCollapsibleRefs.js";
import {DialogRoot} from "radix-vue";
import UpdatePopin from "@/components/UpdatePopin.vue";

const {tree, loadTree} = useCategoryTree()
const {collapse, expend} = useCollapsibleRefs()

const {makeT3Request, makeT3PostRequest} = useT3Api()

onMounted(async () => {
  loadTree()
})

function filterBySearch(item) {
  return item
}

const handleActionButton = async (e) => {
  const data = makeT3Request(e.target.dataset['action'])
  if (data.reloadTree) {
    await loadTree()
  }
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
