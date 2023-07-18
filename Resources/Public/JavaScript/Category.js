export default class Category {

    constructor(props) {

        console.log(props)

        this.uid = props.uid ?? 0
        this.pid = props.pid ?? 0
        this.parent = props.parent ?? 0
        this.title = props.title ?? ''

        this.depth = props.depth ?? 0
        this.children = props.children ?? []
        this.tree = props.tree ?? undefined


        this.span = document.createElement('span')
        this.span.innerText = this.title
        this.span.dataset.uid = this.uid
        this.span.dataset.pid = this.pid
        this.span.dataset.parent = this.parent


        this.div = this.tree.categoryImg.cloneNode(true)
        this.div.classList.add('category')
        this.div.appendChild(this.span)
        this.tree.categoriesTree.appendChild(this.div)

        if (this.children.length > 0) {
            for (const category of this.children) {
                this.tree.categories.set(category.uid, new Category({
                    tree: this.tree,
                    ...category
                }))
            }
        }

        this.span.addEventListener('contextmenu', (e) => {
            e.preventDefault();

            switch (e.button) {
                case 0:
                    break;

                case 1:

                    break;
                case 2:
                    // Show the custom menu at the mouse position
                    this.tree.contextMenu.style.top = `${e.pageY}px`;
                    this.tree.contextMenu.style.left = `${e.pageX}px`;
                    this.tree.contextMenu.style.display = "block";
                    break;
            }
        })

        console.log('category created', this)
    }

}