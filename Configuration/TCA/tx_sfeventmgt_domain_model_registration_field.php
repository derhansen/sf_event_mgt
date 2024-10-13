<?php

use DERHANSEN\SfEventMgt\Utility\FieldType;

$lll = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:';

$showItemDefault = 'title, type,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
        required, placeholder, default_value, feuser_value,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

$showItemRadioCheck = 'title, type, settings,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
        required, placeholder, default_value, feuser_value,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

$showItemText = 'title, type, text,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

$showItemDivider = 'title, type,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

$showItemDateTime = 'title, type, datepickermode,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
        required, default_value, feuser_value,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

return [
    'ctrl' => [
        'title' => $lll . 'tx_sfeventmgt_domain_model_registration_field',
        'label' => 'title',
        'type' => 'type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => true,
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
        'searchFields' => 'title, type',
        'typeicon_column' => 'type',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-registration-field',
        ],
        'hideTable' => true,
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => $showItemDefault,
        ],
        'input' => [
            'showitem' => $showItemDefault,
        ],
        'radio' => [
            'showitem' => $showItemRadioCheck,
        ],
        'check' => [
            'showitem' => $showItemRadioCheck,
        ],
        'textarea' => [
            'showitem' => $showItemDefault,
        ],
        'text' => [
            'showitem' => $showItemText,
        ],
        'divider' => [
            'showitem' => $showItemDivider,
        ],
        'select' => [
            'showitem' => $showItemRadioCheck,
        ],
        'datetime' => [
            'showitem' => $showItemDateTime,
        ],
    ],
    'palettes' => [
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource'],
    ],
    'columns' => [
        'title' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'max' => 255,
                'required' => true,
            ],
        ],
        'type' => [
            'exclude' => 0,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type.0',
                        'value' => FieldType::INPUT,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type.1',
                        'value' => FieldType::RADIO,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type.2',
                        'value' => FieldType::CHECK,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type.3',
                        'value' => FieldType::TEXTAREA,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type.4',
                        'value' => FieldType::TEXT,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type.5',
                        'value' => FieldType::DIVIDER,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type.6',
                        'value' => FieldType::SELECT,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.type.7',
                        'value' => FieldType::DATETIME,
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'required' => true,
            ],
        ],
        'settings' => [
            'exclude' => true,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.settings',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
                'required' => true,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'required' => [
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'exclude' => true,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.required',
            'config' => [
                'type' => 'check',
            ],
        ],
        'placeholder' => [
            'exclude' => true,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.placeholder',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 2,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'default_value' => [
            'exclude' => true,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.default_value',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 2,
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
        'text'  => [
            'exclude' => true,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.text',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'enableRichtext' => true,
            ],
        ],
        'datepickermode'  => [
            'exclude' => true,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.datepickermode',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.datepickermode.mode.0',
                        'value' => 0,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.datepickermode.mode.1',
                        'value' => 1,
                    ],
                    [
                        'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.datepickermode.mode.2',
                        'value' => 2,
                    ],
                ],
            ],
        ],
        'feuser_value' => [
            'exclude' => true,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.feuser_value',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration_field.feuser_value.select',
                        'value' => '',
                    ],
                ],
                'itemsProcFunc' => 'DERHANSEN\SfEventMgt\Hooks\ItemsProcFunc->getFeuserValues',
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
    ],
];
