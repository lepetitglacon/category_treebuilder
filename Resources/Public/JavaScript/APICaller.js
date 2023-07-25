import AjaxRequest from '@typo3/core/ajax/ajax-request.js'

export default class APICaller extends EventTarget {

    constructor(props) {
        super();

        this.tree = props.tree

        this.addEventListener('category_treebuilder_insertorupdate', e => {

        })
        this.addEventListener('API_update', e => {

        })
    }

}