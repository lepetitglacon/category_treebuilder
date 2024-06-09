import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import {useToast} from "vue-toast-notification";
import {useCategoryTree} from "@/composables/useCategoryTree.js";

const {tree, loadTree} = useCategoryTree()
const $toast = useToast();

export default function useT3Api() {

    async function makeT3Request(endpoint) {
        return makeRequest(endpoint, 'get')
    }

    async function makeT3PostRequest(endpoint, inputData) {
        return makeRequest(endpoint, 'post', inputData)
    }

    async function makeRequest(endpoint, method, inputData = {}) {
        let request = new AjaxRequest(TYPO3.settings.ajaxUrls[endpoint]);
        const res = await request[method](inputData)
        const data = await res.resolve()
        if (data.status && data.message) {
            $toast[data.status](data.message)
        }
        if (data.reloadTree) {
            await loadTree()
        }
        return data
    }

    return {
        makeT3Request,
        makeT3PostRequest
    }
}