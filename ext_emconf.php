<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Category Tree Builder',
    'description' => 'Makes category tree management easier for contributors. Adds csv import/export features as well as built-in text builder.',
    'category' => 'module',
    'author' => 'petitglacon',
    'author_email' => 'contact@petitglacon.com',
    'state' => 'alpha',
    'clearCacheOnLoad' => 0,
    'version' => '0.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
