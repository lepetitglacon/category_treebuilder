# Category Tree Builder
This extension makes category tree building simple.

## Install
Download the extension, put it in the "packages" folder.

Then run :
`composer req petitglacon/category-treebuilder:@dev`

## Main features
- Built-in tab indented text builder
- Category tree viewer + auto-scrolling with text builder
- Category uid persistence (move categories without losing uids)
- Import/export categories as ~~`text`~~, `csv`, ~~`json`~~ files
- Auto export after each tree change (history)

> :warning: Take care, this extension is still in development phase, it may ruin your category folder structure or delete all your categories

## Roadmap
- toggle deleted categories view
- folders handling
- typoscript settings
- assuring quality (error handling, code optimisation, ...)
- realtime javascript tree

## Versions
### 1.0.2
- You can now use the pid field to store categories where you want (still in 1 directory only)
- Fixed csv import/export (located in `fileadmin/user_upload/category_treebuilder`)
- Added an auto scroll on tree views
### 1.0.1
- Beta testing

## Screenshot
![category-tree](https://user-images.githubusercontent.com/58629249/198850999-4c7c9a0f-85ee-4c72-b9bf-7d3c4789262b.PNG)

## Infos
This extension was made for the [Typo3](https://typo3.fr/) project, [TER repo](https://extensions.typo3.org/extension/category_treebuilder)
