<template>
  <ContextMenuRoot>
    <ContextMenuTrigger
        as-child
        class=""
    >
      <slot></slot>
    </ContextMenuTrigger>

    <ContextMenuPortal>
      <ContextMenuContent
          class="dropdown-menu"
          :side-offset="5"
      >
        <ContextMenuItem value="New Tab">
          <DialogTrigger
              as="div"
              class="context-trigger dropdown-item"
              @click="changeContextMenuValues"
          >
            Modify
          </DialogTrigger>
        </ContextMenuItem>

        <ContextMenuSeparator as="hr" class="dropdown-divider" />

        <ContextMenuItem value="New Tab" @click="handleClick">
          <div>New Category (inside)</div>
        </ContextMenuItem>
        <ContextMenuItem value="New Tab" @click="handleClick">
          <div>New Category (after)</div>
        </ContextMenuItem>

        <ContextMenuSeparator as="hr" class="dropdown-divider" />

        <ContextMenuItem value="New Tab" @click="handleClick">
          <div>New Categories (inside)</div>
        </ContextMenuItem>
        <ContextMenuItem value="New Tab" @click="handleClick">
          <div>New Categories (after)</div>
        </ContextMenuItem>

        <ContextMenuSeparator as="hr" class="dropdown-divider" />

        <ContextMenuItem value="New Tab" @click="handleClick">
          <div class="btn-danger">Delete</div>
        </ContextMenuItem>

      </ContextMenuContent>
    </ContextMenuPortal>
  </ContextMenuRoot>
</template>

<script setup lang="ts">

import {
  ContextMenuContent,
  ContextMenuItem,
  ContextMenuPortal,
  ContextMenuRoot,
  ContextMenuSeparator,
  ContextMenuTrigger,
  DialogTrigger
} from 'radix-vue'
import {ref} from "vue";
import {useContextMenu} from '../composables/useContextMenu.js'
const {currentCategory} = useContextMenu()

const props = defineProps({
  category: {}
})


const open = ref(false)


function changeContextMenuValues(e) {
  currentCategory.value = props.category
}
function handleClickModify(e) {
  console.log(e, props.category)
}

function handleClick() {
  alert('hello!')
}
</script>

<style>
/* Target the menu content */
[data-radix-popper-content-wrapper] {
  min-width: 2000px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 8px;
}

/* Style the menu items */
[data-radix-vue-collection-item] {
  cursor: pointer;
  padding: 4px 8px;
  white-space: nowrap;
  text-align: left;
}

/* Style the menu items on hover */
[data-radix-vue-collection-item]:hover {
  background-color: #333;
  color: white;
}

.context-trigger {
  position: relative;
  background: none;
  color: inherit;
  border: none;
  padding: 0;
  font: inherit;
  cursor: pointer;
  outline: inherit;
}

</style>