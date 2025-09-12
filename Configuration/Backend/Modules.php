<?php

return [
    'web_NewscatinviteInvitations' => [
        'parent' => 'web',
        'position' => [
            'after' => 'bottom',
        ],
        'access' => 'user',
        'labels' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_invitations.xlf',
        'extensionName' => 'Newscatinvite',
        'controllerActions' => [
            'Visol\Newscatinvite\Controller\InvitationController' => [
                'list',
                'listArchive',
                'listCreatedInvitations',
                'approve',
                'reject',
                'remove',
            ],
        ],
    ],
];
