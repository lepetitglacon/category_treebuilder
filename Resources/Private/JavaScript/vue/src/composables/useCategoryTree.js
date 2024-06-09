import { ref } from 'vue';
import useT3Api from "@/composables/useT3Api.js";
const {makeT3Request, makeT3PostRequest} = useT3Api()

let tree = ref([])

export function useCategoryTree() {

    async function loadTree() {
        const data = await makeT3Request('category_treebuilder_index')
        tree.value = data.data
    }

    return {
        tree,
        loadTree
    }
}