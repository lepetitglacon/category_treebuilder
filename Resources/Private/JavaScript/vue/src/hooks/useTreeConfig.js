import { reactive } from 'vue'
import { ref, onMounted } from 'vue';

let indent = ref(50)

export const store = reactive({
    indent: indent,
    setIndent: (newIndent) => {
        indent.value = newIndent
    }
})