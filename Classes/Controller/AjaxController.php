<?php

declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Controller;

use Doctrine\DBAL\Exception;
use Petitglacon\CategoryTreebuilder\Domain\Model\Category;
use Petitglacon\CategoryTreebuilder\Domain\Repository\CategoryRepository;
use Petitglacon\CategoryTreebuilder\Enum\ToastStatus;
use Petitglacon\CategoryTreebuilder\Utility\AjaxResponseUtility;
use Psr\Http\Message\ServerRequestInterface;
use Petitglacon\CategoryTreebuilder\Builder\TreeBuilder;
use Petitglacon\CategoryTreebuilder\Manager\FileManager;
use Petitglacon\CategoryTreebuilder\Manager\QueryManager;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
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
    public function index(): \Psr\Http\Message\ResponseInterface
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
     */
    public function move(Category $category): \Psr\Http\Message\ResponseInterface
    {
        $this->categoryRepository->update($category);
        return $this->jsonResponse(json_encode([
            'success' => $success,
            'message' => 'TODO'
        ]));
    }

    /**
     * Insert a new category
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function insert(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $args = $request->getParsedBody();
        $category = $args['category'];

        $cat = new Category(
            $category['uid'],
            $category['pid'],
            $category['parent'],
            $category['title']
        );
//
        $res = $this->queryManager->insertCategory($cat);

        if ($res['rows'] === 1) {
            $success = true;
            $message = 'category ' . $res['uid'] . ' created';
        } else {
            $success = false;
            $message = 'category ' . $res['uid'] . ' could not be created';
        }

        return $this->jsonResponse(json_encode([
            'success' => $success,
            'uid' => $res['uid'],
            'message' => $message
        ]));
    }

    /**
     * Update an existing category
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function update(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $args = $request->getParsedBody();
        $category = $args['category'];

        $cat = new Category(
            $category['uid'],
            $category['pid'],
            $category['parent'],
            $category['title']
        );
//
        $res = $this->queryManager->update($cat);

        if ($res['rows'] === 1) {
            $success = true;
            $message = 'category ' . $res['uid'] . ' created';
        } else {
            $success = false;
            $message = 'category ' . $res['uid'] . ' could not be created';
        }

        return $this->jsonResponse(json_encode([
            'success' => $success,
            'uid' => $res['uid'],
            'message' => $message,
            'res' => $res
        ]));
    }

    public function deleteAll(): ResponseInterface {
        $this->categoryRepository->removeAll();
        $this->persistenceManager->persistAll();
        return $this->jsonResponse(AjaxResponseUtility::getJsonResponse(ToastStatus::SUCCESS, 'All categories were removed'));
    }

    public function generateFakeData(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface {
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
