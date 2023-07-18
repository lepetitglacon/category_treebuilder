<?php

use Petitglacon\CategoryTreebuilder\Controller\AjaxController;

return [
    'category_treebuilder_index' => [
        'path' => '/category_treebuilder/index',
        'target' => AjaxController::class . '::index',
    ],
];