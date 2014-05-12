<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
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
		'user',
		'invitations',
		'top',
		array(
			'Invitation' => 'list, listArchive, approve, reject',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_invitations.xlf',
		)
	);

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'News category invitation');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newscatinvite_domain_model_invitation', 'EXT:newscatinvite/Resources/Private/Language/locallang_csh_tx_newscatinvite_domain_model_invitation.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newscatinvite_domain_model_invitation');
$TCA['tx_newscatinvite_domain_model_invitation'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation',
		'label' => 'category',
		'label_alt' => 'status',
		'label_alt_force' => 1,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'status,sent,category,news,approving_beuser,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Invitation.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_newscatinvite_domain_model_invitation.gif'
	),
);

$tmp_newscatinvite_columns = array(
	'tx_newscatinvite_invitations' => array(
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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $tmp_newscatinvite_columns);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'tx_news_domain_model_news',
	'tx_newscatinvite_invitations',
	'',
	'after:categories'
);

?>
