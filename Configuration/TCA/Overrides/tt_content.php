<?php

defined('TYPO3') or die();

/**
 * Add new select group for list_type
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content',
    'list_type',
    'sf_event_mgt',
    'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:CType.div.plugingroup',
    'after:default'
);

/**
 * Register all plugins with flexform settings and previewRenderer
 */
$plugins = [
    'Pieventlist' => [
        'flexForm' => 'Pieventlist.xml',
        'previewRenderer' => \DERHANSEN\SfEventMgt\Preview\PieventPreviewRenderer::class,
    ],
    'Pieventdetail' => [
        'flexForm' => 'Pieventdetail.xml',
        'previewRenderer' => \DERHANSEN\SfEventMgt\Preview\PieventPreviewRenderer::class,
    ],
    'Pieventregistration' => [
        'flexForm' => 'Pieventregistration.xml',
        'previewRenderer' => \DERHANSEN\SfEventMgt\Preview\PieventPreviewRenderer::class,
    ],
    'Pieventsearch' => [
        'flexForm' => 'Pieventsearch.xml',
        'previewRenderer' => \DERHANSEN\SfEventMgt\Preview\PieventPreviewRenderer::class,
    ],
    'Pieventcalendar' => [
        'flexForm' => 'Pieventcalendar.xml',
        'previewRenderer' => \DERHANSEN\SfEventMgt\Preview\PieventPreviewRenderer::class,
    ],
    'Pipayment' => [
        'flexForm' => null,
        'previewRenderer' => \DERHANSEN\SfEventMgt\Preview\PipaymentPreviewRenderer::class,
    ],
    'Piuserreg' => [
        'flexForm' => 'Piuserreg.xml',
        'previewRenderer' => \DERHANSEN\SfEventMgt\Preview\PiuserregPreviewRenderer::class,
    ],
];

foreach ($plugins as $pluginName => $pluginConfig) {
    $signature = 'sfeventmgt_' . strtolower($pluginName);

    // Register plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'SfEventMgt',
        $pluginName,
        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin.' . strtolower($pluginName) . '.title',
        'ext-sfeventmgt-default',
        'sf_event_mgt'
    );

    // Remove unused fields
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$signature] = 'layout,recursive,select_key,pages';

    // Register flexform if required
    if (($pluginConfig['flexForm'] ?? null)) {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$signature] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $signature,
            'FILE:EXT:sf_event_mgt/Configuration/FlexForms/' . $pluginConfig['flexForm']
        );
    }

    if (isset($pluginConfig['previewRenderer'])) {
        $GLOBALS['TCA']['tt_content']['types']['list']['previewRenderer'][$signature] = $pluginConfig['previewRenderer'];
    }
}

/**
 * Register event as "Insert Record"
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_sfeventmgt_domain_model_event');
