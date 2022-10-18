<?php

namespace Petitglacon\CategoryTreebuilder\Builder;

class CategoryBuilder
{
    private $categories;

    public function getCategories($file) {
        $parser = getParser($file);


        return $this->categories;
    }

    private function getParser($file) {
        if (is_string($file)) {

        }
    }
}