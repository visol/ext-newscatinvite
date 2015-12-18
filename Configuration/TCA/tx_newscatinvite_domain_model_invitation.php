<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newscatinvite']);

$GLOBALS['TCA']['tx_newscatinvite_domain_model_invitation'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation',
        'label' => 'category',
        'label_alt' => 'status',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        // Removed versioning, translations, deleteClause and enableColumns to make Extbase constraint for invitations working
        //'versioningWS' => 2,
        //'versioning_followPages' => TRUE,
        //'origUid' => 't3_origuid',
        //'languageField' => 'sys_language_uid',
        //'transOrigPointerField' => 'l10n_parent',
        //'transOrigDiffSourceField' => 'l10n_diffsource',
        //'delete' => 'deleted',
        //'enablecolumns' => array(
        //	'disabled' => 'hidden',
        //	'starttime' => 'starttime',
        //	'endtime' => 'endtime',
        //),
        'searchFields' => 'status,sent,category,news,approving_beuser,',
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('newscatinvite') . 'Configuration/TCA/Invitation.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('newscatinvite') . 'Resources/Public/Icons/tx_newscatinvite_domain_model_invitation.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'status, sent, category, news, approving_beuser',
    ),
    'types' => array(
        '1' => array('showitem' => 'status, sent, category, news, approving_beuser'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'status' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.status',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('', 0),
                    array(
                        'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.status.1',
                        1
                    ),
                    array(
                        'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.status.-1',
                        -1
                    ),
                ),
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ),
        ),
        'sent' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.sent',
            'config' => array(
                'type' => 'check',
                'default' => 0,
                'readOnly' => 1,
            ),
        ),
        'category' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.category',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND sys_category.parent = ' . $extensionConfiguration['rootCategoryUid'] . ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.title',
                'items' => array(
                    array('', ''),
                ),
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ),
            // hide field if notification mail was already sent
            'displayCond' => 'FIELD:sent:!=:1',
        ),
        'news' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.news',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'tx_news_domain_model_news',
                'minitems' => 1,
                'maxitems' => 1,
            ),
        ),
        'approving_beuser' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.approving_beuser',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'be_users',
                'foreign_table_where' => ' ORDER BY be_users.username',
                'items' => array(
                    array('', ''),
                ),
                'minitems' => 0,
                'maxitems' => 1,
                'readOnly' => 1,
            ),
        ),
        'tstamp' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
    ),
);

