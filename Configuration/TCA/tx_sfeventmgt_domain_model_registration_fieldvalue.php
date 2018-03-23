<?php

$lll = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $lll . 'tx_sfeventmgt_domain_model_registration_fieldvalue',
        'label' => 'value',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'value',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-registration-field'
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, value, value_type, field',
    ],
    'types' => [
        '0' => [
            'showitem' => 'l10n_parent, l10n_diffsource,  value, value_type, field',
            ]
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
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration_fieldvalue',
                'foreign_table_where' => 'AND tx_sfeventmgt_domain_model_registration_fieldvalue.pid=###CURRENT_PID### AND tx_sfeventmgt_domain_model_registration_fieldvalue.sys_language_uid IN (-1,0)',
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
        'value' => [
            'exclude' => 1,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_fieldvalue.value',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ],
        ],
        'field' => [
            'exclude' => 1,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_fieldvalue.field',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_registration_field',
                'size' => 1,
                'maxitems' => 1,
                'multiple' => 0,
                'default' => 0
            ],
        ],
        'value_type' => [
            'exclude' => 1,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_fieldvalue.valueType',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_fieldvalue.valueType.type0',
                        '0'
                    ],
                    [
                        $lll . 'tx_sfeventmgt_domain_model_registration_fieldvalue.valueType.type1',
                        '1'
                    ],
                ],
            ],
        ],
    ],
];
