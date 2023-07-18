<?php

declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Controller;

use Petitglacon\CategoryTreebuilder\Builder\TreeBuilder;
use Petitglacon\CategoryTreebuilder\Enum\FileType;
use Petitglacon\CategoryTreebuilder\Manager\FileManager;
use Petitglacon\CategoryTreebuilder\Manager\QueryManager;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Page\PageRenderer;

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
    )
    {}


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
}
