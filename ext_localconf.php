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
		'Event' => 'registration, saveRegistration, saveRegistrationResult',
	)
);
