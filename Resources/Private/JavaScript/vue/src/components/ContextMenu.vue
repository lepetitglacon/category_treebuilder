<template>
  <ContextMenuRoot>
    <ContextMenuTrigger
        as-child
    >
      <slot></slot>
    </ContextMenuTrigger>

    <ContextMenuPortal>
      <ContextMenuContent
          v-if="category.uid !== 0"
          class=""
          :side-offset="5"
      >
        <ContextMenuItem value="New Tab">
          <DialogTrigger
              as="div"
              @click="changeContextMenuValues"
          >
            Modify
          </DialogTrigger>
        </ContextMenuItem>

        <ContextMenuSeparator as="hr" class="dropdown-divider"/>

        <ContextMenuItem value="New Tab">
          <DialogTrigger
              as="div"
              @click="prepareContextMenuValuesForInsertInside"
          >
            New Category (inside)
          </DialogTrigger>
        </ContextMenuItem>
        <ContextMenuItem value="New Tab">
          <DialogTrigger
              as="div"
              @click="prepareContextMenuValuesForInsertAfter"
          >
            New Category (after)
          </DialogTrigger>
        </ContextMenuItem>

        <ContextMenuSeparator as="hr" class="dropdown-divider"/>

        <ContextMenuItem value="New Tab" @click="prepareFullTextImporter">
          <div>New Categories (inside)</div>
        </ContextMenuItem>
        <ContextMenuItem value="New Tab" @click="prepareFullTextImporterAfter">
          <div>New Categories (after)</div>
        </ContextMenuItem>

        <ContextMenuSeparator as="hr" class="dropdown-divider"/>

        <ContextMenuItem class="text-warning" value="New Tab" @click="handleGenerateFakeCategories">
          <div>Generate fake categories inside</div>
        </ContextMenuItem>

        <ContextMenuSeparator as="hr" class="dropdown-divider"/>

        <ContextMenuItem class="text-danger" value="New Tab" @click="handleDelete">
          <span>Delete</span>
        </ContextMenuItem>

      </ContextMenuContent>
    </ContextMenuPortal>
  </ContextMenuRoot>
</template>

<script setup lang="ts">

import {
  ContextMenuContent,
  ContextMenuItem,
  ContextMenuPortal,
  ContextMenuRoot,
  ContextMenuSeparator,
  ContextMenuTrigger,
  DialogTrigger
} from 'radix-vue'
import {inject, ref} from "vue";
import {useContextMenu} from '../composables/useContextMenu.js'
import useT3Api from "@/composables/useT3Api.js";
import Modal from '@typo3/backend/modal.js';
import Severity from '@typo3/backend/severity.js';
import DeferredAction from "@typo3/backend/action-button/deferred-action.js";

const {contextMenuInfos} = useContextMenu()
const {makeT3Request, makeT3PostRequest} = useT3Api()

const props = defineProps({
  category: {}
})

const importFromTextRef = inject('importFromTextRef')

const open = ref(false)


function changeContextMenuValues(e) {
  contextMenuInfos.value.title = `Modify Category "${props.category.title}"`
  contextMenuInfos.value.description = ''
  contextMenuInfos.value.action = 'category_treebuilder_update'
  contextMenuInfos.value.submitButtonTitle = 'Update'
  contextMenuInfos.value.category = props.category
}

function prepareContextMenuValuesForInsertInside(e) {
  contextMenuInfos.value.title = `Create new category inside "${props.category.title}"`
  contextMenuInfos.value.description = 'Enter the name and click on "create"'
  contextMenuInfos.value.action = 'category_treebuilder_insert'
  contextMenuInfos.value.submitButtonTitle = 'Create'
  contextMenuInfos.value.category = {
    title: '',
    uid: '',
    pid: props.category.pid,
    parent: props.category.uid
  }
}

function prepareContextMenuValuesForInsertAfter(e) {
  contextMenuInfos.value.title = `Create new category after "${props.category.title}"`
  contextMenuInfos.value.description = 'Enter the name and click on "create"'
  contextMenuInfos.value.action = 'category_treebuilder_insert'
  contextMenuInfos.value.submitButtonTitle = 'Create'
  contextMenuInfos.value.category = {
    title: '',
    uid: '',
    pid: props.category.pid,
    parent: props.category.parent
  }
}

function handleClickModify(e) {
  console.log(e, props.category)
}

async function handleDelete() {
  Modal.confirm(
      'Delete category ?',
      `Do you really want to delete the category "${props.category.title}" [${props.category.uid}] ?`,
      Severity.warning,
      [
        {
          text: 'Cancel',
          trigger: function () {
            Modal.dismiss();
          }
        },
        {
          text: 'Delete',
          btnClass: 'btn-danger',
          action: new DeferredAction(async () => {
            return await makeT3PostRequest('category_treebuilder_delete', {
              "category[__identity]": props.category.uid
            })
          })
        }
      ]
  );
}

function prepareFullTextImporter() {
  importFromTextRef.value.pid = props.category.pid
  importFromTextRef.value.parent = props.category.uid
  importFromTextRef.value.open = true
}
function prepareFullTextImporterAfter() {
  prepareFullTextImporter()
  importFromTextRef.value.parent = props.category.parent
}

async function handleGenerateFakeCategories() {
  const number = 10
  Modal.confirm(
      'Generate categories',
      `Generate ${number} category inside category "${props.category.title}" [${props.category.uid}] ?`,
      Severity.warning,
      [
        {
          text: 'Cancel',
          trigger: function () {
            Modal.dismiss();
          }
        },
        {
          active: true,
          text: 'Generate',
          btnClass: 'btn-success',
          action: new DeferredAction(async () => {
            return await makeT3PostRequest('category_treebuilder_generatefakedata', {
              'pid': props.category.pid,
              'parent': props.category.uid,
              'number': number
            })
          })
        }
      ]
  );
}
</script>

<style scoped>
/* Target the menu content */
[data-radix-popper-content-wrapper] {
  min-width: 2000px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 8px;
}

/* Style the menu items */
[data-radix-vue-collection-item] {
  cursor: pointer;
  padding: 4px 8px;
  white-space: nowrap;
  text-align: left;
}

/* Style the menu items on hover */
[data-radix-vue-collection-item]:hover {
  background-color: #333;
  color: white;
}

.context-trigger {
  position: relative !important;
  background: none;
  color: inherit;
  border: none;
  padding: 0;
  margin: 0;
  font: inherit;
  cursor: pointer;
  outline: inherit;
  width: 100%;
}

.dropdown-divider {
  border: 1px solid #eee;
}
</style>