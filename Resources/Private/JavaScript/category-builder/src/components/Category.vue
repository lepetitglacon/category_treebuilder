<script setup>
import Icons from '@typo3/backend/icons.js';
import { ref, onMounted } from 'vue';

const props = defineProps({
  node: "",
  stat: ""
  // uid,
  // pid,
  // title,
  // parent,
  // parentUid,
  // storage,
  // storageUid
})

let category_icon = ref('')
Icons.getIcon('mimetypes-x-sys_category', Icons.sizes.small, null, 'disabled').then((icon) => {
  category_icon.value = icon
});

</script>

<template>
  <div class="greetings">
    <OpenIcon
        v-if="stat.children.length"
        :open="stat.open"
        class="mtl-mr"
        @click.native="stat.open = !stat.open"
    />
    <input
        class="mtl-checkbox mtl-mr"
        type="checkbox"
        v-model="stat.checked"
    />
    <span v-html="category_icon"></span>
    <span class="mtl-ml">{{ node.title }}</span>
  </div>
</template>

<style scoped>
h1 {
  font-weight: 500;
  font-size: 2.6rem;
  position: relative;
  top: -10px;
}

h3 {
  font-size: 1.2rem;
}

.greetings h1,
.greetings h3 {
  text-align: center;
}

@media (min-width: 1024px) {
  .greetings h1,
  .greetings h3 {
    text-align: left;
  }
}
</style>
