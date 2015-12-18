<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$tmp_newscatinvite_columns = array(
    'invitations' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.tx_newscatinvite_invitations',
        'config' => array(
            'type' => 'inline',
            'foreign_table' => 'tx_newscatinvite_domain_model_invitation',
            'foreign_table_where' => ' ORDER BY tx_newscatinvite_domain_model_invitation.tstamp DESC ',
            'foreign_field' => 'news',
            'maxitems' => 9999,
            'appearance' => array(
                'collapseAll' => 1,
                'levelLinksPosition' => 'top',
                'showSynchronizationLink' => 1,
                'showPossibleLocalizationRecords' => 1,
                'newRecordLinkTitle' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.tx_newscatinvite_invitations.newRecordLinkTitle',
                'showAllLocalizationLink' => 1,
            )
        )
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news',
    $tmp_newscatinvite_columns);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    'invitations',
    '',
    'after:categories'
);
