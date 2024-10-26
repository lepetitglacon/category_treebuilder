<template>

    <ContextMenu :category="element">
      <p class="category" >
        <T3Icon v-if="element.parent == 0" title="Is at root level" name="apps-pagetree-folder-root"/>
        <span v-if="element.parent == 0">  </span>
        <T3Icon :title="'uid=' + element.uid + ' pid=' + element.pid" name="mimetypes-x-sys_category"/>
        {{ element.title }}
      </p>
    </ContextMenu>


</template>

<script setup>
import ContextMenu from "@/components/ContextMenu.vue";
import T3Icon from "@/components/T3Icon.vue";

const props = defineProps({
  element: {}
})

</script>

<style>
.category {
  padding-bottom: 0;
  margin-bottom: 0;
  word-break: keep-all;
}

.category-disabled {
  pointer-events: none;
  cursor: default;
}
.category-disabled:hover {
  background: initial;
}
</style>
