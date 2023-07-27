import AjaxRequest from '@typo3/core/ajax/ajax-request.js'
import Notification from "@typo3/backend/notification.js";
import Category from '@petitglacon/category-treebuilder/Category.js'
import CategoryFormModal from "@petitglacon/category-treebuilder/CategoryFormModal.js";
import Sortable from 'sortablejs'

// console.log(Sortable)

export default class CategoryTree extends EventTarget {

    constructor() {
        super();

        this.config = {
            reorderingCategories: 0, // reoarders categories when changing in the same parent
            automaticDirectories: 1, // create automatically sub folders for children categories
            categoryClassName: 'TYPO3\\CMS\\Extbase\\Domain\\Model\\Category', // used to build the insert/update form
            randomColorForFolders: true, // true for random folders color every refresh, false for fixed colors
        }

        // utils
        this.loaderDiv = document.getElementById('loader')
        this.categoryImg = document.getElementById('category-img')
        this.contextMenu = document.getElementById('customMenu')

        // trees
        this.treeContainer = document.getElementById('cat-tree-div')
        this.treeList = document.getElementById('cat-tree')

        // Maps
        this.categories = new Map() // will hold all categories by id
        this.moves = new Map() // will hold all movement of categories to rollback on error
        this.folders = new Map() // will hold all movement of categories to rollback on error

        // category form
        this.categoryFormModal = new CategoryFormModal({
            tree: this
        })

        this.categories.set(0, new Category({
            tree: this,
            title: 'Root',
            parent: null,
            uid: 0,
            pid: 0,
            depth: 0
        }))

        this.lastContextMenuCategory = {}

        this.addEventListener('category/move/error', e => {

        })
        this.addEventListener('category/move/success', e => {

        })

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

        console.log('CTB - building tree')
        // build tree manually
        for (const category of tree) {
            this.categories.set(category.uid, new Category({
                tree: this,
                ...category
            }))
        }

        this.buildSortableTree()

        const carets = document.getElementsByClassName('icon-actions-caret-right')
        for (let i = 0; i < carets.length; i++) {
            carets[i].addEventListener('click', e => {

                let li = e.target.parentNode.parentNode.parentNode
                if (li.dataset.uid === undefined) {
                    li = e.target.parentNode.parentNode.parentNode.parentNode
                }
                const cat = this.categories.get(parseInt(li.dataset.uid))
                cat.childrenUl.classList.toggle('d-none')

                let caret = cat.li.querySelector('.icon-actions-caret-right');
                caret.classList.toggle('rotate-right')
            })
        }


        Notification.success('Tree built', `You can start managing categories`, 5);
        console.log('CTB - tree built')
        // console.log(this.categories)
    }

    buildSortableTree() {
        const nestedSortables = document.getElementsByClassName('nested-sortable')
        for (let i = 0; i < nestedSortables.length; i++) {
            new Sortable(nestedSortables[i], {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onEnd: async (e) => {

                    if (e.item.dataset.parent !== e.to.parentNode.dataset.uid) {
                        console.log('need to change category parent')

                        const categoryJson = {
                            uid: e.item.dataset.uid,
                            parent: e.to.parentNode.dataset.uid
                        };
                        // TODO move categories parent
                        const res = await new AjaxRequest(TYPO3.settings.ajaxUrls.category_treebuilder_move).post(categoryJson);
                        const {success, message} = await res.resolve();

                        if (!success) {
                            Notification.error('Category not moved', message, 5);
                        } else {
                            Notification.success('Category moved', e.item.dataset.title, 5)
                        }
                    } else {
                        if (this.config.reorderingCategories) {
                            // TODO change ordering (add a CONF option if usefull)
                            Notification.success('Same parent', `ordering changed`, 5);
                        } else {
                            Notification.info('Same parent', `activate 'reorderingCategories' config to reorder categories in same parent`, 5);
                        }

                    }

                },
                onAdd: async (e) => {
                    console.log('onAdd',e)
                }
            });
        }
    }

    initContextMenu() {

        // Prevent the default context menu from appearing
        document.addEventListener("click", (e) => {
            if (this.contextMenu.style.display === "block") {
                this.contextMenu.style.display = "none"
            }
        });

        // Define the actions for each menu option
        document.getElementById("menuOption0").addEventListener("click", (e) => {
            console.log("Menu Option 0 selected");
            const cat = this.lastContextMenuCategory.target.parentNode ?? {}
            console.log(cat)
            this.categoryFormModal.show('Modify category', {
                method: 'update',
                parent: cat.dataset.parent,
                pid: cat.dataset.pid,
                uid: cat.dataset.uid,
                title: cat.dataset.title
            })
        });

        document.getElementById("menuOption1").addEventListener("click", () => {
            console.log("Menu Option 1 selected");
        });

        document.getElementById("menuOption2").addEventListener("click", (e) => {
            const parent = this.lastContextMenuCategory.target.parentElement ?? {}

            this.categoryFormModal.show('Create category', {
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

    addCategoryToCategory(category) {
        console.log('parent',category.parent)
        const cat = this.categories.get(parseInt(category.parent))
        cat.addChildren(category)
    }

    updateCategory(category) {
        if (!category.uid) return console.error('Could not update category, category does not exist in tree');
        const cat = this.categories.get(parseInt(category.uid))
        cat.updateInfo(category)
    }

    getRandomColor() {
        // old way
        // return Math.floor(Math.random()*16777215).toString(16);

        const letters = /*'01234567*/ '89ABCDEF';
        let color = '';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 8)];
        }
        return color;
    }

}
new CategoryTree().init()


