<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Invitations',
	array(
		'Invitation' => 'list, show, new, create, edit, update, remove',
		
	),
	// non-cacheable actions
	array(
		'Invitation' => 'create, update, remove',
		
	)
);

?>