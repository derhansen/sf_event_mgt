<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'SKYFILLERS.' . $_EXTKEY,
	'Pievent',
	array(
		'Event' => 'list, detail, registration'
	),
	// non-cacheable actions
	array(
		'Event' => 'registration, saveRegistration, saveRegistrationResult, confirmRegistration',
	)
);

// DataHandler hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['SKYFILLERS.' . $_EXTKEY] =
	'SKYFILLERS\SfEventMgt\Hooks\DataHandlerHooks';