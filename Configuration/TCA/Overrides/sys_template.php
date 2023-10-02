<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Default TypoScript
 */
ExtensionManagementUtility::addStaticFile(
    'sf_event_mgt',
    'Configuration/TypoScript',
    'Event management and registration'
);
