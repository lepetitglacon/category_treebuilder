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
        foreach ($flattenCategories as $category) {
            if ($category->getUid() === 0) {
                $tree[0][] = $category->toArray();
                continue;
            }

            $tree[$category->getParent()?->getUid() ?? 1][] = $category->toArray();
        }

        return $this->createTreeNode($tree, $tree[0]);
    }

    private function createTreeNode(&$tree, $parents): array
    {
        $rootTree = [];
        foreach ($parents as $parentUid => $category) {

            if ($category['uid'] == 0) {
                $category['children'] = $this->createTreeNode(
                    $tree,
                    $tree[1]
                );
            } else {
                if(isset($tree[$category['uid']])) {
                    $category['children'] = $this->createTreeNode(
                        $tree,
                        $tree[$category['uid']]
                    );
                }
            }


            $rootTree[] = $category;
        }

        return $rootTree;
    }
}