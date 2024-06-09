<?php

use Petitglacon\CategoryTreebuilder\Controller\AjaxController;

return [
    'category_treebuilder_index' => [
        'path' => '/category_treebuilder/index',
        'target' => AjaxController::class . '::indexAction',
    ],
    'category_treebuilder_move' => [
        'path' => '/category_treebuilder/move',
        'target' => AjaxController::class . '::moveAction',
    ],
    'category_treebuilder_insert' => [
        'path' => '/category_treebuilder/insert',
        'target' => AjaxController::class . '::insertAction',
    ],
    'category_treebuilder_update' => [
        'path' => '/category_treebuilder/update',
        'methods' => ['POST'],
        'target' => AjaxController::class . '::updateAction',
    ],
    'category_treebuilder_insertorupdate' => [
        'path' => '/category_treebuilder/insert',
        'target' => AjaxController::class . '::insertOrUpdate',
    ],
    'category_treebuilder_generatefakedata' => [
        'path' => '/category_treebuilder/generatefakedata',
        'target' => AjaxController::class . '::generateFakeDataAction',
    ],
    'category_treebuilder_deleteall' => [
        'path' => '/category_treebuilder/deleteall',
        'target' => AjaxController::class . '::deleteAllAction',
    ],
];