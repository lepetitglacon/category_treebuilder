<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Category TreeBuilder',
    'description' => 'creates category tree from indented text file',
    'category' => 'plugin',
    'author' => 'petitglacon',
    'author_email' => '',
    'state' => 'alpha',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
