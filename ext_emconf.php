<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Category TreeBuilder',
    'description' => 'Makes category tree management easier for contributors. Adds csv import/export features as well as built-in text builder.',
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
