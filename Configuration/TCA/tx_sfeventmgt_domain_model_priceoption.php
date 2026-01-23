<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$rteImageSoftref = ExtensionManagementUtility::isLoaded('rte_ckeditor_image') ? ',rtehtmlarea_images' : '';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_priceoption',
        'label' => 'title',
        'label_alt' => 'price, valid_until',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'hideTable' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'searchFields' => 'price,',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-priceoption',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => 'title, --palette--;;palettePrice, valid_until,
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.additional,
                    description,
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
                    --palette--;;language,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                    hidden, --palette--;;timeRestriction, fe_group',
        ],
    ],
    'palettes' => [
        'palettePrice' => ['showitem' => 'price, tax_rate'],
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource'],
    ],
    'columns' => [
        'title' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_priceoption.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'description' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_priceoption.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'softref' => 'typolink_tag,email[subst],url' . $rteImageSoftref,
            ],
        ],
        'price' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_priceoption.price',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 5,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'tax_rate' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_priceoption.tax_rate',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 5,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'valid_until' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_priceoption.valid_until',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'event' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
