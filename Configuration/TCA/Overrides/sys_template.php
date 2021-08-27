<?php

defined('TYPO3') or die();

/**
 * Default TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'sf_event_mgt',
    'Configuration/TypoScript',
    'Event management and registration'
);
