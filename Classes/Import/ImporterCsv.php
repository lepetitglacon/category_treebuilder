<?php

namespace Petitglacon\CategoryTreebuilder\Import;

use Petitglacon\CategoryTreebuilder\Object\Category;

class ImporterCsv extends AbstractImporter
{

    /**
     * @return Category[]
     */
    public function getCategories() : Category
    {
         return [new Category(0, 0, 0, 'bug category', false)];
    }
}