<?php

$lll = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:';

$showItemDefault = 'title, type,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
        required, placeholder, default_value,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

$showItemRadioCheck = 'title, type, settings,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
        required, placeholder, default_value,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

$showItemText = 'title, type, text,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

$showItemDivider = 'title, type,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
        --palette--;;language,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden, --palette--;;timeRestriction, fe_group';

$showItemDateTime = 'title, type, datepickermode, 
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
        required, default_value,
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
        'cruser_id' => 'cruser_id',
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
            'default' => 'ext-sfeventmgt-registration-field'
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => $showItemDefault
        ],
        'input' => [
            'showitem' => $showItemDefault
        ],
        'radio' => [
            'showitem' => $showItemRadioCheck
        ],
        'check' => [
            'showitem' => $showItemRadioCheck
        ],
        'textarea' => [
            'showitem' => $showItemDefault
        ],
        'text' => [
            'showitem' => $showItemText
        ],
        'divider' => [
            'showitem' => $showItemDivider
        ],
        'select' => [
            'showitem' => $showItemRadioCheck
        ],
        'datetime' => [
            'showitem' => $showItemDateTime
        ],
    ],
    'palettes' => [
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration_field',
                'foreign_table_where' => 'AND tx_sfeventmgt_domain_model_registration_field.pid=###CURRENT_PID### AND tx_sfeventmgt_domain_model_registration_field.sys_language_uid IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'fe_group' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hide_at_login',
                        -1,
                    ],
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.any_login',
                        -2,
                    ],
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.usergroups',
                        '--div--',
                    ],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'title' => [
            'exclude' => 1,
            'l10n_mode' => 'prefixLangTitle',
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'max' => 255,
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
                        $lll . 'tx_sfeventmgt_domain_model_registration_field.type.0',
                        \DERHANSEN\SfEventMgt\Utility\FieldType::INPUT
                    ],
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_field.type.1',
                        \DERHANSEN\SfEventMgt\Utility\FieldType::RADIO
                    ],
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_field.type.2',
                        \DERHANSEN\SfEventMgt\Utility\FieldType::CHECK
                    ],
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_field.type.3',
                        \DERHANSEN\SfEventMgt\Utility\FieldType::TEXTAREA
                    ],
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_field.type.4',
                        \DERHANSEN\SfEventMgt\Utility\FieldType::TEXT
                    ],
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_field.type.5',
                        \DERHANSEN\SfEventMgt\Utility\FieldType::DIVIDER
                    ],
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_field.type.6',
                        \DERHANSEN\SfEventMgt\Utility\FieldType::SELECT
                    ],
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_field.type.7',
                        \DERHANSEN\SfEventMgt\Utility\FieldType::DATETIME
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
        ],
        'settings' => [
            'exclude' => 1,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.settings',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
                'eval' => 'required',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'required' => [
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'exclude' => 1,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.required',
            'config' => [
                'type' => 'check'
            ],
        ],
        'placeholder' => [
            'exclude' => 1,
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
            'exclude' => 1,
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
            'exclude' => 1,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.text',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'enableRichtext' => true,
            ]
        ],
        'datepickermode'  => [
            'exclude' => 1,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.datepickermode',
            'config' => [
                'type' => 'select',
                'items' => [
                    [$lll . 'tx_sfeventmgt_domain_model_registration_field.datepickermode.mode.0', 0],
                    [$lll . 'tx_sfeventmgt_domain_model_registration_field.datepickermode.mode.1', 1],
                    [$lll . 'tx_sfeventmgt_domain_model_registration_field.datepickermode.mode.2', 2],
                ]
            ]
        ],
    ],
];
