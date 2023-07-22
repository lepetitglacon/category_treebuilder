<?php

declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Controller;

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
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function move(): \Psr\Http\Message\ResponseInterface
    {

        $success = false;

        return $this->jsonResponse(json_encode([
            'success' => $success,
            'message' => 'error blabla'
        ]));
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function insert(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {

//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->arguments, '$this->arguments');
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($request->getParsedBody(), '$this->arguments');

//        $cat = new Category();
//
//        $success = $this->queryManager->insertCategory();
        $success = false;

        return $this->jsonResponse(json_encode([
            'success' => $success,
            'message' => 'error blabla'
        ]));
    }
}
