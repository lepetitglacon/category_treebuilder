<?php

namespace Petitglacon\CategoryTreebuilder\Builder;

use Petitglacon\CategoryTreebuilder\Domain\Model\Category;
use Petitglacon\CategoryTreebuilder\Domain\Repository\CategoryRepository;
use Petitglacon\CategoryTreebuilder\Import\ImporterCsv;
use Petitglacon\CategoryTreebuilder\Import\ImporterText;
use Petitglacon\CategoryTreebuilder\Enum\FileType;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class TreeBuilder
{
    /** @var CategoryRepository $categoryRepository */
    private CategoryRepository $categoryRepository;

    /** @var PersistenceManager $persistenceManager */
    private PersistenceManager $persistenceManager;

    public function __construct(
        CategoryRepository $categoryRepository,
        PersistenceManager $persistenceManager
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->persistenceManager = $persistenceManager;
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

    private function getCategories()
    {
        return $this->categoryRepository->findAll();
    }

    public function buildFrontendTree(): bool|array
    {
        $rootCategory = new Category();
        $rootCategory->setTitle('--Category Root--');
        $rootCategory->setUid(0);
        $rootCategory->setPid(0);
        $flattenCategories[0] = $rootCategory;

        foreach ($this->getCategories() as $category) {
            $flattenCategories[$category->getUid()] = $category;
        }

        if (count($flattenCategories) < 1) {
            return [];
        }

        $tree = [];
        foreach ($flattenCategories as $category){
            if ($category->getUid() === 0) {
                continue;
            }

            $tree[$category->getParent()?->getUid() ?? 0][] = $category->toArray();
        }

        return $this->createTreeNode($tree, $tree[0]);
    }

    private function createTreeNode(&$tree, $parents): array
    {
        $childrenTree = [];
        foreach ($parents as $parentUid => $category) {

            if(isset($tree[$category['uid']])) {
                $category['children'] = $this->createTreeNode($tree, $tree[$category['uid']]);
            }

            $childrenTree[] = $category;
        }
        return $childrenTree;
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