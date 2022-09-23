<?php

use Visol\Newscatinvite\Controller\InvitationController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

ExtensionUtility::registerPlugin(
    'newscatinvite',
    'Invitations',
    'Invitations'
);

/**
 * Registers a Backend Module
 */
ExtensionUtility::registerModule(
    'Newscatinvite',
    'web',
    'invitations',
    'after:tx_news_m2',
    [
        InvitationController::class => 'list, listArchive, listCreatedInvitations, approve, reject, remove',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:newscatinvite/ext_icon.svg',
        'labels' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_invitations.xlf',
    ]
);

ExtensionManagementUtility::addStaticFile(
    'newscatinvite',
    'Configuration/TypoScript',
    'News category invitation'
);

ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_newscatinvite_domain_model_invitation',
    'EXT:newscatinvite/Resources/Private/Language/locallang_csh_tx_newscatinvite_domain_model_invitation.xlf'
);
ExtensionManagementUtility::allowTableOnStandardPages('tx_newscatinvite_domain_model_invitation');
