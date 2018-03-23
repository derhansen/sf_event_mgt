<?php

$lll = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:';

$showItemDefault = 'l10n_parent, l10n_diffsource,  --palette--;;paletteCore, title, type,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
        required, placeholder, default_value,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime, fe_group,';

$showItemRadioCheck = 'l10n_parent, l10n_diffsource,  --palette--;;paletteCore, title, type, settings,
    --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration_field.tabs.settings,
        required, placeholder, default_value,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime, fe_group,';

return [
    'ctrl' => [
        'title' => $lll . 'tx_sfeventmgt_domain_model_registration_field',
        'label' => 'title',
        'type' => 'type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
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
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, type, settings, required',
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
    ],
    'palettes' => [
        'paletteCore' => [
            'showitem' => 'sys_language_uid, hidden,',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration_field',
                'foreign_table_where' => 'AND tx_sfeventmgt_domain_model_registration_field.pid=###CURRENT_PID### AND tx_sfeventmgt_domain_model_registration_field.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'fe_group' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.hide_at_login',
                        -1,
                    ],
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.any_login',
                        -2,
                    ],
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.usergroups',
                        '--div--',
                    ],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
            ],
        ],
        'title' => [
            'exclude' => 1,
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
            ],
        ],
        'required' => [
            'l10n_mode' => 'exclude',
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
            ],
        ],
        'default_value' => [
            'exclude' => 1,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_field.default_value',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 2,
            ],
        ],
        'event' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
