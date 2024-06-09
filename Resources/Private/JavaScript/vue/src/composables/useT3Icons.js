import Icons from '@typo3/backend/icons.js';
import {useToast} from "vue-toast-notification";

const $toast = useToast();

const icons = {
    'actions-chevron-right': null,
    'actions-document-edit': null,
    'actions-document-view': null,
    'miscellaneous-placeholder': null,
    'apps-pagetree-category-expand-all': null,
    'apps-pagetree-category-collapse-all': null,
    'spinner-circle': null
}

async function loadIcons() {
    for (const iconKey of Object.keys(icons)) {
        icons[iconKey] = await Icons.getIcon(iconKey, Icons.sizes.small, null, 'disabled')
    }
    $toast.success('Icons loaded')
}
loadIcons()

export default function useT3Icons() {

    function getIcon(name) {
        return icons[name]
    }

    return {
        getIcon
    }
}