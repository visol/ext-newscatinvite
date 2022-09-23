<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

if (!defined('TYPO3')) {
    die('Access denied.');
}

$extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('newscatinvite');

$GLOBALS['TCA']['tx_newscatinvite_domain_model_invitation'] = [
    'ctrl' => [
        'title' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation',
        'label' => 'category',
        'label_alt' => 'status',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'searchFields' => 'status,sent,category,news,approving_beuser,',
        'iconfile' => 'EXT:newscatinvite/Resources/Public/Icons/tx_newscatinvite_domain_model_invitation.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'status, sent, category, news, approving_beuser',
    ],
    'types' => [
        '1' => ['showitem' => 'status, sent, category, news, approving_beuser'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'status' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                    [
                        'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.status.1',
                        1
                    ],
                    [
                        'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.status.-1',
                        -1
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'cruser_id' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'be_users',
                'foreign_table_where' => 'ORDER BY username',
                'items' => [
                    ['', ''],
                ],
            ],
        ],
        'sent' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.sent',
            'config' => [
                'type' => 'check',
                'default' => 0,
                'readOnly' => 1,
            ],
        ],
        'category' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND sys_category.parent = ' . $extensionConfiguration['rootCategoryUid'] . ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.title',
                'items' => [
                    ['', ''],
                ],
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ],
            // hide field if notification mail was already sent
            'displayCond' => 'FIELD:sent:!=:1',
        ],
        'news' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.news',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_news_domain_model_news',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'approving_beuser' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.approving_beuser',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'be_users',
                'foreign_table_where' => ' ORDER BY be_users.username',
                'items' => [
                    ['', ''],
                ],
                'minitems' => 0,
                'maxitems' => 1,
                'readOnly' => 1,
            ],
        ],
        'tstamp' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
