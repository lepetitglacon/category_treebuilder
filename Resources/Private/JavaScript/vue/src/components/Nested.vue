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

function getStyles() {
  return {
    background: getRandomHexColor()
  }
}

function getRandomHexColor() {
  return '#' + Math.floor(Math.random()*16777215).toString(16)
}

function handleChange(e) {
  console.log(e)
}
</script>

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
    <template #item="{ element }">
			<li :style="getStyles">
        <p><span v-html="category_icon"></span>{{ element.title }}</p>
				<Nested v-if="element.children" :children="element.children" />
<!--				<Nested v-else :children="element.children" :class="'empty-draggable-item'" />-->
			</li>
		</template>
	</draggable>
</template>

<style scoped>
.draggable-item {
	//outline: 1px dashed #00bd7e;
}
.empty-draggable-item {
  background: #eee;
  min-height: 10px;
}
</style>