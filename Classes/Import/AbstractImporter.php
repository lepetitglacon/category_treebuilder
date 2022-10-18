<?php

namespace Petitglacon\CategoryTreebuilder\Import;

use Petitglacon\CategoryTreebuilder\Object\Category;

abstract class AbstractImporter
{
    const ROOT_PARENT = 0;
    const ROOT_PID = 3;

    protected $content;
    protected $categories = [];
    protected $parents;
    protected $uidCounter = 1;

    /**
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    protected function createCategory($uid, $pid, $parent, $title): Category
    {
        return new Category($uid, $pid, $parent, str_replace(['\\t', '\\', '"'], [''], $title));
    }

    public abstract function getCategories(): array|Category;

    /**
     * @return int
     */
    public function getUidCounter(): int
    {
        return $this->uidCounter++;
    }

}