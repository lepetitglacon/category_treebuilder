import {ref, onMounted, watch} from "vue";

const localStorageKey = `petitglacon/category_treebuilder-open_categories`
const refs = {}

if (!localStorage.hasOwnProperty(localStorageKey)) {
    localStorage.setItem(localStorageKey, JSON.stringify({}))
}

export function useCollapsibleRefs() {
    let currentRef = null
    let currentUid = null

    function createRef(elementUid) {
        const r = ref(getValueFromLocalStorage(elementUid))
        refs[elementUid] = r
        currentRef = r
        currentUid = elementUid
        return r
    }

    function addRef(r, elementUid) {
        refs[elementUid] = r
    }

    function getRefs() {
        return refs
    }

    function getValueFromLocalStorage(elementUid) {
        const ls = JSON.parse(localStorage.getItem(localStorageKey))
        return ls[elementUid] ?? false
    }

    onMounted(() => {
        if (currentRef) {
            watch(currentRef, (val) => {
                const ls = JSON.parse(localStorage.getItem(localStorageKey))
                ls[currentUid] = val
                localStorage.setItem(localStorageKey, JSON.stringify(ls))
            })
        }
    })

    function collapse(expend = false) {
        console.log(Object.entries(refs))
        for (const [key, currentRef] of Object.entries(refs)) {
            currentRef.value = expend
        }
    }

    function expend() {
        collapse(true)
    }

    return {
        createRef,
        addRef,
        getRefs,
        collapse,
        expend
    }
}