<script setup>
import {
  DialogRoot,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogOverlay,
  DialogPortal,
  DialogTitle,
  DialogTrigger
} from "radix-vue";
import {
  TooltipArrow,
  TooltipContent,
  TooltipPortal,
  TooltipProvider,
  TooltipRoot,
  TooltipTrigger
} from 'radix-vue'
import {onMounted, ref} from "vue";
import useT3Api from "@/composables/useT3Api.js";

const {makeT3PostRequest} = useT3Api()

const open = ref(false)
const pid = ref(null)
const parent = ref(null)
const text = ref('')
const textareaRef = ref(null)

defineExpose({
  open,
  pid,
  parent,
  text,
  textareaRef,
})

function onKeyDown(e) {

  // add tabs
  if (e.code === "Tab") {
    e.preventDefault()
    textareaRef.value.setRangeText(
        '\t',
        textareaRef.value.selectionStart,
        textareaRef.value.selectionStart,
        'end'
    )
  }

  // set height of textarea based on his content
  const currentHeight = textareaRef.value.style.height
  if (textareaRef.value.scrollHeight > currentHeight) {
    textareaRef.value.style.height = textareaRef.value.scrollHeight + "px"
  }
}

async function handleFullTextImport() {
  if (pid.value === null) {
    return
  }
  if (text.value === '') {
    return
  }

  const res = await makeT3PostRequest('category_treebuilder_import_from_text', {
    text: JSON.stringify(text.value),
    pid: pid.value,
    parent: parent.value
  })

  if (res.status === 'success') {
    open.value = false
    pid.value = null
    parent.value = null
    text.value = null
  }
  console.log(res)
}
</script>

<template>
  <DialogRoot v-model:open="open">
    <DialogTrigger as-child>
      <button class="w-100 btn btn-secondary mb-2">Full text importer</button>
    </DialogTrigger>
    <DialogPortal>
      <DialogOverlay class="DialogOverlayy"/>
      <DialogContent
          class="DialogContent"
      >
        <DialogTitle class="DialogTitle">
          Import from text
        </DialogTitle>
        <DialogDescription class="DialogDescription">
          Use tabs to create children

          <TooltipProvider :delay-duration="0">
            <TooltipRoot>
              <TooltipTrigger as="span"
                  class="TooltipSpan"
              >
                See exemple
              </TooltipTrigger>
              <TooltipPortal>
                <TooltipContent
                    as-child
                    class="TooltipContent"
                    :side-offset="0"
                    align="center"
                >
                  <pre>
                  parent
                    children
                      sub children
                  another parent
                    another children
                  </pre>
                  <TooltipArrow
                      class="TooltipArrow"
                      :width="8"
                  />
                </TooltipContent>
              </TooltipPortal>
            </TooltipRoot>
          </TooltipProvider>

        </DialogDescription>
        <div class="d-flex w-100">
          <label>Parent category uid : <input type="number" v-model="parent"/></label>
        </div>
        <div class="d-flex w-100">
          <label>Import into pid : <input type="number" v-model="pid"/></label>
        </div>
        <textarea
            v-model="text"
            ref="textareaRef"
            @keydown="onKeyDown"
            class="w-100"
            rows="20"
        >
        </textarea>
        <div :style="{ display: 'flex', marginTop: 25, justifyContent: 'flex-end' }">
          <button class="Button green" @click="handleFullTextImport">Import</button>
        </div>
        <DialogClose
            class="IconButton"
            aria-label="Close"
        >
          X
        </DialogClose>
      </DialogContent>
    </DialogPortal>
  </DialogRoot>
</template>

<style scoped lang="css">

.DialogOverlayy {
  background-color: var(--black-a9);
  position: fixed;
  inset: 0;
  z-index: 0 !important;
  animation: overlayShow 150ms cubic-bezier(0.16, 1, 0.3, 1);
}

