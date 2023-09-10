<?php

namespace Petitglacon\CategoryTreebuilder\Builder;

use Petitglacon\CategoryTreebuilder\Import\ImporterCsv;
use Petitglacon\CategoryTreebuilder\Import\ImporterText;
use Petitglacon\CategoryTreebuilder\Enum\FileType;
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
                $importer = new ImporterCsv($content);
                $categories = $importer->getCategories();
                break;

            case FileType::JSON:
                break;

            case FileType::PASSTHRGOUH:
                $categories = $content;
                break;
        }

        if (count($categories)) {
//            $this->queryManager->truncate();
            return $this->queryManager->insertOrUpdateCategories($categories);
        }
    }

    public function buildFrontendTree(): bool|array
    {
        $dbCategories[0] = ['uid' => 0];

        foreach ($this->queryManager->getCategoriesForFrontend() as $cat) {
            $dbCategories[$cat['uid']] = $cat;
        }

        if (count($dbCategories) <= 1) {
            return [];
        } else {
            $new = [];
            foreach ($dbCategories as $a){
                if ($a['uid'] == 0) {
                    continue;
                }
                $a['depth'] = $this->findDepth($a, $dbCategories);
                $new[$a['parent']][] = $a;
            }
            return $this->createFrontendTreeNode($new, $new[0]);
        }
    }

    private function createFrontendTreeNode(&$list, $parents): array
    {
        $tree = [];
        foreach ($parents as $key => $category){
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
            $cat = $array[$cat['parent']];
            if ($depth >= 999) return $depth;
        }

        return $depth;
    }

    public function buildExportTree() {
        return $this->queryManager->getCategoriesForExport();
    }

    public function generateFakeData() {

    }
}