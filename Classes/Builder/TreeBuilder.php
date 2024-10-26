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

    /**
     * @return array|\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface[]|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    private function getCategories()
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * @return bool|array
     */
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

    /**
     * @param $tree
     * @param $parents
     * @return array
     */
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

    public function parseTextTree($text, $settings = [])
    {
        $rootPid = $settings['pid'] ?? 0;
        $parentUid = $settings['parent'] ?? 0;
        $rootParentCategory = $this->categoryRepository->findByUid($parentUid);

        $categories = [];
        $parents = [$rootParentCategory];

        if (!empty($text)) {

            // trim " from json encoded string
            $categoryTreeFromForm = str_replace('"', '', $text);

            foreach (explode('\\n', $categoryTreeFromForm) as $lineCount => $line) {
                if (empty($line)) {
                    break;
                }
                $category = new Category();
                $depth = substr_count($line, '\\t');

//                $matches = [];
//                preg_match_all('/\[([0-9]*?)\]/', $line, $matches);
//                if (!empty($matches[1])) {
//                    $uid = (int)$matches[1][count($matches[1]) - 1];
//                    $title = trim(preg_replace('/\[[0-9]*?\](?! \[([0-9]*?)\])/', '', $line));
//                } else {
                    $title = $line;
//                }
                $title = trim(str_replace(['\\t', '\\', '"'], '', $title));

                $category->setTitle($title);
                $category->setParent($parents[$depth]);
                $category->setPid($category?->getParent()?->getPid() ?? (int)$rootPid);

                $parents[$depth + 1] = $category;
                $categories[] = $category;
            }
        }

        return $categories;
    }
}