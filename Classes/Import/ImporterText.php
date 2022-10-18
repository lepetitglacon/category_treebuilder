<?php

namespace Petitglacon\CategoryTreebuilder\Import;

use Petitglacon\CategoryTreebuilder\Object\Category;

class ImporterText extends AbstractImporter
{
    public function getCategories() : array|Category
    {
        /** @var array|Category $categories */
        $categories = [];

        if (isset($this->content['textCategoryTree'])) {

            $categoryTreeFromForm = str_replace('\"', '', $this->content['textCategoryTree']);

            foreach (explode('\\n', $categoryTreeFromForm) as $lineCount => $line) {

                if (empty($line)) {
                    break;
                }

                $tabsCount = substr_count($line, '\\t');

                // if category parent is root
                if (substr_count($line, '\\t') == 0) {
                    $category = $this->createCategory($this->getUidCounter(), self::ROOT_PID,self::ROOT_PARENT, $line);
                } else {
                    $category = $this->createCategory($this->getUidCounter(), self::ROOT_PID, $this->parents[$tabsCount - 1]->getUid(), $line);
                }

                $this->addParent($category, $tabsCount);
                $categories[] = $category->toArray();
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