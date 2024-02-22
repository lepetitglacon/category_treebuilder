<script setup>
import draggable from 'vuedraggable'

const props = defineProps({
	children: []
})
</script>

<template>
	<draggable
		class="dragArea"
		tag="ul"
		:list="children"
		:group="{ name: 'g1' }"
		item-key="uid"
	>
		<template #item="{ element }">
			<li>
				<p>{{ element.title }}</p>
				<Nested :children="element.children" />
			</li>
		</template>
	</draggable>
</template>

<style scoped>
.dragArea {
	min-height: 50px;
	outline: 1px dashed;
}
</style>