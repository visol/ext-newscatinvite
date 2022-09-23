<?php

declare(strict_types=1);

use Visol\Newscatinvite\Domain\Model\BackendUser;
use Visol\Newscatinvite\Domain\Model\News;
use GeorgRinger\News\Domain\Model\Category;
use Visol\Newscatinvite\Domain\Model\BackendUserGroup;
use Visol\Newscatinvite\Domain\Model\Invitation;

return [
    News::class => [
        'tableName' => 'tx_news_domain_model_news',
        'properties' => [
            'invitations' => [
                'fieldName' => 'invitations'
            ],
        ]
    ],
    Category::class => [
        'tableName' => 'sys_category',
    ],
    BackendUser::class => [
        'tableName' => 'be_users',
    ],
    BackendUserGroup::class => [
        'tableName' => 'be_groups',
    ],
    Invitation::class => [
        'tableName' => 'tx_newscatinvite_domain_model_invitation',
        'properties' => [
            'creator' => [
                'fieldName' => 'cruser_id'
            ],
        ]
    ],
];
