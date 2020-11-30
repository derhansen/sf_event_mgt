<?php

defined('TYPO3_MODE') or die();

/**
 * Plugins
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'SfEventMgt',
    'Pievent',
    'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin.title'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'SfEventMgt',
    'Piuserreg',
    'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin_userreg.title'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'SfEventMgt',
    'Pipayment',
    'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin_payment.title'
);

/**
 * Remove unused fields
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['sfeventmgt_pievent'] = 'layout,recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['sfeventmgt_piuserreg'] = 'layout,recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['sfeventmgt_pipayment'] = 'layout,recursive,select_key,pages';

/**
 * Add Flexform for event plugin
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['sfeventmgt_pievent'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'sfeventmgt_pievent',
    'FILE:EXT:sf_event_mgt/Configuration/FlexForms/Flexform_plugin.xml'
);

/**
 * Add Flexform for user registration plugin
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['sfeventmgt_piuserreg'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'sfeventmgt_piuserreg',
    'FILE:EXT:sf_event_mgt/Configuration/FlexForms/Flexform_userreg.xml'
);

/**
 * Register event as "Insert Record"
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_sfeventmgt_domain_model_event');
