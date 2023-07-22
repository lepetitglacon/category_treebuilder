import Notification from "@typo3/backend/notification.js";

export default class Category {

    static IMAGE =
        `<span class="t3js-icon icon icon-size-small icon-state-default icon-mimetypes-x-sys_category" data-identifier="mimetypes-x-sys_category">
            <span class="icon-markup">
                <svg class="icon-color"><use xlink:href="/_assets/1ee1d3e909b58d32e30dcea666dd3224/Icons/T3Icons/sprites/mimetypes.svg#mimetypes-x-sys_category"></use></svg>
            </span>
        </span>`

    constructor(props) {
        this.uid = props.uid ?? 0
        this.pid = props.pid ?? 0
        this.parent = props.parent ?? 0
        this.title = props.title ?? ''

        this.depth = props.depth ?? 0
        this.children = props.children ?? []
        this.tree = props.tree ?? undefined

        this.li = document.createElement('li')
        this.li.innerText = this.title
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

        Notification.success('Created', `category ${this.title} created`, 3);

        // build category HTML
        this.li.innerHTML = `
        ${Category.IMAGE}
        <span class="cat-title">${this.title}</span>
        `

        this.li.appendChild(this.childrenUl)

        // add category to tree root if no parent
        if (this.parent === 0) {
            this.tree.treeList.appendChild(this.li)
        }

        this.li.addEventListener('contextmenu', (e) => {
            if (e.ctrlKey) // debug purpose
                return;

            e.preventDefault();
            console.log(e.target)

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

}