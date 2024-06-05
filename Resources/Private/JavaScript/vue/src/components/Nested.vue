<script setup>
import draggable from 'vuedraggable'
import {ref} from "vue";
import Icons from '@typo3/backend/icons.js';

let category_icon = ref('')
Icons.getIcon('mimetypes-x-sys_category', Icons.sizes.small, null, 'disabled').then((icon) => {
  category_icon.value = icon
});

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
        <p><span v-html="category_icon"></span>{{ element.title }}</p>
				<Nested :children="element.children" />
			</li>
		</template>
	</draggable>
</template>

<style scoped>
.dragArea {
	min-height: 30px;
	//outline: 1px dashed;
}
</style>