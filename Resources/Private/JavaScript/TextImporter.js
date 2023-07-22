export default class TextImporter {

    constructor() {
        const textarea = document.getElementById('textarea')
        const treeViewerScroll = document.getElementById('treeViewerScroll')

// Auto scroll
        textarea.addEventListener('scroll', (e) => {
            treeViewerScroll.scrollTop = e.target.scrollTop
        })
        treeViewerScroll.addEventListener('scroll', (e) => {
            textarea.scrollTop = e.target.scrollTop
        })

// enable tab in textarea
        textarea.addEventListener('keydown', (e) => {
            if (e.code === "Tab") {
                e.preventDefault()

                textarea.setRangeText(
                    '\t',
                    textarea.selectionStart,
                    textarea.selectionStart,
                    'end'
                )
                console.log(textarea.value)
            }
        })

        let treeJson = JSON.parse(document.getElementById("hiddenTreeJson").value)

        treeJson.forEach((tree) => {
            addLine(tree)
        })

        function addLine(category) {
            // add tabs
            if (category.depth !== undefined) {
                for (let i=0;i<category.depth;i++) {
                    addTab()
                }
            }

            // text
            textarea.setRangeText(
                category.title + ' [' + category.uid + ']',
                textarea.selectionStart,
                textarea.selectionStart,
                'end'
            )

            addReturn()

            if (category.children !== undefined) {
                if (category.children.length > 0) {
                    for (let i=0;i<category.children.length;i++) {
                        console.log("add " + category.children[i].title)
                        addLine(category.children[i])
                    }
                }
            }

        }

        function addTab() {
            textarea.setRangeText(
                '\t',
                textarea.selectionStart,
                textarea.selectionStart,
                'end'
            )
        }

        function addReturn() {
            textarea.setRangeText(
                '\n',
                textarea.selectionStart,
                textarea.selectionStart,
                'end'
            )
        }

// content
        const submit = document.getElementById('text-submit')
        submit.addEventListener('click', (e) => {

            let testValues = {
                0: "parent1\n\tenfant1\n\t\tsousenfant1\n\tenfant2\n\t\tsousenfant2\n\t\tsousenfant3\nparent2\n\tenfant3\n\tenfant4\n\t\tsousenfant4",
                1: "parent1\n\tenfant1\n\t\tsousenfant1\n\t\t\tsoussousenfant1\n\tenfant2\n\t\tsousenfant2\n\t\tsousenfant3\nparent2\n\tenfant3\n\tenfant4\n\t\tsousenfant4",
                2: "Catégories éditoriales\n" +
                    "\tÀ la une\n" +
                    "\t\tActualités\n" +
                    "\t\tAgenda\n" +
                    "\tCatégories pour les tests\n" +
                    "\t\tTest\n" +
                    "\tConsultation par profil\n" +
                    "\t\tJe suis un étudiant\n" +
                    "\t\tJe suis un nouvel arrivant\n" +
                    "\t\tJe suis un senior\n" +
                    "\t\tJe suis une famille\n" +
                    "\tThématiques éditoriales\n" +
                    "\t\tAménagements\n" +
                    "\t\tAndromède\n" +
                    "\t\tCentre\n" +
                    "\t\tCoopération"
            }
            // debug values
            // textarea.value = testValues[2]
            let val = textarea.value
                .replaceAll("    ","\t")
                .replaceAll("        ", "\t")
                .trim()

            textarea.value = JSON.stringify(val)
        })

        var toggler = document.getElementsByClassName("cat-caret");
        var i;

        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
                this.parentElement.querySelector(".cat-nested").classList.toggle("cat-active");
                this.classList.toggle("caret-down");
            });
            toggler[i].parentElement.querySelector(".cat-nested").classList.toggle("cat-active");
            toggler[i].classList.toggle("caret-down");
        }
    }


}
