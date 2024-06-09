import {ref, onMounted, watch} from "vue";

const currentCategory = ref()

export function useContextMenu() {
    return {
        currentCategory
    }
}