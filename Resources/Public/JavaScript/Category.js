import Notification from "@typo3/backend/notification.js";

export default class Category {



    constructor(props) {
        this.uid = props.uid ?? 0
        this.pid = props.pid ?? 0
        this.parent = props.parent ?? 0
        this.title = props.title ?? ''

        this.depth = props.depth ?? 0
        this.children = props.children ?? []
        this.tree = props.tree ?? undefined

        this.li = document.createElement('li')
        this.li.dataset.title = this.title
        this.li.dataset.uid = this.uid
        this.li.dataset.pid = this.pid
        this.li.dataset.parent = this.parent

        this.li.classList.add('category')

        this.childrenUl = document.createElement('ul')
        this.childrenUl.classList.add('nested-sortable')

        if (this.children.length > 0) {
            for (const category of this.children) {
                const cat = new Category({
                    tree: this.tree,
                    ...category
                })
                this.tree.categories.set(category.uid, cat)
                this.childrenUl.appendChild(cat.li)
            }
        }

        this.IMAGE =
        `<span class="t3js-icon icon icon-size-small icon-state-default icon-mimetypes-x-sys_category" data-identifier="mimetypes-x-sys_category" title="${this.uid}">
            <span class="icon-markup">
                <svg class="icon-color"><use xlink:href="/_assets/1ee1d3e909b58d32e30dcea666dd3224/Icons/T3Icons/sprites/mimetypes.svg#mimetypes-x-sys_category"></use></svg>
            </span>
        </span>`

        this.caretClass = this.children.length <= 0 ? 'd-none' : ''
        this.CARET =
        `<span class="t3js-icon icon icon-size-small icon-state-default icon-actions-caret-right rotate-right ${this.caretClass}" data-identifier="actions-caret-right" data-uid="${this.uid}">
            <span class="icon-markup">
                <svg class="icon-color"><use xlink:href="/_assets/1ee1d3e909b58d32e30dcea666dd3224/Icons/T3Icons/sprites/actions.svg#actions-caret-right"></use></svg>
            </span>
        </span>`

        // build category HTML
        this.li.innerHTML = `
        ${this.CARET}
        ${this.IMAGE}
        <span class="cat-title">${this.title}</span>
        `

        // add children to HTML
        this.li.appendChild(this.childrenUl)

        // add category to tree root if no parent
        if (this.parent === 0) {
            this.tree.treeList.appendChild(this.li)
        }

        this.li.addEventListener('contextmenu', (e) => {
            if (e.ctrlKey) // debug purpose
                return;

            e.preventDefault();
            e.stopPropagation();

            switch (e.button) {
                case 0:
                    break;

                case 1:

                    break;
                case 2:
                    // Show the custom menu at the mouse position
                    this.tree.lastContextMenuCategory = e

                    this.tree.contextMenu.style.top = `${e.pageY}px`;
                    this.tree.contextMenu.style.left = `${e.pageX}px`;
                    this.tree.contextMenu.style.display = "block";
                    break;
            }
        })
    }

    addChildren(category) {
        const cat = new Category({
            tree: this.tree,
            ...category
        })
        this.tree.categories.set(category.uid, cat)
        this.childrenUl.appendChild(cat.li)

        let caret = this.li.querySelector('.icon-actions-caret-right');
        caret.classList.remove('d-none')

        // refresh Sortable list
        // this.childrenUl.refresh()
    }

    updateInfo(category) {
        this.li.dataset.title = category.title
        this.li.dataset.parent = category.parent
        this.li.dataset.pid = category.pid
    }

}