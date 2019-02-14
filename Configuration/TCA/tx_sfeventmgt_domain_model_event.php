<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event',
        'label' => 'title',
        'descriptionColumn' => 'rowDescription',
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
        'setToDefaultOnCopy' => 'registration, registration_waitlist',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'requestUpdate' => 'enable_registration, enable_waitlist, enable_cancel, enable_payment, restrict_payment_methods',
        'searchFields' => 'title,description,startdate,enddate,max_participants,price,currency,category,image,registration,location,enable_registration,enable_waitlist,speaker',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-event'
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, rowDescription, teaser, 
        description, program, link, top_event, startdate, enddate, fe_group, enable_registration, enable_waitlist, 
        max_participants, max_registrations_per_user, registration_deadline, price, currency, category, related, image, 
        files, additional_image, registration, location, organisator, notify_admin, notify_organisator, unique_email_check,
        enable_payment,price_options,registration_waitlist, enable_autoconfirm, speaker, registration_fields',
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;;titleTopEvent, --palette--;;paletteDates, teaser, description,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.additional,
                    --palette--;;palettePrice, price_options, link, program,
    
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.relations,
                    location, organisator, speaker, related,
    
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.media,
                    image, files, additional_image,
    
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.category,
                    category,
    
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registration_options,
                    enable_registration, registration_deadline, --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.cancellation;paletteCancellation,
                    max_participants, max_registrations_per_user, enable_autoconfirm, enable_waitlist, unique_email_check,
                    --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.notification;paletteNotification,
    
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registration_fields,
                    registration_fields,
    
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registrations,
                    registration,registration_waitlist,
    
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.payment,
                     enable_payment, restrict_payment_methods, selected_payment_methods,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
                    --palette--;;language,

                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, 
                    hidden, --palette--;;timeRestriction, fe_group,
                    
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription'
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => '',
        ],
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource'],
        'titleTopEvent' => ['showitem' => 'title, top_event'],
        'paletteDates' => [
            'showitem' => 'startdate, enddate,',
            'canNotCollapse' => true
        ],
        'palettePrice' => [
            'showitem' => 'price, currency,',
            'canNotCollapse' => true
        ],
        'paletteNotification' => [
            'showitem' => 'notify_admin, notify_organisator,',
            'canNotCollapse' => true
        ],
        'paletteCancellation' => [
            'showitem' => 'enable_cancel, cancel_deadline,',
            'canNotCollapse' => true
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
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
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_sfeventmgt_domain_model_event',
                'foreign_table_where' => 'AND tx_sfeventmgt_domain_model_event.pid=###CURRENT_PID### AND tx_sfeventmgt_domain_model_event.sys_language_uid IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
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
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
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
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'enableMultiSelectFilterTextfield' => true,
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    [
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hide_at_login',
                        -1,
                    ],
                    [
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.any_login',
                        -2,
                    ],
                    [
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.usergroups',
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
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'title' => [
            'exclude' => 1,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'teaser' => [
            'exclude' => 1,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ]
        ],
        'description' => [
            'exclude' => 1,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ]
        ],
        'program' => [
            'exclude' => 1,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.program',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ]
        ],
        'link' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'top_event' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.top_event',
            'config' => [
                'type' => 'check',
            ],
        ],
        'startdate' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.startdate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time()
            ],
        ],
        'enddate' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enddate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time() + 3600
            ],
        ],
        'enable_registration' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_registration',
            'config' => [
                'type' => 'check',
            ],
        ],
        'enable_waitlist' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_waitlist',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'registration_deadline' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registration_deadline',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1
            ],
        ],
        'enable_cancel' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_cancel',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'cancel_deadline' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.cancel_deadline',
            'displayCond' => 'FIELD:enable_cancel:REQ:TRUE',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1
            ],
        ],
        'enable_autoconfirm' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_autoconfirm',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'max_participants' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.maxParticipants',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'max_registrations_per_user' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.maxRegistrationsPerUser',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'price' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.price',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'double2',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'currency' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.currency',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'enable_payment' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_payment',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'restrict_payment_methods' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.restrict_payment_methods',
            'displayCond' => 'FIELD:enable_payment:REQ:TRUE',
            'config' => [
                'type' => 'check',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'selected_payment_methods' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.selected_payment_methods',
            'displayCond' => 'FIELD:restrict_payment_methods:REQ:TRUE',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'size' => 5,
                'maxitems' => 99,
                'itemsProcFunc' => 'DERHANSEN\SfEventMgt\Hooks\ItemsProcFunc->getPaymentMethods',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'location' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.location',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_location',
                'foreign_table' => 'tx_sfeventmgt_domain_model_location',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                    ],
                ],
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'organisator' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.organisator',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_organisator',
                'foreign_table' => 'tx_sfeventmgt_domain_model_organisator',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                    ],
                ],
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'speaker' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.speaker',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_speaker',
                'foreign_table' => 'tx_sfeventmgt_domain_model_speaker',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_sfeventmgt_event_speaker_mm',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'searchWholePhrase' => true
                        ]
                    ],
                ],
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'image' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'image',
                        'tablenames' => 'tx_sfeventmgt_domain_model_event',
                        'table_local' => 'sys_file',
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                        --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                        --palette--;;filePalette'
                            ],
                        ],
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true,
                    ],
                    'minitems' => 0,
                    'maxitems' => 999,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'files' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.files',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('files', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
                    'showPossibleLocalizationRecords' => true,
                    'showRemovedLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]),
        ],
        'related' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.related',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_event',
                'foreign_table' => 'tx_sfeventmgt_domain_model_event',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_sfeventmgt_domain_model_event_related_mm',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'searchWholePhrase' => true
                        ]
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'additional_image' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.additional_image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'additional_image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'additional_image',
                        'tablenames' => 'tx_sfeventmgt_domain_model_event',
                        'table_local' => 'sys_file',
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                        ],
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true,
                    ],
                    'minitems' => 0,
                    'maxitems' => 999,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'registration' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registrations',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration',
                'foreign_field' => 'event',
                'foreign_match_fields' => [
                    'waitlist' => '0',
                ],
                'maxitems' => 9999,
                'appearance' => [
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => 1,
                ],
            ],
        ],
        'registration_waitlist' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registration_waitlist',
            'displayCond' => 'FIELD:enable_waitlist:REQ:TRUE',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration',
                'foreign_field' => 'event',
                'foreign_match_fields' => [
                    'waitlist' => '1',
                ],
                'maxitems' => 9999,
                'appearance' => [
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
        'registration_fields' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registration_fields',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration_field',
                'foreign_field' => 'event',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'notify_admin' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.notify_admin',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'notify_organisator' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.notify_organisator',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'unique_email_check' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.unique_email_check',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'price_options' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.price_options',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_sfeventmgt_domain_model_priceoption',
                'foreign_field' => 'event',
                'foreign_default_sortby' => 'valid_until DESC',
                'maxitems' => 100,
                'appearance' => [
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 0,
                    'showAllLocalizationLink' => 1
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'rowDescription' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 30
            ]
        ],
    ],
];
