<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'DERHANSEN.' . $_EXTKEY,
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
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['DERHANSEN.' . $_EXTKEY] =
	'DERHANSEN\SfEventMgt\Hooks\DataHandlerHooks';

// Register cleanup command
if (TYPO3_MODE === 'BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'DERHANSEN\\SfEventMgt\\Command\\CleanupCommandController';
}

// Register longitude- and latitude-evaluator for TCA
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['DERHANSEN\\SfEventMgt\\Evaluation\\LongitudeEvaluator'] = 'EXT:sf_event_mgt/Classes/Evaluation/LongitudeEvaluator.php';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['DERHANSEN\\SfEventMgt\\Evaluation\\LatitudeEvaluator'] = 'EXT:sf_event_mgt/Classes/Evaluation/LatitudeEvaluator.php';
