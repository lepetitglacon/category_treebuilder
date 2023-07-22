import AjaxRequest from '@typo3/core/ajax/ajax-request.js'
import Notification from "@typo3/backend/notification.js";
import Category from '@petitglacon/category-treebuilder/Category.js'
import Sortable from 'sortablejs'

export default class CategoryTree {

    constructor() {

        // utils
        this.loaderDiv = document.getElementById('loader')
        this.categoryImg = document.getElementById('category-img')
        this.contextMenu = document.getElementById('customMenu')

        // trees
        this.treeContainer = document.getElementById('cat-tree-div')
        this.treeList = document.getElementById('cat-tree')

        this.categories = new Map()
        this.categories.set(0, new Category({
            tree: this,
            title: 'Root',
            parent: null,
            uid: 0,
            pid: 0,
            depth: 0
        }))

    }

    async init() {
        await this.initCategoryTree()
        this.initContextMenu()
        console.log('ready to build categories :)')
    }

    async initCategoryTree() {

        // get tree by ajax
        // const res = await new AjaxRequest(TYPO3.settings.ajaxUrls.category_treebuilder_index).get()
        // const {success, tree, message} = await res.resolve();
        // if (!success) throw new Error('CTB - could not get categories from DB')
        // console.log(res)

        // get tree by input
        const tree = JSON.parse(document.getElementById('jsonTreeInput').value)
        console.log('CTB - received tree', tree)

        console.log('CTB - building tree', tree)
        // build tree manually
        for (const category of tree) {
            this.categories.set(category.uid, new Category({
                tree: this,
                ...category
            }))
        }


        const nestedSortables = document.getElementsByClassName('nested-sortable')
        for (let i = 0; i < nestedSortables.length; i++) {
            new Sortable(nestedSortables[i], {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onEnd: (e) => {
                    console.log(e);

                    Notification.success('Category moved', e.item.dataset.title, 5)
                }
            });
        }
        console.log('CTB - tree built', tree)
    }

    initContextMenu() {


        // Prevent the default context menu from appearing
        document.addEventListener("click", (e) => {
            if (this.contextMenu.style.display === "block") {
                this.contextMenu.style.display = "none"
            }
        });

        // Define the actions for each menu option
        document.getElementById("menuOption0").addEventListener("click", () => {
            console.log("Menu Option 0 selected");
        });

        document.getElementById("menuOption1").addEventListener("click", () => {
            console.log("Menu Option 1 selected");
        });

        document.getElementById("menuOption2").addEventListener("click", () => {
            console.log("Menu Option 2 selected");
        });

        document.getElementById("menuOption3").addEventListener("click", () => {
            console.log("Menu Option 3 selected");
        });
    }

}
new CategoryTree().init()


