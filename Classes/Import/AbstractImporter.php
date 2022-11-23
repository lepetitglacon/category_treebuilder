<?php

namespace Petitglacon\CategoryTreebuilder\Import;

use Petitglacon\CategoryTreebuilder\Manager\QueryManager;
use Petitglacon\CategoryTreebuilder\Object\Category;

abstract class AbstractImporter
{
    const ROOT_PARENT = 0;
    const ROOT_PID = 0;

    protected $content;
    protected $categories = [];
    protected $parents;
    protected $uidCounter = 1;

    /**
     * @var QueryManager
     */
    protected QueryManager $queryManager;

    /**
     * @param QueryManager $queryManager
     */
    public function __construct($content)
    {
        $this->queryManager = new QueryManager();
        $this->content = $content;
    }

    public function setContent($content) {
        $this->content = $content;
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