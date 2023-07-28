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

    static async changeCategoryParent(args) {
        const res = await new AjaxRequest(TYPO3.settings.ajaxUrls.category_treebuilder_move).post({
            uid: args.categoryUid,
            parent: args.newParent
        });
        return res.resolve();
    }

}