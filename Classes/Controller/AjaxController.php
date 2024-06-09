<?php

declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Controller;

use Petitglacon\CategoryTreebuilder\Domain\Model\Category;
use Petitglacon\CategoryTreebuilder\Domain\Repository\CategoryRepository;
use Petitglacon\CategoryTreebuilder\Enum\ToastStatus;
use Petitglacon\CategoryTreebuilder\Utility\AjaxResponseUtility;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ServerRequestInterface;
use Petitglacon\CategoryTreebuilder\Builder\TreeBuilder;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

#[Controller]
class AjaxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @param TreeBuilder $treeBuilder
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        private readonly TreeBuilder $treeBuilder,
        private readonly CategoryRepository $categoryRepository,
        private readonly PersistenceManager $persistenceManager
    ) {}

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $tree = $this->treeBuilder->buildFrontendTree();
        return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(
            ToastStatus::SUCCESS,
            'Tree loaded',
            $tree,
            false
        ));
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function insertAction(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $args = $request->getParsedBody()['category'];

        $category = new Category();
        $category->setTitle($args['title']);
        $category->setPid((int)$args['pid']);

        if ($args['parent'] == 0) {
            $category->setParent(null);
        } else {
            $parent = $this->categoryRepository->findOneBy(['uid' => $args['parent']]);
            $category->setParent($parent ?? $category->getParent());
        }

        $this->categoryRepository->add($category);
        $this->persistenceManager->persistAll();

        $title = $category->getTitle();
        $categoryUid = $category->getUid();

        return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(
            ToastStatus::SUCCESS,
            "Category \"$title\" [$categoryUid] created")
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function updateAction(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $args = $request->getParsedBody()['category'];

        $categoryUid = $args['__identity'] ?? $args['uid'] ?? null;
        if (is_null($categoryUid)) {
            return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(
                ToastStatus::ERROR,
                'Category uid not found')
            );
        }

        $category = $this->categoryRepository->findOneBy(['uid' => $categoryUid]);
        if (is_null($category)) {
            return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(
                ToastStatus::ERROR,
                'Category not found in repository')
            );
        }
        $title = $category->getTitle();

        $category->setTitle($args['title'] ?? $category->getTitle());
        $category->setPid($args['pid'] ? (int)$args['pid'] : null ?? $category->getPid());

        if ($args['parent'] == 0) {
            $category->setParent(null);
        } else {
            $parent = $this->categoryRepository->findOneBy(['uid' => $args['parent']]);
            $category->setParent($parent ?? $category->getParent());
        }

        // TODO sorting
        // https://stackoverflow.com/questions/36896377/typo3-commandcontroller-how-to-set-table-field-sorting-of-extbase-object

        $this->categoryRepository->update($category);
        $this->persistenceManager->persistAll();

        return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(
            ToastStatus::SUCCESS,
            "Category \"$title\" [$categoryUid] updated")
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function deleteAction(ServerRequestInterface $request): ResponseInterface {
        $args = $request->getParsedBody()['category'];

        $categoryUid = $args['__identity'] ?? $args['uid'] ?? null;
        if (is_null($categoryUid)) {
            return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(
                ToastStatus::ERROR,
                'Category uid not found')
            );
        }

        $category = $this->categoryRepository->findOneBy(['uid' => $categoryUid]);
        if (is_null($category)) {
            return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(
                ToastStatus::ERROR,
                'Category not found in repository')
            );
        }
        $title = $category->getTitle();

        $this->categoryRepository->remove($category);
        $this->persistenceManager->persistAll();
        return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(
            ToastStatus::SUCCESS,
            "Category $title has been removed")
        );
    }

    /**
     * @return ResponseInterface
     */
    public function deleteAllAction(): ResponseInterface {
        $this->categoryRepository->removeAll();
        $this->persistenceManager->persistAll();
        return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(ToastStatus::SUCCESS, 'All categories were removed'));
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function generateFakeDataAction(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface {
        $faker = \Faker\Factory::create();

        $parents = [];
        for ($i = 0; $i < 100; $i++) {
            $category = new Category();
            $category->setPid(0);
            $category->setTitle($faker->words($faker->numberBetween(1, 10), true));
            $category->setParent($faker->randomElement($parents));

            $parents[] = $category;
            $this->categoryRepository->add($category);
        }

        $this->persistenceManager->persistAll();
        return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(ToastStatus::SUCCESS, 'Fake data generated'));
    }

}
