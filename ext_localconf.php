<?php

use Visol\Newscatinvite\Controller\InvitationController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Visol\Newscatinvite\Update\TranslationUpdateWizard;

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

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][GeorgRinger\News\Domain\Repository\NewsRepository::class] = [
    'className' => \Visol\Newscatinvite\Domain\Repository\NewsRepository::class
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['newscatinvite_insert_translations'] =
    TranslationUpdateWizard::class;
