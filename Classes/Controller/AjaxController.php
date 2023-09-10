<?php

declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Controller;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ServerRequestInterface;
use Petitglacon\CategoryTreebuilder\Builder\TreeBuilder;
use Petitglacon\CategoryTreebuilder\Enum\FileType;
use Petitglacon\CategoryTreebuilder\Manager\FileManager;
use Petitglacon\CategoryTreebuilder\Manager\QueryManager;
use Petitglacon\CategoryTreebuilder\Object\Category;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Http\ServerRequest;

#[Controller]
class AjaxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @param FileManager $fileManager
     * @param TreeBuilder $treeBuilder
     * @param ModuleTemplateFactory $moduleTemplateFactory
     * @param PageRenderer $pageRenderer
     * @param QueryManager $queryManager
     */
    public function __construct(
        private readonly FileManager             $fileManager,
        private readonly TreeBuilder             $treeBuilder,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly PageRenderer            $pageRenderer,
        private readonly QueryManager            $queryManager,
    ) {}

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(): \Psr\Http\Message\ResponseInterface
    {
        $tree = $this->treeBuilder->buildFrontendTree();

        $success = false;
        if ($tree) {
            $success = true;
        }

        return $this->jsonResponse(json_encode([
            'success' => $success,
            'tree' => $tree,
            'message' => 'message'
        ]));
    }

    /**
     * Move a category to a new parent
     *
     * @return ResponseInterface
     */
    public function move(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $args = $request->getParsedBody();
        $uid = $args['uid'];
        $parent = $args['parent'];

        $res = $this->queryManager->updateParent($uid, $parent);

        if ($res) {
            $success = true;
        } else {
            $success = false;
        }

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

    /**
     * Insert or update a category
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function insertOrUpdate(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
//        $args = $request->getParsedBody();
//        $categoryFromRequest = $args['category'];
//
//        $this->verifyCategoryFromRequest();
//
//        if ($args['category']['uid']) {
//            $catFromDB = $this->queryManager->getCategory($categoryFromRequest['uid']);
//        }
//
//        $cat = new Category(
//            $categoryFromRequest['uid'],
//            $categoryFromRequest['pid'],
//            $categoryFromRequest['parent'],
//            $categoryFromRequest['title']
//        );
////
//        $res = $this->queryManager->insertCategory($cat);
//
//        if ($res['rows'] === 1) {
//            $success = true;
//            $message = 'category ' . $res['uid'] . ' created';
//        } else {
//            $success = false;
//            $message = 'category ' . $res['uid'] . ' could not be created';
//        }
//
//        return $this->jsonResponse(json_encode([
//            'success' => $success,
//            'uid' => $res['uid'],
//            'message' => $message
//        ]));

        return $this->jsonResponse(json_encode([
            'success' => false,
            'message' => 'TODO'
        ]));
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function generateFakeData(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface {
        $faker = \Faker\Factory::create();

        /** @var Category[] $categories */
        $categories = [];

        $categoriesStartUids = $this->queryManager->getLastInsertedUid();

        for ($i = 0; $i < 100; $i++) {
            $cat = new Category(
                ++$categoriesStartUids,
                3,
                $faker->randomElement([25, 28, 30, $categoriesStartUids-1]),
                implode(' ', $faker->words($i % 5))
            );
            $categories[] = $cat->toArray();
        }

        $success = $this->queryManager->bulkInsert($categories) > 0;
        return $this->jsonResponse(json_encode([
            'success' => $success,
            'message' => 'TEST',
        ]));
    }


    private function verifyCategoryFromRequest() {

    }


}
