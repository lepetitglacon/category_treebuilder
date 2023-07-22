<?php

use Petitglacon\CategoryTreebuilder\Controller\AjaxController;

return [
    'category_treebuilder_index' => [
        'path' => '/category_treebuilder/index',
        'target' => AjaxController::class . '::index',
    ],
    'category_treebuilder_move' => [
        'path' => '/category_treebuilder/move',
        'target' => AjaxController::class . '::move',
    ],
    'category_treebuilder_insert' => [
        'path' => '/category_treebuilder/insert',
        'target' => AjaxController::class . '::insert',
    ],
];