<?php
defined('TYPO3_MODE') or die();

/**
 * Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'sf_event_mgt',
    'Pievent',
    'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin.title'
);

/**
 * Remove unused fields
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['sfeventmgt_pievent'] = 'layout,recursive,select_key,pages';

/**
 * Add Flexform
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['sfeventmgt_pievent'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'sfeventmgt_pievent',
    'FILE:EXT:sf_event_mgt/Configuration/FlexForms/Flexform_plugin.xml'
);

/**
 * Default TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'sf_event_mgt',
    'Configuration/TypoScript',
    'Event management and registration'
);

