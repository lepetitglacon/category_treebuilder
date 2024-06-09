<template>
	<draggable
    @update="handleMove"
    @change="handleMove"
    :move="onMove"
		class="draggable-item"
		tag="ul"
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

const lastMoveEvent = ref()

const props = defineProps({
  element: {},
  children: []
})

function onMove(e) {
  lastMoveEvent.value = e
}
async function handleMove(e) {
  console.log(e, lastMoveEvent.value)

  if (!e instanceof CustomEvent || e.removed === undefined) {
    return
  }

  const data = await makeT3PostRequest('category_treebuilder_update',
      {
        "category[__identity]": lastMoveEvent.value.draggedContext.element.uid,
        "category[parent]": lastMoveEvent.value.to.dataset.uid,
      }
  )
  console.log(data)
  if (data.reloadTree) {
    await loadTree()
  }
}

</script>

<style scoped>
.draggable-item {
	//outline: 1px dashed #00bd7e;
}
</style>