<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News category invitation',
    'description' => 'Send invitations to let other users add news to their categories.',
    'category' => 'module',
    'author' => 'Jonas Renggli',
    'author_email' => 'jonas.renggli@visol.ch',
    'author_company' => 'visol digitale Dienstleistungen GmbH',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '2.1.3',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.7.999',
            'news' => '6.1.0-6.1.999',
        ],
        'conflicts' => [],
        'suggests' => [
            'eventnews' => '2.1.0-2.1.999',
        ],
    ],
];
