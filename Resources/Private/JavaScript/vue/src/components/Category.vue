<template>

    <ContextMenu :category="element">
      <p class="category">
        <span :title="element.uid" v-html="categoryIconHtml"/>
        {{ element.title }}
      </p>
    </ContextMenu>


</template>

<script setup>
import Icons from '@typo3/backend/icons.js';
import { ref } from 'vue';
import ContextMenu from "@/components/ContextMenu.vue";
import {ContextMenuRoot} from "radix-vue";

const props = defineProps({
  element: {}
})

let categoryIconHtml = ref('')
Icons.getIcon('mimetypes-x-sys_category', Icons.sizes.small, null, 'disabled').then((icon) => {
  categoryIconHtml.value = icon
});

</script>

<style scoped>
.category {
  padding-bottom: 0;
  margin-bottom: 0;
  word-break: keep-all;
}
</style>
