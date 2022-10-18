<?php

namespace Petitglacon\CategoryTreebuilder\Builder;

use Petitglacon\CategorytreeBuilder\Enum\FileType;
use Petitglacon\CategoryTreebuilder\Import\ImporterText;
use Petitglacon\CategoryTreebuilder\Manager\QueryManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TreeBuilder
{

    /**
     * @var QueryManager $queryManager
     */
    private QueryManager $queryManager;

    public function __construct(QueryManager $queryManager)
    {
        $this->queryManager = $queryManager;
    }

    public function buildBackendTree($content, int $type) {
        $categories = [];

        switch ($type) {
            case FileType::TEXT:
                $importer = new ImporterText($content);
                $categories = $importer->getCategories();
                break;

            case FileType::CSV:
            case FileType::JSON:
                break;
        }

        if (count($categories)) {
            $this->queryManager->truncate();
            $this->queryManager->insertCategories($categories);
        }
    }

    public function buildFrontendTree(): bool|array
    {
        $dbCategories = $this->queryManager->getCategoriesFromDatabase();

        if (count($dbCategories)) {
            $new = [];
            foreach ($dbCategories as $a){
                $a['depth'] = $this->findDepth($a, $dbCategories);
                $new[$a['parent']][] = $a;
            }
            return $this->createFrontendTreeNode($new, $new[0]);
        } else {
            return false;
        }
    }

    private function createFrontendTreeNode(&$list, $parents): array
    {
        $tree = [];
        foreach ($parents as $key => $category){
//            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($category, 'category');
//            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(isset($list[$category['uid']]), 'in list');
            if(isset($list[$category['uid']])){
                $category['children'] = $this->createFrontendTreeNode($list, $list[$category['uid']]);
            }
            $tree[] = $category;
        }
        return $tree;
    }

    private function findDepth($category, $array): int
    {
        $depth = 0;
        $cat = $category;
        while (!empty($cat['parent'])) {
            $depth++;
            $cat = $array[$cat['parent']-1];
        }
        return $depth;
    }

    public function buildExportTree() {
        return $this->queryManager->getCategoriesForExport();
    }
}