<template>

  <DialogRoot>


    <DialogPortal class="dialog-portal">
      <DialogOverlay class="dialog-overlay" />
      <DialogContent
          class="dialog-content"
      >
        <DialogTitle class="text-mauve12 m-0 text-[17px] font-semibold">
          Modify category
        </DialogTitle>
        <DialogDescription class="text-mauve11 mt-[10px] mb-5 text-[15px] leading-normal">
          todo
        </DialogDescription>

        <form ref="form">
          <div>
            <label class="" for="name">Title</label>
            <input id="title" name="category[title]" class="" :value="currentCategory.title">
          </div>
          <div>
            <label class="" for="name">Uid</label>
            <input id="title" name="category[uid]" class="" :value="currentCategory.uid">
          </div>
          <div>
            <label class="" for="name">Pid</label>
            <input id="title" name="category[pid]" class="" :value="currentCategory.pid">
          </div>
          <input type="hidden" name="category[__identity]" :value="currentCategory.uid">

          <div class="mt-[25px] flex justify-end">
            <DialogClose as-child>
              <button type="submit" class="" @click.prevent="handleCategoryUpdate">
                Update
              </button>
            </DialogClose>
          </div>
        </form>
        <DialogClose
            class="dialog-close-button"
            aria-label="Close"
        >
          X
        </DialogClose>
      </DialogContent>
    </DialogPortal>

    <div id="tree-div" >
      <Nested v-if="treeElements.length > 0" :children="treeElements" />
      <p v-else>Loading</p>
    </div>

    <div id="actions">
      <button data-action="category_treebuilder_generatefakedata" @click="handleActionButton">Generate fake categories</button>
      <button data-action="category_treebuilder_deleteall" @click="handleActionButton">Delete all categories</button>
    </div>

  </DialogRoot>
</template>

<script setup>
import 'vue-toast-notification/dist/theme-sugar.css';
import { ref, onMounted } from 'vue';
import Nested from "@/components/Nested.vue";
import useT3Api from "@/composables/useT3Api.js";
import {
  ContextMenuContent,
  ContextMenuItem, ContextMenuPortal,
  ContextMenuRoot, ContextMenuSeparator,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogOverlay,
  DialogPortal,
  DialogRoot,
  DialogTitle, DialogTrigger
} from "radix-vue";

import {useContextMenu} from './composables/useContextMenu.js'
const {currentCategory} = useContextMenu()

const {makeT3Request, makeT3PostRequest} = useT3Api()
const treeElements = ref([])
const form = ref()

onMounted(async () => {
  loadTree()
})

async function handleCategoryUpdate(e) {

  const inputData = {}
  for (const [key, val] of new FormData(form.value).entries()) {
    inputData[key] = val
  }
  const data = await makeT3PostRequest('category_treebuilder_update', inputData)
  if (data.reloadTree) {
    await loadTree()
  }
}

const handleActionButton = async (e) => {
  const data = makeT3Request(e.target.dataset['action'])
  if (data.reloadTree) {
    await loadTree()
  }
}

async function loadTree() {
  const data = await makeT3Request('category_treebuilder_index')
  treeElements.value = data.data
}

</script>

<style>
/* Style the dialog overlay */
.dialog-overlay {
  background-color: rgba(0, 0, 0, 0.5); /* Set overlay opacity */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 100; /* Ensure overlay is above other elements */
}

/* Style the dialog container */
.dialog-content {
  background-color: #fff; /* Set dialog background color */
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%); /* Center the dialog */
  padding: 20px;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2); /* Add a shadow effect */
  width: fit-content; /* Adjust width as needed */
  max-width: 800px; /* Set a maximum width */
  z-index: 150;
}

/* Style the dialog title */
[data-radix-dialog-title] {
  font-size: 1.2em;
  font-weight: bold;
  margin-bottom: 10px;
}

/* Style the dialog content */
.dialog-content {
  /* Add styles for the dialog content here */
}

/* Style the dialog close button */
.dialog-close-button {
  cursor: pointer;
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 5px;
  border: none;
  background-color: transparent;
  color: #999;
  font-size: 16px;
}

/* Style the dialog close button on hover */
.dialog-close-button:hover {
  color: #000;
}



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
