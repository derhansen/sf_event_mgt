<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pievent',
	'Event Management'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Event management and booking');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_sfeventmgt_domain_model_event', 'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_tx_sfeventmgt_domain_model_event.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_sfeventmgt_domain_model_event');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_sfeventmgt_domain_model_category', 'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_tx_sfeventmgt_domain_model_category.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_sfeventmgt_domain_model_category');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_sfeventmgt_domain_model_booking', 'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_tx_sfeventmgt_domain_model_booking.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_sfeventmgt_domain_model_booking');
