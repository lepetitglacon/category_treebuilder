import Notification from "@typo3/backend/notification.js";
import AjaxRequest from '@typo3/core/ajax/ajax-request.js'

export default class CategoryFormModal extends EventTarget{

    constructor(props) {
        super();

        this.tree = props.tree

        this.modal = document.getElementById('categoryFormModal')
        this.modalTitle = document.getElementById('categoryFormModal-modalTitle')

        this.formTitle = document.getElementById('categoryFormModal-formTitle')
        this.formParent = document.getElementById('categoryFormModal-formParent')
        this.formPid = document.getElementById('categoryFormModal-formPid')
        this.formUid = document.getElementById('categoryFormModal-formUid')

        this.form = document.getElementById('categoryFormModal-form')

        this.form.addEventListener('submit', async (e) => {
            e.preventDefault()

            const callerMethod = e.target.dataset.method
            console.log(e)
            console.log(callerMethod)

            switch (callerMethod) {
                case 'insert': {
                    let category = {
                        title: this.formTitle.value,
                        parent: this.formParent.value,
                        pid: this.formPid.value,
                    }

                    const categoryJson = {
                        category: category
                    };
                    let res = await new AjaxRequest(TYPO3.settings.ajaxUrls.category_treebuilder_insert).post(categoryJson);
                    let {success, uid, message} = await res.resolve();

                    if (success) {
                        Notification.success('Created', message, 5);
                        this.hide()
                        this.emptyForm_()
                        category.uid = uid
                        this.tree.addCategoryToCategory(category)
                    } else {
                        Notification.error('Not created', message, 5);
                    }

                    break
                }
                case 'update': {
                    let category = {
                        title: this.formTitle.value,
                        parent: this.formParent.value,
                        pid: this.formPid.value,
                        uid: this.formUid.value
                    }

                    let res = await new AjaxRequest(TYPO3.settings.ajaxUrls.category_treebuilder_update).post({
                        category: category
                    });
                    let {success, uid, message} = await res.resolve();

                    if (success) {
                        Notification.success('Updated', message, 5);
                        this.hide()
                        this.emptyForm_()
                        category.uid = uid
                        this.tree.updateCategory(category)


                    } else {
                        Notification.error('Not created', message, 5);
                    }

                    break
                }


                default:
                    console.warning('Unkown callerMethod')
                    break
            }



        })
        this.formSubmit = document.getElementById('categoryFormModal-formSubmit')
        this.formSubmit.addEventListener('click', e => {
            this.form.requestSubmit()
        })

        for (const closeBtn of document.getElementsByClassName('categoryFormModalCloseButton')) {
            closeBtn.addEventListener('click', (e) => {
                this.hide()
            })
        }


        this.addEventListener('show', e => {

        })
        this.addEventListener('hide', e => {

        })
        this.addEventListener('update', e => {

        })
        this.addEventListener('insert', e => {

        })
    }

    /**
     *
     * @param title
     * @param props
     */
    show(title, props = {}) {
        this.modalTitle.innerText = title
        this.fillInfo_(props)
        this.modal.style.display = 'block'
    }

    hide() {
        this.modal.style.display = 'none'
    }

    fillInfo_(props) {
        this.formTitle.value = props.title ?? ''
        this.formParent.value = props.parent ?? 0
        this.formPid.value = props.pid ?? 0
        this.formUid.value = props.uid ?? 0
        this.form.dataset.method = props.method ?? 'update'
    }

    emptyForm_() {
        this.formTitle.value = ''
        this.formParent.value = 0
        this.formPid.value = 0
        this.formUid.value = 0
    }

}