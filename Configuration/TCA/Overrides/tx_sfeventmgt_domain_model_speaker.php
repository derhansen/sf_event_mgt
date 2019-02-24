<?php
defined('TYPO3_MODE') or die();

$tableName = 'tx_sfeventmgt_domain_model_speaker';

// Register slug field for TYPO3 9.5
if (\DERHANSEN\SfEventMgt\Utility\MiscUtility::isV9Lts()) {
    $speakerColumns['slug'] = [
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
            'eval' => 'uniqueInSite',
            'default' => ''
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        $tableName,
        $speakerColumns
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        $tableName,
        'slug',
        '',
        'after:name'
    );
}
