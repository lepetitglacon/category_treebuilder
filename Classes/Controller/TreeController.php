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
class TreeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var int
     */
    private int $depthCounter = 0;

    /**
     * @var int
     */
    private int $uidCounter = 1;

    /**
     * @var array
     */
    private array $tree = [];

    /**
     * @var array
     */
    private array $parents = [];

    /**
     * @var FileManager $fileManager
     */
    private FileManager $fileManager;

    /**
     * @var TreeBuilder $treeBuilder
     */
    private TreeBuilder $treeBuilder;

    /**
     * @param FileManager $fileManager
     */
    public function __construct(
        FileManager $fileManager,
        TreeBuilder $treeBuilder,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly PageRenderer $pageRenderer,
    )
    {
        $this->fileManager = $fileManager;
        $this->treeBuilder = $treeBuilder;
    }


    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $tree = $this->treeBuilder->buildFrontendTree();

        $this->view->assignMultiple([
            'tree' => $tree,
            'jsonTree' => json_encode($tree)
        ]);


        // Load JavaScript via PageRenderer
        $this->pageRenderer->loadJavaScriptModule('@petitglacon/category-treebuilder');
        $this->pageRenderer->addCssFile('EXT:category_treebuilder/Resources/Public/Styles/styles.css');

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        // Adding title, menus, buttons, etc. using $moduleTemplate ...
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * @return ResponseInterface
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
     */
    public function buildAction()
    {
        $this->fileManager->exportCSV($this->treeBuilder->buildExportTree());
        $res = $this->treeBuilder->buildBackendTree($_POST, FileType::TEXT);

        $this->addFlashMessage("inserted : ${res['insert']}, updated : ${res['update']}, deleted : ${res['delete']}", 'Results');

        return $this->redirect('index');
    }

    public function exportAction() {
        $this->fileManager->exportCSV($this->treeBuilder->buildExportTree());
        $this->redirect('index');
    }

    public function importAction() {

        if (isset($_FILES['file'])) {
            if ($filepath = $this->fileManager->saveExternalFile()) {
                $this->fileManager->exportCSV($this->treeBuilder->buildExportTree());
                $this->treeBuilder->buildBackendTree($this->fileManager->getCsvContent($filepath), FileType::PASSTHRGOUH);
            }
        }
        $this->redirect('index');
    }

    public function clearHistoryAction() {
        // todo
    }
}
