<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_newscatinvite_domain_model_invitation'] = array(
	'ctrl' => $TCA['tx_newscatinvite_domain_model_invitation']['ctrl'],
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
					array('LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.status.1', 1),
					array('LLL:EXT:newscatinvite/Resources/Private/Language/locallang_db.xlf:tx_newscatinvite_domain_model_invitation.status.-1', -1),
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
				'renderMode' => 'tree',
				'treeConfig' => array(
					'rootUid' => 4,
					'parentField' => 'parentcategory',
					'appearance' => array(
						'showHeader' => TRUE,
						'allowRecursiveMode' => TRUE,
						'expandAll' => TRUE,
						'maxLevels' => 99,
					),
				),
				'MM' => 'tx_news_domain_model_news_category_mm',
				'foreign_table' => 'tx_news_domain_model_category',
				'foreign_table_where' => ' AND (tx_news_domain_model_category.sys_language_uid = 0 OR tx_news_domain_model_category.l10n_parent = 0) ORDER BY tx_news_domain_model_category.sorting',
				'size' => 10,
				'autoSizeMax' => 20,
				'minitems' => 1,
				'maxitems' => 1,
			),
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
				'minitems' => 0,
				'maxitems' => 1,
				'readOnly' => 1,
			),
		),
		
	),
);

?>
