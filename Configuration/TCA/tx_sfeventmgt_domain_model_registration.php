<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration',
        'label' => 'firstname',
        'label_alt' => 'lastname, email',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'typeicon_column' => 'confirmed',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-registration-unconfirmed',
            '0' => 'ext-sfeventmgt-registration-unconfirmed',
            '1' => 'ext-sfeventmgt-registration-confirmed',
        ],
        'origUid' => 't3_origuid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'firstname,lastname,address,zip,city,phone,email,gender,confirmed,paid,paymentmethod,payment_reference,notes,fe_user,waitlist,',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
            --palette--;;paletteGenderTitle,
            --palette--;;paletteName,
            company,
            --palette--;;paletteAddress,
            country, phone, email, date_of_birth, accepttc, notes, registration_date,

            --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.additional,
                fe_user, confirmation_until, confirmed, ignore_notifications, amount_of_registrations,
                waitlist, main_registration, language,

            --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registration_fields,
                field_values,

            --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:registration.tabs.payment,
                paid, price, price_option, paymentmethod, payment_reference,

            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                hidden',
        ],
    ],
    'palettes' => [
        'paletteName' => ['showitem' => 'firstname, lastname,'],
        'paletteAddress' => ['showitem' => 'address, zip, city,'],
        'paletteGenderTitle' => ['showitem' => 'gender, title,'],
    ],
    'columns' => [
        'language' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.language',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'readOnly' => true,
            ],
        ],
        'firstname' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.firstname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'lastname' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.lastname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'company' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.company',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'address' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'zip' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.zip',
            'config' => [
                'type' => 'input',
                'size' => 4,
            ],
        ],
        'city' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.city',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'country' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.country',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'phone' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.phone',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.email',
            'config' => [
                'type' => 'email',
                'size' => 30,
                'required' => true,
            ],
        ],
        'ignore_notifications' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.ignoreNotifications',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'gender' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender.notset',
                        'value' => '',
                    ],
                    [
                        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender.female',
                        'value' => 'f',
                    ],
                    [
                        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender.male',
                        'value' => 'm',
                    ],
                    [
                        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.gender.various',
                        'value' => 'v',
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'date_of_birth' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.date_of_birth',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
                'default' => 0,
            ],
        ],
        'confirmation_until' => [
            'exclude' => true,
            'displayCond' => 'FIELD:confirmed:REQ:FALSE',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.confirmationUntil',
            'config' => [
                'type' => 'datetime',
                'default' => time(),
            ],
        ],
        'confirmed' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.confirmed',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'accepttc' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.accepttc',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'waitlist' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.waitlist',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'paymentmethod' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.paymentmethod',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.paymentmethod.notSet',
                        'value' => '',
                    ],
                ],
                'itemsProcFunc' => 'DERHANSEN\SfEventMgt\Hooks\ItemsProcFunc->getPaymentMethods',
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'payment_reference' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.payment_reference',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'paid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.paid',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'price' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.price',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 5,
            ],
        ],
        'price_option' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.priceOption',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_sfeventmgt_domain_model_priceoption',
                'foreign_table' => 'tx_sfeventmgt_domain_model_priceoption',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
            ],
        ],
        'notes' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.notes',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ],
        ],
        'registration_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.registration_date',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'amount_of_registrations' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.amountOfRegistrations',
            'displayCond' => 'FIELD:amount_of_registrations:>:1',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'readOnly' => 1,
            ],
        ],
        'main_registration' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.mainRegistration',
            'displayCond' => 'FIELD:main_registration:>:0',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_sfeventmgt_domain_model_registration',
                'maxitems' => 1,
                'size' => 1,
                'readOnly' => 1,
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
            'exclude' => true,
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
                    'showAllLocalizationLink' => 0,
                ],
            ],
        ],
        'fe_user' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_registration.fe_user',
            'config' => [
                'type' => 'group',
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
        'event' => [
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_sfeventmgt_domain_model_event',
                'foreign_table' => 'tx_sfeventmgt_domain_model_event',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'temp_event_uid' => [
            'exclude' => true,
            'label' => 'Holds temporarily the event UID of an event copy/paste/localize process in TYPO3 backend.',
            'config' => [
                'type' => 'number',
            ],
        ],
    ],
];
