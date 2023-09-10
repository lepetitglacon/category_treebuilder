export default class ContextMenu {

    constructor({tree}) {

        this.tree = tree

        this.div = document.getElementById('customMenu')
        this.lastContextMenuCategory = {}
        this.bind()
    }

    bind() {

        // hide context menu
        document.addEventListener("click", (e) => {
            if (this.div.style.display === "block") {
                this.div.style.display = "none"
            }
        });

        // Define the actions for each menu option
        document.getElementById("menuOption0").addEventListener("click", (e) => {
            console.log("Menu Option 0 selected");
            const cat = this.lastContextMenuCategory.target.parentNode ?? {}
            console.log(cat)

            this.tree.categoryFormModal.show('Modify category', {
                method: 'update',
                parent: cat.dataset.parent,
                pid: cat.dataset.pid,
                uid: cat.dataset.uid,
                title: cat.dataset.title
            })


            // this.tree.dispatchEvent(new Event('modal-open', ))
        });

        document.getElementById("menuOption1").addEventListener("click", () => {
            console.log("Menu Option 1 selected");
        });

        document.getElementById("menuOption2").addEventListener("click", (e) => {
            const parent = this.lastContextMenuCategory.target.parentElement ?? {}

            this.tree.categoryFormModal.show('Create category', {
                method: 'insert',
                parent: parent.dataset.uid,
                pid: parent.dataset.pid
            })

            console.log("Menu Option 2 selected");
        });

        document.getElementById("menuOption3").addEventListener("click", () => {
            console.log("Menu Option 5 selected");
        });
    }

}