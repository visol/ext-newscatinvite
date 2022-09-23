<?php

use Visol\Newscatinvite\Controller\InvitationController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

ExtensionUtility::configurePlugin(
    'Newscatinvite',
    'Invitations',
    [
        InvitationController::class => 'list, show, new, create, edit, update, remove',

    ],
    // non-cacheable actions
    [
        InvitationController::class => 'create, update, remove',

    ]
);