.DialogContent {
  background-color: white;
  border-radius: 6px;
  box-shadow: hsl(206 22% 7% / 35%) 0px 10px 38px -10px, hsl(206 22% 7% / 20%) 0px 10px 20px -15px;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 90vw;
  max-width: 450px;
  max-height: 85vh;
  padding: 25px;
  animation: contentShow 150ms cubic-bezier(0.16, 1, 0.3, 1);
}

.DialogContent:focus {
  outline: none;
}

.DialogTitle {
  margin: 0;
  font-weight: 500;
  color: var(--mauve-12);
  font-size: 17px;
}

.DialogDescription {
  margin: 10px 0 20px;
  color: var(--mauve-11);
  font-size: 15px;
  line-height: 1.5;
}

.Button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  padding: 0 15px;
  font-size: 15px;
  line-height: 1;
  font-weight: 500;
  height: 35px;
}

.Button.grass {
  background-color: white;
  color: var(--grass-11);
  box-shadow: 0 2px 10px var(--black-a7);
}

.Button.grass:hover {
  background-color: var(--mauve-3);
}

.Button.grass:focus {
  box-shadow: 0 0 0 2px black;
}

.Button.green {
  background-color: var(--green-4);
  color: var(--green-11);
}

.Button.green:hover {
  background-color: var(--green-5);
}

.Button.green:focus {
  box-shadow: 0 0 0 2px var(--green-7);
}

.IconButton {
  font-family: inherit;
  border-radius: 100%;
  height: 25px;
  width: 25px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: var(--grass-11);
  position: absolute;
  top: 10px;
  right: 10px;
}

.IconButton:hover {
  background-color: var(--grass-4);
}

.IconButton:focus {
  box-shadow: 0 0 0 2px var(--grass-7);
}

.Fieldset {
  display: flex;
  gap: 20px;
  align-items: center;
  margin-bottom: 15px;
}

.Label {
  font-size: 15px;
  color: var(--grass-11);
  width: 90px;
  text-align: right;
}

.Input {
  width: 100%;
  flex: 1;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  padding: 0 10px;
  font-size: 15px;
  line-height: 1;
  color: var(--grass-11);
  box-shadow: 0 0 0 1px var(--grass-7);
  height: 35px;
}

.Input:focus {
  box-shadow: 0 0 0 2px var(--grass-8);
}

@keyframes overlayShow {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes contentShow {
  from {
    opacity: 0;
    transform: translate(-50%, -48%) scale(0.96);
  }
  to {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
}



.TooltipContent {
  border-radius: 4px;
  padding: 10px 15px;
  font-size: 15px;
  line-height: 1;
  color: var(--grass-11);
  background-color: white;
  box-shadow: hsl(206 22% 7% / 35%) 0px 10px 38px -10px, hsl(206 22% 7% / 20%) 0px 10px 20px -15px;
  animation-duration: 400ms;
  animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
  will-change: transform, opacity;
}

.TooltipContent[data-state='delayed-open'][data-side='top'] {
  animation-name: slideDownAndFade;
}

.TooltipContent[data-state='delayed-open'][data-side='right'] {
  animation-name: slideLeftAndFade;
}

.TooltipContent[data-state='delayed-open'][data-side='bottom'] {
  animation-name: slideUpAndFade;
}

.TooltipContent[data-state='delayed-open'][data-side='left'] {
  animation-name: slideRightAndFade;
}

.TooltipArrow {
  fill: white;
}

.TooltipSpan {
  font-family: inherit;
  border-radius: 100%;
  display: inline-flex;
}

.TooltipSpan:hover {
  background-color: var(--grass-3);
}

.TooltipSpan:focus {
  box-shadow: 0 0 0 2px black;
}

@keyframes slideUpAndFade {
  from {
    opacity: 0;
    transform: translateY(2px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideRightAndFade {
  from {
    opacity: 0;
    transform: translateX(-2px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideDownAndFade {
  from {
    opacity: 0;
    transform: translateY(-2px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideLeftAndFade {
  from {
    opacity: 0;
    transform: translateX(2px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>
