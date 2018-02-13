<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'Invitations',
    'Invitations'
);

if (TYPO3_MODE === 'BE') {

    /**
     * Registers a Backend Module
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Visol.' . $_EXTKEY,
        'web',
        'invitations',
        'after:tx_news_m2',
        [
            'Invitation' => 'list, listArchive, listCreatedInvitations, approve, reject, remove',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.svg',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_invitations.xlf',
        ]
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'News category invitation'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_newscatinvite_domain_model_invitation',
    'EXT:newscatinvite/Resources/Private/Language/locallang_csh_tx_newscatinvite_domain_model_invitation.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newscatinvite_domain_model_invitation');
