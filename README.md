# Category Tree Builder
Build and manage easily your category tree.

## Install

> :warning: Do not install this extension in production environnement ! :warning: <br>
> This extension is still in development phase, it might ruin your category folder structure or delete all your categories
> Install at your own risks

### Manual install
1. Download the extension.
2. Put the extension in a root folder of your TYPO3 site. `ex. "packages"`
3. In your root composer.json add the following
```json
	"repositories": [
		{
			"type": "path",
			"url": "packages/*"
		}
	],
```
4. Run the command `composer req petitglacon/category-treebuilder:@dev`
5. You'll find the module under admin tab
6. Enjoy

## Main feature
- Javscript category tree using SortableJS, create, move, delete your categories at will

## Roadmap
reintroduce v11 features like
- Built-in tab indented text builder
- Category tree viewer + auto-scrolling with text builder
- Category uid persistence (move categories without losing uids)
- Import/export categories as ~~`text`~~, `csv`, ~~`json`~~ files
- Auto export after each tree change (history)<br>

and continue growing this extension
- toggle deleted categories view
- folders handling
- typoscript settings
- assuring quality (error handling, code optimisation, ...)
- realtime javascript tree

## Versions
See version changelog in changelog.md

## Infos
This extension was made for the [Typo3](https://typo3.fr/) project, [see on TER](https://extensions.typo3.org/extension/category_treebuilder)
