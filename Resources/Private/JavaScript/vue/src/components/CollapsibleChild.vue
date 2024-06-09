<template>
  <CollapsibleRoot v-model:open="open" class="">

    <div class="callapsible-container" style="display: flex; align-items: center">

      <div class="callapsible-trigger-container">
        <CollapsibleTrigger
            v-if="element.children"
            class="collapsible-trigger"
            :class="open && 'collapsed'">
          <T3Icon :name="'actions-chevron-right'"/>
        </CollapsibleTrigger>

        <button v-else class="collapsible-trigger collapsible-trigger-placeholder">
          <T3Icon :name="'miscellaneous-placeholder'"/>
        </button>
      </div>

      <div class="category-container">
        <Category :element="element"/>
      </div>

    </div>

    <CollapsibleContent class="">

      <li>
        <Nested v-if="element.children" :element="element" :children="element.children" />

        <!-- permet de pouvoir ajouter des enfants quand il n'y en a pas -->
        <!--				<Nested v-else :children="element.children" :class="'empty-draggable-item'" />-->
      </li>
    </CollapsibleContent>

  </CollapsibleRoot>
</template>

<script setup>
import Icons from '@typo3/backend/icons.js';
import Nested from "@/components/Nested.vue";
import {CollapsibleContent, CollapsibleRoot, CollapsibleTrigger} from "radix-vue";
import Category from "@/components/Category.vue";
import {onMounted, ref, watch} from "vue";
import {useCollapsibleRefs} from "@/composables/useCollapsibleRefs.js";
import T3Icon from "@/components/T3Icon.vue";
const {createRef, addRef, getRefs} = useCollapsibleRefs()

const props = defineProps({
  element: {}
})

const open = createRef(props.element.uid)

let categoryCollapsibleIconHtml = ref('')
Icons.getIcon('', Icons.sizes.small, null, 'disabled').then((icon) => {
  categoryCollapsibleIconHtml.value = icon
});

let emptyIconHtml = ref('')
Icons.getIcon('miscellaneous-placeholder', Icons.sizes.small, null, 'disabled').then((icon) => {
  emptyIconHtml.value = icon
});
</script>

<style scoped>

.category-container {
  padding: 2px;
  border-radius: 3px;
  cursor: pointer;
}
.category-container:hover {
  background: #eee;
}
.callapsible-container {
  position: relative;
}
.collapsible-trigger {
  position: relative;
  background: none;
  color: inherit;
  border: none;
  padding: 0;
  font: inherit;
  cursor: pointer;
  outline: inherit;
}
.collapsible-trigger-placeholder {
  cursor: default;
}
.collapsed {
  transform: rotate(90deg);
}
</style>