<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Category Tree Builder',
    'description' => 'Build and manage easily your category tree.',
    'category' => 'module',
    'author' => 'petitglacon',
    'author_email' => 'estebangagneur03@gmail.com',
    'state' => 'beta',
    'clearCacheOnLoad' => 1,
    'version' => '2.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-12.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
