<template>
  <DialogPortal class="dialog-portal">
    <DialogOverlay class="dialog-overlay" />
    <DialogContent
        class="dialog-content"
    >
      <DialogTitle class="text-mauve12 m-0 text-[17px] font-semibold">
        Modify category
      </DialogTitle>

      <form ref="form">
        <div class="my-5">
          <label class="form-label" for="update-modal-title">Title</label>
          <textarea
              id="update-modal-title"
              name="category[title]"
              class="form-control"
              :value="currentCategory.title"
          >
            </textarea>
        </div>

        <fieldset>
          <div class="col-auto">
              <span id="passwordHelpInline" class="form-text">
                You should know what you're doing by modifying these fields
              </span>
          </div>

          <div>
            <label class="form-label" for="update-modal-uid">Uid</label>
            <input
                id="update-modal-uid"
                name="category[uid]"
                class="form-control"
                :value="currentCategory.uid"
                disabled readonly
            >
          </div>
          <div>
            <label class="form-label" for="update-modal-pid">Pid</label>
            <input
                id="update-modal-pid"
                name="category[pid]"
                class="form-control"
                :value="currentCategory.pid"
            >
          </div>
          <div>
            <label class="form-label" for="update-modal-parent">Parent</label>
            <input
                id="update-modal-parent"
                name="category[parent]"
                class="form-control"
                :value="currentCategory.parent"
            >
          </div>
          <input type="hidden" name="category[__identity]" :value="currentCategory.uid">
        </fieldset>

        <div class="mt-[25px] flex justify-end">
          <DialogClose as-child>
            <button
                type="submit"
                class="btn btn-primary my-3"
                @click.prevent="handleCategoryUpdate"
            >
              Update
            </button>
          </DialogClose>
        </div>
      </form>
      <DialogClose
          class="dialog-close-button"
          aria-label="Close"
      >
        X
      </DialogClose>
    </DialogContent>
  </DialogPortal>
</template>

<script setup>
import {DialogClose, DialogContent, DialogOverlay, DialogPortal, DialogTitle} from "radix-vue";
import {useContextMenu} from "@/composables/useContextMenu.js";
import {useCategoryTree} from "@/composables/useCategoryTree.js";
import {useCollapsibleRefs} from "@/composables/useCollapsibleRefs.js";
import useT3Api from "@/composables/useT3Api.js";
import {ref} from "vue";

const {currentCategory} = useContextMenu()
const {tree, loadTree} = useCategoryTree()
const {collapse, expend} = useCollapsibleRefs()
const {makeT3Request, makeT3PostRequest} = useT3Api()

const form = ref()

async function handleCategoryUpdate(e) {

  const inputData = {}
  for (const [key, val] of new FormData(form.value).entries()) {
    inputData[key] = val
  }
  const data = await makeT3PostRequest('category_treebuilder_update', inputData)
  if (data.reloadTree) {
    await loadTree()
  }
}
</script>

<style scoped>
/* Style the dialog overlay */
.dialog-overlay {
  background-color: rgba(0, 0, 0, 0.5); /* Set overlay opacity */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 99999999; /* Ensure overlay is above other elements */
}

/* Style the dialog container */
.dialog-content {
  background-color: #fff; /* Set dialog background color */
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%); /* Center the dialog */
  padding: 20px;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2); /* Add a shadow effect */
  width: fit-content; /* Adjust width as needed */
  max-width: 800px; /* Set a maximum width */
  z-index: 999999999;
}

/* Style the dialog title */
[data-radix-dialog-title] {
  font-size: 1.2em;
  font-weight: bold;
  margin-bottom: 10px;
}

/* Style the dialog content */
.dialog-content {
  /* Add styles for the dialog content here */
}

/* Style the dialog close button */
.dialog-close-button {
  cursor: pointer;
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 5px;
  border: none;
  background-color: transparent;
  color: #999;
  font-size: 16px;
}
</style>