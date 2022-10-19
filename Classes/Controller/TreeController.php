<?php

declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Controller;

use Petitglacon\CategoryTreebuilder\Builder\TreeBuilder;
use Petitglacon\CategoryTreebuilder\Enum\FileType;
use Petitglacon\CategoryTreebuilder\Manager\FileManager;
use Petitglacon\CategoryTreebuilder\Manager\QueryManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * TreeController
 */
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
    public function __construct(FileManager $fileManager, TreeBuilder $treeBuilder)
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
        return $this->htmlResponse();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function buildAction()
    {
        $this->fileManager->exportCSV($this->treeBuilder->buildExportTree());
        $this->treeBuilder->buildBackendTree($_POST, FileType::TEXT);
        $this->redirect('index');
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
