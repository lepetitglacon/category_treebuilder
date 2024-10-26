<?php

use Petitglacon\CategoryTreebuilder\Controller\AjaxController;

return [
    'category_treebuilder_index' => [
        'path' => '/category_treebuilder/index',
        'target' => AjaxController::class . '::indexAction',
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
    'category_treebuilder_generatefakedata' => [
        'path' => '/category_treebuilder/generatefakedata',
        'target' => AjaxController::class . '::generateFakeDataAction',
    ],
    'category_treebuilder_deleteall' => [
        'path' => '/category_treebuilder/deleteall',
        'target' => AjaxController::class . '::deleteAllAction',
    ],
    'category_treebuilder_delete' => [
        'path' => '/category_treebuilder/delete',
        'target' => AjaxController::class . '::deleteAction',
    ],
    'category_treebuilder_import_from_text' => [
        'path' => '/category_treebuilder/importFromText',
        'target' => AjaxController::class . '::importFromTextAction',
    ],
];