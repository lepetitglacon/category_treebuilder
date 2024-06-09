<?php

/**
 * Definitions for modules provided by EXT:examples
 */
return [
    'category_treebuilder' => [
        'parent' => 'tools',
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/category_treebuilder/',
        'iconIdentifier' => 'category_treebuilder',
        'labels' => 'LLL:EXT:category_treebuilder/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'CategoryTreebuilder',
        'controllerActions' => [
            \Petitglacon\CategoryTreebuilder\Controller\TreeController::class => [
                'index','build','export','import'
            ],
            \Petitglacon\CategoryTreebuilder\Controller\AjaxController::class => [
                'update','insert','delete', 'deleteAll', 'generateFakeData'
            ],
        ],
    ],
];