<template>
    <div class="container-fluid" style="min-width: 90vw">
      <div class="row d-flex">
        <DialogRoot>

        <div id="tree-div" class="col-8">
          <!-- TODO -->
          <!--      <div>-->
          <!--        <button @click="collapse">Collapse</button>-->
          <!--        <button @click="expend">Expend</button>-->
          <!--      </div>-->

          <CategoryTree v-if="tree.length > 0" :children="tree.filter(filterBySearch)" />
          <p v-else>Loading Tree</p>
        </div>

        <div id="right-div" class="col">

          <div>
            <div class="row d-flex">
              <div id="ext-conf" class="col">
                <div id="ext-conf_header" class="row">
                  <div class="row">
                    <h1>category_treebuilder</h1>
                  </div>
                  <div class="row">
                    <div class="row">
                      <div id="ext-conf_version" class="col ">1.0.0 <span class="badge badge-dark">beta</span></div>
                      <div id="ext-conf_socials" class="col">
                        <a class="mx-2" href="https://github.com/lepetitglacon/category_treebuilder" title="See on github"  target="_blank">
                          <img alt="See on github" class="picto" :src="'/_assets/5175194f086937d302a346a098d2cf15/dist' + githubLogo">
                        </a>
                        <a class="mx-2" href="https://extensions.typo3.org/extension/category_treebuilder" title="See on TER" target="_blank">
                          <img alt="See on TER" class="picto" :src="'/_assets/5175194f086937d302a346a098d2cf15/dist' + typo3Logo">
                        </a>
                        <a class="mx-2" href="https://petitglacon.com" title="petitglacon.com" target="_blank">
                          <img alt="petitglacon.com" class="picto" :src="'/_assets/5175194f086937d302a346a098d2cf15/dist' + petitglaconLogo">
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <hr>

                <div id="ext-conf_configuration" class="row">
                  <h2>Configuration</h2>
                  <form>
                    <div class="form-group">
                      <label for="ext-conf_form_rootPid">Root Parent ID for categories (pid)</label>
                      <input type="number" class="form-control" id="ext-conf_form_rootPid">
                    </div>

                    <div class="form-check">
                      <label for="ext-conf_form_autogenerateFolders">Autogenerate Folders for children categories</label>
                      <input class="form-check-input position-static" type="checkbox" id="ext-conf_form_autogenerateFolders" value="autogenerate" >
                    </div>
                  </form>
                </div>

                <hr>

                <div id="ext-conf_actions" class="row">
                  <h2>Actions</h2><p>(Comming soon)</p>
                  <button disabled class="btn btn-light mb-2">Full text Importer</button>
                </div>

                <div id="ext-conf_actions" class="row">
                  <h2>Danger zone</h2>
                  <button class="btn btn-warning mb-2" data-action="category_treebuilder_generatefakedata" @click="handleActionButton">Generate fake categories</button>
                  <button class="btn btn-danger mb-2" data-action="category_treebuilder_deleteall" @click="handleActionButton">Delete all categories</button>
                </div>

              </div>
            </div>
          </div>

          <div id="actions">

          </div>
        </div>

        <UpdatePopin/>
        </DialogRoot>
      </div>
    </div>
</template>

<script setup>
import './assets/main.css';
import 'vue-toast-notification/dist/theme-sugar.css';
import { ref, onMounted } from 'vue';
import CategoryTree from "@/components/CategoryTree.vue";
import useT3Api from "@/composables/useT3Api.js";
import {useCategoryTree} from "@/composables/useCategoryTree.js";
import {useCollapsibleRefs} from "@/composables/useCollapsibleRefs.js";
import {DialogRoot} from "radix-vue";
import UpdatePopin from "@/components/UpdatePopin.vue";
import Modal from '@typo3/backend/modal.js';
import Severity from '@typo3/backend/severity.js';
import DeferredAction from "@typo3/backend/action-button/deferred-action.js";

import githubLogo from './assets/github.png'
import typo3Logo from './assets/typo3.png'
import petitglaconLogo from './assets/petitglacon.png'

console.log(githubLogo)


const {tree, loadTree} = useCategoryTree()
const {collapse, expend} = useCollapsibleRefs()

const {makeT3Request, makeT3PostRequest} = useT3Api()

onMounted(async () => {
  loadTree()
})

function filterBySearch(item) {
  return item
}

const handleActionButton = async (e) => {
  Modal.confirm(
      e.target.dataset['action'] === 'category_treebuilder_generatefakedata' ? 'Generate fake categories' : 'Delete all categories ?',
      e.target.dataset['action'] === 'category_treebuilder_generatefakedata' ? 'Do you really want to generate fake categories ?' : `Do you really want to delete ALL categories ?`,
      Severity.warning,
      [
        {
          text: 'Cancel',
          trigger: function () {
            Modal.dismiss();
          }
        },
        {
          text: e.target.dataset['action'] === 'category_treebuilder_generatefakedata' ? 'Generate' : `Delete all`,
          btnClass: 'btn-danger',
          action: new DeferredAction(async () => {
            return makeT3Request(e.target.dataset['action']);
          })
        }
      ]
  );
}

</script>

<style>

.picto {
  max-width: 32px;
}
</style>
