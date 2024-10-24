<template>
  <DialogRoot>
    <DialogTrigger
        @click.prevent.stop
        class="Button grass"
    >
      Edit profile
    </DialogTrigger>
    <DialogPortal :disabled="true">
      <DialogOverlay class="DialogOverlayy" />
      <DialogContent
          class="DialogContent"
      >
        <DialogTitle class="DialogTitle">
          Import from text
        </DialogTitle>
        <DialogDescription class="DialogDescription">
          Use tab separated text to import categories/sub-categories
        </DialogDescription>
        <fieldset class="Fieldset">
          <label
              class="Label"
              for="name"
          > Name </label>
          <input
              id="name"
              class="Input"
              defaultValue="Pedro Duarte"
          >
        </fieldset>
        <fieldset class="Fieldset">
          <label
              class="Label"
              for="username"
          > Username </label>
          <input
              id="username"
              class="Input"
              defaultValue="@peduarte"
          >
        </fieldset>
        <div :style="{ display: 'flex', marginTop: 25, justifyContent: 'flex-end' }">
          <DialogClose as-child>
            <button
                class="Button green"
            >
              Save changes
            </button>
          </DialogClose>
        </div>
        <DialogClose
            class="IconButton"
            aria-label="Close"
        >
          <Icon icon="lucide:x" />
        </DialogClose>
      </DialogContent>
    </DialogPortal>
  </DialogRoot>
</template>

<script setup>
import {
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogOverlay,
  DialogPortal,
  DialogTitle,
  DialogTrigger
} from "radix-vue";
</script>

<style scoped lang="css">

/* reset */
button,
fieldset,
input {
  all: unset;
}

.DialogOverlayy {
  background-color: var(--black-a9);
  position: fixed;
  inset: 0;
  z-index: 0!important;
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
</style>
