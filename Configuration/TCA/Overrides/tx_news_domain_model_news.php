<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

$tmp_newscatinvite_columns = [
    'invitations' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.tx_newscatinvite_invitations',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_newscatinvite_domain_model_invitation',
            'foreign_table_where' => ' ORDER BY tx_newscatinvite_domain_model_invitation.tstamp DESC ',
            'foreign_field' => 'news',
            'maxitems' => 9999,
            'appearance' => [
                'collapseAll' => 1,
                'levelLinksPosition' => 'top',
                'showSynchronizationLink' => 1,
                'showPossibleLocalizationRecords' => 1,
                'newRecordLinkTitle' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.tx_newscatinvite_invitations.newRecordLinkTitle',
                'showAllLocalizationLink' => 1,
            ]
        ]
    ],
];

ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $tmp_newscatinvite_columns
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    'invitations',
    '',
    'after:categories'
);
