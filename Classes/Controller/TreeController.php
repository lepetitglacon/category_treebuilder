<?php

declare(strict_types=1);

namespace Petitglacon\CategoryTreebuilder\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Page\PageRenderer;

#[Controller]
class TreeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @param ModuleTemplateFactory $moduleTemplateFactory
     * @param PageRenderer $pageRenderer
     */
    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly PageRenderer            $pageRenderer,
    )
    {}

    /**
     * @return ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $this->pageRenderer->loadJavaScriptModule('@typo3/core/ajax/ajax-request.js');
        $this->pageRenderer->loadJavaScriptModule('@petitglacon/category-treebuilder/index.js');
        $this->pageRenderer->addCssFile('EXT:category_treebuilder/Resources/Public/dist/assets/index.css');

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}