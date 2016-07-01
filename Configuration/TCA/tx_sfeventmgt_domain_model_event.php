<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'sortby' => 'sorting',
        'versioningWS' => 2,
        'versioning_followPages' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'requestUpdate' => 'enable_registration, enable_cancel, enable_payment, restrict_payment_methods',
        'searchFields' => 'title,description,startdate,enddate,max_participants,price,currency,category,image,registration,location,enable_registration',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('sf_event_mgt') . 'Resources/Public/Icons/tx_sfeventmgt_domain_model_event.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, teaser, description, program, link, top_event, startdate, enddate, enable_registration, max_participants, max_registrations_per_user, registration_deadline, price, currency, category, image, files, youtube, additional_image, registration, location, organisator, notify_admin, notify_organisator, unique_email_check, enable_payment,price_options',
    ],
    'types' => [
        '1' => [
            'showitem' => 'l10n_parent, l10n_diffsource,
			--palette--;;paletteCore, title,--palette--;;paletteDates, teaser,
			description;;;richtext:rte_transform[mode=ts_links],

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.additional,
				--palette--;;palettePrice, price_options, location, organisator, link, program;;;richtext:rte_transform[mode=ts_links],

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.media,
				image, files, youtube,additional_image,

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.category,
				category,

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registration_options,
				enable_registration, registration_deadline, --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.cancellation;paletteCancellation, max_participants, max_registrations_per_user, unique_email_check, --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.notification;paletteNotification,

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registrations,
				registration,

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.payment,
                 enable_payment, restrict_payment_methods, selected_payment_methods,

			--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => '',
        ],
        'paletteCore' => [
            'showitem' => 'sys_language_uid, top_event, hidden,',
            'canNotCollapse' => true
        ],
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
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]
                ],
            ],
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
                'foreign_table' => 'tx_sfeventmgt_domain_model_event',
                'foreign_table_where' => 'AND tx_sfeventmgt_domain_model_event.pid=###CURRENT_PID### AND tx_sfeventmgt_domain_model_event.sys_language_uid IN (-1,0)',
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
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'teaser' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ]
        ],
        'description' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => [
                    'RTE' => [
                        'icon' => 'wizard_rte2.gif',
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'module' => [
                            'name' => 'wizard_rte',
                        ],
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    ]
                ]
            ],
        ],
        'program' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.program',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => [
                    'RTE' => [
                        'icon' => 'wizard_rte2.gif',
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'module' => [
                            'name' => 'wizard_rte',
                        ],
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    ]
                ]
            ],
        ],
        'link' => [
            'exclude' => 0,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.link',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'link_popup.gif',
                        'module' => [
                            'name' => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard'
                            ]
                        ],
                        'JSopenParams' => 'height=600,width=800,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'top_event' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.top_event',
            'config' => [
                'type' => 'check',
            ],
        ],
        'startdate' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.startdate',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time()
            ],
        ],
        'enddate' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enddate',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time()
            ],
        ],
        'enable_registration' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_registration',
            'config' => [
                'type' => 'check',
            ],
        ],
        'registration_deadline' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registration_deadline',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1
            ],
        ],
        'enable_cancel' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_cancel',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'cancel_deadline' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.cancel_deadline',
            'displayCond' => 'FIELD:enable_cancel:REQ:TRUE',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1
            ],
        ],
        'max_participants' => [
            'exclude' => 1,
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
                'eval' => 'double2'
            ]
        ],
        'currency' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.currency',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ],
        ],
        'enable_payment' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_payment',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'restrict_payment_methods' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.restrict_payment_methods',
            'displayCond' => 'FIELD:enable_payment:REQ:TRUE',
            'config' => [
                'type' => 'check',
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
            ]
        ],
        'location' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.location',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_location',
                'foreign_table' => 'tx_sfeventmgt_domain_model_location',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                    ],
                ],
            ],
        ],
        'organisator' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.organisator',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_organisator',
                'foreign_table' => 'tx_sfeventmgt_domain_model_organisator',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                    ],
                ],
            ],
        ],
        'image' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                ],
                'minitems' => 0,
                'maxitems' => 999,
            ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
        ],
        'files' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.files',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('files', [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:media.addFileReference'
                    ],
                ]
            ),
        ],
        'youtube' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.youtube',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 3,
            ]
        ],
        'additional_image' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.additional_image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('additional_image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'minitems' => 0,
                    'maxitems' => 999,
                ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
        ],
        'registration' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registrations',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration',
                'foreign_field' => 'event',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
        'notify_admin' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.notify_admin',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'notify_organisator' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.notify_organisator',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'unique_email_check' => [
            'exclude' => 1,
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
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 0,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],

    ],
];
