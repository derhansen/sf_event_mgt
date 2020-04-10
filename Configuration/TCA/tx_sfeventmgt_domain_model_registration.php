<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration',
        'label' => 'firstname',
        'label_alt' => 'lastname, email',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'typeicon_column' => 'confirmed',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-registration-unconfirmed',
            '0' => 'ext-sfeventmgt-registration-unconfirmed',
            '1' => 'ext-sfeventmgt-registration-confirmed',
        ],
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'firstname,lastname,address,zip,city,phone,email,gender,confirmed,paid,paymentmethod,payment_reference,notes,fe_user,waitlist,',
        'iconfile' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_registration_unconfirmed.svg'
    ],
    'types' => [
        '1' => [
            'showitem' => '
            --palette--;;paletteGenderTitle,
            --palette--;;paletteName,
            company, 
            --palette--;;paletteAddress,
            country, phone, email, date_of_birth, accepttc, notes, 

            --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.additional,
                fe_user, confirmation_until, confirmed, ignore_notifications, amount_of_registrations,
                waitlist, main_registration,

            --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registration_fields,
                field_values, 
            
            --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.payment,
                paid, paymentmethod, payment_reference, 
                
            --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
                --palette--;;language,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                hidden,--palette--;;timeRestriction'
        ],
    ],
    'palettes' => [
        'paletteName' => ['showitem' => 'firstname, lastname,'],
        'paletteAddress' => ['showitem' => 'address, zip, city,'],
        'paletteGenderTitle' => ['showitem' => 'gender, title,'],
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
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration',
                'foreign_table_where' => 'AND tx_sfeventmgt_domain_model_registration.pid=###CURRENT_PID### AND tx_sfeventmgt_domain_model_registration.sys_language_uid IN (-1,0)',
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

        'language' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.language',
            'config' => [
                'type' => 'input',
            ],
        ],
        'firstname' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.firstname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'lastname' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.lastname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'company' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.company',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'address' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'zip' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.zip',
            'config' => [
                'type' => 'input',
                'size' => 4,
            ]
        ],
        'city' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.city',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'country' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.country',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'phone' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.phone',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'email,trim,required'
            ],
        ],
        'ignore_notifications' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.ignoreNotifications',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'gender' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender.notset',
                        ''
                    ],
                    [
                        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender.female',
                        'f'
                    ],
                    [
                        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender.male',
                        'm'
                    ],
                    [
                        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender.various',
                        'v'
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'date_of_birth' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.date_of_birth',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'date',
                'checkbox' => 1
            ],
        ],
        'confirmation_until' => [
            'exclude' => 1,
            'displayCond' => 'FIELD:confirmed:REQ:FALSE',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.confirmationUntil',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time()
            ],
        ],
        'confirmed' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.confirmed',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'accepttc' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.accepttc',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'waitlist' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.waitlist',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'paymentmethod' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.paymentmethod',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.paymentmethod.notSet', '']
                ],
                'itemsProcFunc' => 'DERHANSEN\SfEventMgt\Hooks\ItemsProcFunc->getPaymentMethods',
                'size' => 1,
                'maxitems' => 1,
            ]
        ],
        'payment_reference' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.payment_reference',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'paid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.paid',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'notes' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.notes',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ]
        ],
        'event' => [
            'config' => [
                'type' => 'passthrough',
                'foreign_table' => 'tx_sfeventmgt_domain_model_event',
            ],
        ],
        'amount_of_registrations' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.amountOfRegistrations',
            'displayCond' => 'FIELD:amount_of_registrations:>:1',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'readOnly' => 1
            ]
        ],
        'main_registration' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.mainRegistration',
            'displayCond' => 'FIELD:main_registration:>:0',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_registration',
                'maxitems' => 1,
                'size' => 1,
                'readOnly' => 1
            ],
        ],
        'recaptcha' => [
            'exclude' => 0,
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'field_values' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.fieldvalues',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration_fieldvalue',
                'foreign_field' => 'registration',
                'maxitems' => 9999,
                'appearance' => [
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 0,
                    'showPossibleLocalizationRecords' => 0,
                    'useSortable' => 0,
                    'showAllLocalizationLink' => 0
                ],
            ],
        ],
        'fe_user' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.fe_user',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_users',
                'foreign_table' => 'fe_users',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'name, first_name, last_name',
                    ],
                ],
            ],
        ],
    ],
];
