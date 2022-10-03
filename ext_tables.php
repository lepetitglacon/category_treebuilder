<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'CategoryTreebuilder',
        'tools',
        'builder',
        '',
        [
            \Petitglacon\CategoryTreebuilder\Controller\TreeController::class => 'index,build',
        ],
        [
            'access' => 'user,group',
            'icon'   => 'EXT:category_treebuilder/Resources/Public/Icons/icon.svg',
            'labels' => 'LLL:EXT:category_treebuilder/Resources/Private/Language/locallang_builder.xlf',
        ]
    );

})();
