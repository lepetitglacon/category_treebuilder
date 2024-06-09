import {ref, onMounted, watch} from "vue";

const contextMenuInfos = ref({})

export function useContextMenu() {
    return {
        contextMenuInfos: contextMenuInfos
    }
}