import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import {useToast} from "vue-toast-notification";

const $toast = useToast();

export default function useT3Api() {

    async function makeT3Request(endpoint) {
        let request = new AjaxRequest(TYPO3.settings.ajaxUrls[endpoint]);
        const res = await request.get()
        const data = await res.resolve()
        if (data.status && data.message) {
            $toast[data.status](data.message)
        }
        return data
    }

    async function makeT3PostRequest(endpoint, inputData) {
        let request = new AjaxRequest(TYPO3.settings.ajaxUrls[endpoint]);
        const res = await request.post(inputData)
        const data = await res.resolve()
        if (data.status && data.message) {
            $toast[data.status](data.message)
        }
        return data
    }

    return {
        makeT3Request,
        makeT3PostRequest
    }
}