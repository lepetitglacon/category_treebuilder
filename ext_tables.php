<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'CategorytreeBuilder',
        'tools',
        'builder',
        '',
        [
            \Petitglacon\CategorytreeBuilder\Controller\CategoryController::class => 'index',
            \Petitglacon\CategorytreeBuilder\Controller\TreeController::class => 'index,build',
        ],
        [
            'access' => 'user,group',
            'icon'   => 'EXT:categorytree_builder/Resources/Public/Icons/user_mod_builder.svg',
            'labels' => 'LLL:EXT:categorytree_builder/Resources/Private/Language/locallang_builder.xlf',
        ]
    );

})();
