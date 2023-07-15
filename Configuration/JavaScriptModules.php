<?php

return [
    // required import configurations of other extensions,
    // in case a module imports from another package
    'dependencies' => ['core', 'backend'],
    'imports' => [
        // recursive definiton, all *.js files in this folder are import-mapped
        // trailing slash is required per importmap-specification
        '@petitglacon/category-treebuilder' => 'EXT:category_treebuilder/Resources/Public/JavaScript/index.js',
    ],
];