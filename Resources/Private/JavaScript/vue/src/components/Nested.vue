<template>
	<draggable
    @update="handleChange"
    @change="handleChange"
		class="draggable-item"
		tag="ul"
		:list="children"
		:group="{ name: 'g1' }"
		item-key="uid"
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

const props = defineProps({
  children: []
})

function handleChange(e) {
  console.log(e)
}

</script>

<style scoped>
.draggable-item {
	outline: 1px dashed #00bd7e;
}
</style>