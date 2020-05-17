<?php
defined('TYPO3_MODE') or die();

$tableName = 'tx_sfeventmgt_domain_model_organisator';

// Register slug field for TYPO3 9.5
if (\DERHANSEN\SfEventMgt\Utility\MiscUtility::isV9Lts()) {
    $slugBehaviour = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
        ->get('sf_event_mgt', 'slugBehaviour');

    $organisatorColumns['slug'] = [
        'exclude' => true,
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
        'config' => [
            'type' => 'slug',
            'size' => 50,
            'generatorOptions' => [
                'fields' => ['name'],
                'replacements' => [
                    '/' => '-'
                ],
            ],
            'fallbackCharacter' => '-',
            'eval' => $slugBehaviour,
            'default' => ''
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        $tableName,
        $organisatorColumns
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        $tableName,
        'slug',
        '',
        'after:name'
    );
}
