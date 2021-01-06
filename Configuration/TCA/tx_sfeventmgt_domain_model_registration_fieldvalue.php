<?php

$lll = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $lll . 'tx_sfeventmgt_domain_model_registration_fieldvalue',
        'label' => 'value',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'origUid' => 't3_origuid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'value',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-registration-field'
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'value, value_type, field',
            ]
    ],
    'palettes' => [],
    'columns' => [
        'hidden' => [
            'exclude' => true,
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
        'value' => [
            'exclude' => true,
            'label' => $lll . 'tx_sfeventmgt_domain_model_registration_fieldvalue.value',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ],
        ],
        'field' => [
            'exclude' => true,
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
            'exclude' => true,
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
