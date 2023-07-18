import $ from 'jquery'
import AjaxRequest from 'typo3-ajax-request'
import TextImporter from "./TextImporter.js";

console.log(AjaxRequest)

export default class CategoryTreebuilder {

    constructor() {
        // this.textImporter = new TextImporter()

        this.initCategoryTree().then(() => {
            this.initContextMenu()
        })

        console.log('ready to build categories :)')
    }

    async initCategoryTree() {


        new AjaxRequest(TYPO3.settings.ajaxUrls.category_treebuilder_index)
            .get()
            .then(async function (response) {
                console.log(response);
                const resolved = await response.resolve();
                console.log(resolved);
            });

        // const categories = await fetch('typo3/category_treebuilder/Ajax/index')
        // console.log(await categories)
        // console.log(await categories.text())
    }

    initContextMenu() {
        this.$contextMenu = $('#customMenu')

        // Prevent the default context menu from appearing
        document.addEventListener("click", (event) => {
            event.preventDefault();

            switch (event.button) {
                case 0:
                    break;

                case 1:
                    this.$contextMenu.style.display = "none";
                    break;
                case 2:
                    // Show the custom menu at the mouse position
                    this.$contextMenu.style.top = `${event.pageY}px`;
                    this.$contextMenu.style.left = `${event.pageX}px`;
                    this.$contextMenu.style.display = "block";
                    break;
            }
        });

        // Define the actions for each menu option
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

let c = new CategoryTreebuilder()