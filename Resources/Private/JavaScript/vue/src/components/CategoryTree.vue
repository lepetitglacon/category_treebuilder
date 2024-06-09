<template>

	<draggable
    @update="handleMove"
    @change="handleMove"
    :move="onMove"
		class="draggable-item"
		tag="ul"
    filter=".category-disabled"
		:list="children"
		:group="{ name: 'g1' }"
		item-key="uid"
    :data-uid="element?.uid ?? 0"
	>
    <template #item="{ element, index }">

      <CollapsibleChild
        :element="element"
      />

		</template>
	</draggable>

</template>

<script setup>
import draggable from 'vuedraggable'
import CollapsibleChild from "@/components/CollapsibleChild.vue";
import {ref} from "vue";
import useT3Api from "@/composables/useT3Api.js";
import {useCategoryTree} from "@/composables/useCategoryTree.js";
const {makeT3Request, makeT3PostRequest} = useT3Api()
const {tree, loadTree} = useCategoryTree()

const lastSortableMoveEvent = ref()

const props = defineProps({
  element: {},
  children: []
})

function onMove(e) {
  lastSortableMoveEvent.value = e
}
async function handleMove(e) {
  console.log(e, lastSortableMoveEvent.value)

  if (!e instanceof CustomEvent || e.removed === undefined) {
    return
  }

  const data = await makeT3PostRequest('category_treebuilder_update',
      {
        "category[__identity]": lastSortableMoveEvent.value.draggedContext.element.uid,
        "category[parent]": lastSortableMoveEvent.value.to.dataset.uid,
      }
  )
}

</script>

<style scoped>
.draggable-item {
	/* outline: 1px dashed #00bd7e; */
}
</style>