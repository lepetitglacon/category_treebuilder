<?php

namespace Petitglacon\CategoryTreebuilder\Import;

use Petitglacon\CategoryTreebuilder\Object\Category;

class ImporterText extends AbstractImporter
{

    public function getCategories() : array|Category
    {
        /** @var array|Category $categories */
        $categories = [];
        $this->uidCounter = $this->queryManager->getLastInsertedUid() + 1;


        if (isset($this->content['textCategoryTree'])) {

            $categoryTreeFromForm = str_replace('\"', '', $this->content['textCategoryTree']);

            foreach (explode('\\n', $categoryTreeFromForm) as $lineCount => $line) {

                if (empty($line)) {
                    break;
                }

                $tabsCount = substr_count($line, '\\t');

                // get uid/title
                $matches = [];
                preg_match_all('/\[([0-9]*?)\]/', $line, $matches);
                if (!empty($matches[1])) {
                    $uid = (int)$matches[1][count($matches[1]) - 1];
                    $title = trim(preg_replace('/\[[0-9]*?\](?! \[([0-9]*?)\])/', '', $line));
                    $updated = true;
                } else {
                    $uid = $this->getUidCounter();
                    $title = $line;
                    $updated = false;
                }
                $title = trim(str_replace(['\\t', '\\', '"'], '', $title));

                // get parent
                if (substr_count($line, '\\t') == 0) {
                    $parent = self::ROOT_PARENT;
                } else {
                    $parent = $this->parents[$tabsCount - 1]->getUid();
                }

                // set pid
                if (isset($this->content['root-pid'])) {
                    $pid = $this->content['root-pid'];
                } else {
                    $pid = self::ROOT_PID;
                }

                $category = new Category($uid, $pid, $parent, $title, $updated);

                $this->addParent($category, $tabsCount);
                $categories[] = $category;
            }
        }
        return $categories;
    }

    private function addParent($category, $tabCount) {

        // reset parent array
        if ($tabCount == 0) {
            $this->parents[$tabCount] = $category;
        }

        $this->parents[$tabCount] = $category;
    }
}