import {ref, onMounted, watch} from "vue";

const localStorageKey = `petitglacon/category_treebuilder-opened_categories`
const refs = {}
const defaultOpen = true

if (!localStorage.hasOwnProperty(localStorageKey)) {
    localStorage.setItem(localStorageKey, JSON.stringify({}))
}

export function useCollapsibleRefs() {
    let currentRef = null
    let currentUid = null

    function createRef(elementUid) {
        if (refs[elementUid]) {
            return refs[elementUid]
        }
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
        return ls[elementUid] ?? defaultOpen
    }

    onMounted(() => {
        if (currentRef) {
            watch(currentRef, (val) => {
                console.log(val)
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
        refs,
        createRef,
        addRef,
        collapse,
        expend
    }
}