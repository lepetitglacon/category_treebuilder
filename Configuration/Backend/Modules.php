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
        'labels' => 'Category Tree Builder',
        'extensionName' => 'CategoryTreebuilder',
        'controllerActions' => [
            \Petitglacon\CategoryTreebuilder\Controller\TreeController::class => [
                'index','build','export','import'
            ],
        ],
    ],
];