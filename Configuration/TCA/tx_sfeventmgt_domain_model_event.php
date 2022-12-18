<?php

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$slugBehaviour = GeneralUtility::makeInstance(ExtensionConfiguration::class)
    ->get('sf_event_mgt', 'slugBehaviour');

$rteImageSoftref = ExtensionManagementUtility::isLoaded('rte_ckeditor_image') ? ',rtehtmlarea_images' : '';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event',
        'label' => 'title',
        'descriptionColumn' => 'rowDescription',
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
        'searchFields' => 'title,description,startdate,enddate,max_participants,price,currency,category,image,registration,location,enable_registration,enable_waitlist,speaker,meta_keywords,meta_description',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-event',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;;titleTopEvent, slug, --palette--;;paletteDates, teaser, description,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.additional,
                    --palette--;;palettePrice, price_options, link, program, custom_text,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.relations,
                    --palette--;;location, organisator, speaker, related,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.media,
                    image, files, additional_image,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.categories,
                    category,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registration_options,
                    enable_registration,
                    --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.registrationPeriod;paletteRegistrationPeriod,
                    --palette--;;participantOptions,
                    --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.cancellation;paletteCancellation,
                    --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.waitlist;paletteWaitlist,
                    --palette--;;miscOptions,
                    --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.notification;paletteNotification,
                    --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.payment;palettePayment,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registration_fields,
                    registration_fields,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registrations,
                    registration,registration_waitlist,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.metadata,
                    --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.metatags;paletteMetatags, alternative_title,

                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
                    --palette--;;language,

                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                    hidden, --palette--;;timeRestriction, fe_group,

                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription',
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => '',
        ],
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource'],
        'location' => [
            'showitem' => 'location, --linebreak--,room',
        ],
        'titleTopEvent' => ['showitem' => 'title, top_event'],
        'paletteDates' => [
            'showitem' => 'startdate, enddate',
        ],
        'palettePrice' => [
            'showitem' => 'price, currency',
        ],
        'paletteNotification' => [
            'showitem' => 'notify_admin, notify_organisator',
        ],
        'paletteRegistrationPeriod' => [
            'showitem' => 'registration_startdate, registration_deadline',
        ],
        'paletteCancellation' => [
            'showitem' => 'enable_cancel, cancel_deadline',
        ],
        'paletteWaitlist' => [
            'showitem' => 'enable_waitlist, enable_waitlist_moveup',
        ],
        'participantOptions' => [
            'showitem' => 'max_participants, max_registrations_per_user',
        ],
        'miscOptions' => [
            'showitem' => 'enable_autoconfirm, unique_email_check',
        ],
        'palettePayment' => [
            'showitem' => 'enable_payment, restrict_payment_methods, --linebreak--, selected_payment_methods',
        ],
        'paletteMetatags' => [
            'showitem' => 'meta_keywords, meta_description',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
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
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'fe_group' => [
            'exclude' => true,
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
        'category' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.category',
            'config' => [
                'type' => 'category',
            ],
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'title' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
            'displayCond' => 'VERSION:IS:false',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title'],
                    'replacements' => [
                        '/' => '-',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => $slugBehaviour,
                'default' => '',
            ],
        ],
        'teaser' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ],
        ],
        'description' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'softref' => 'typolink_tag,email[subst],url' . $rteImageSoftref,
            ],
        ],
        'program' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.program',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'softref' => 'typolink_tag,email[subst],url' . $rteImageSoftref,
            ],
        ],
        'custom_text' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.custom_text',
            'description' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.custom_text.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'softref' => 'typolink_tag,email[subst],url' . $rteImageSoftref,
            ],
        ],
        'link' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.link',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['page', 'url', 'record', 'email', 'file', 'telephone', 'record'],
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'top_event' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.top_event',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'startdate' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.startdate',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'required' => true,
            ],
        ],
        'enddate' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enddate',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'enable_registration' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_registration',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'enable_waitlist' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_waitlist',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'enable_waitlist_moveup' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_waitlist_moveup',
            'displayCond' => 'FIELD:enable_cancel:REQ:TRUE',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'registration_startdate' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registration_startdate',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'registration_deadline' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registration_deadline',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'enable_cancel' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_cancel',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'cancel_deadline' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.cancel_deadline',
            'displayCond' => 'FIELD:enable_cancel:REQ:TRUE',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'enable_autoconfirm' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_autoconfirm',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'max_participants' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.maxParticipants',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'number',
                'size' => 4,
            ],
        ],
        'max_registrations_per_user' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.maxRegistrationsPerUser',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'number',
                'size' => 4,
            ],
        ],
        'price' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.price',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 5,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'currency' => [
            'exclude' => true,
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
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_payment',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'restrict_payment_methods' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.restrict_payment_methods',
            'displayCond' => 'FIELD:enable_payment:REQ:TRUE',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'selected_payment_methods' => [
            'exclude' => true,
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
            ],
        ],
        'location' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.location',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_sfeventmgt_domain_model_location',
                'foreign_table' => 'tx_sfeventmgt_domain_model_location',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'room' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.room',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'organisator' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.organisator',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_sfeventmgt_domain_model_organisator',
                'foreign_table' => 'tx_sfeventmgt_domain_model_organisator',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'speaker' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.speaker',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_sfeventmgt_domain_model_speaker',
                'foreign_table' => 'tx_sfeventmgt_domain_model_speaker',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'default' => 0,
                'MM' => 'tx_sfeventmgt_event_speaker_mm',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.image',
            'config' => [
                'type' => 'file',
                'maxitems' => 999,
                'allowed' => 'common-image-types',
            ],
        ],
        'files' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.files',
            'config' => [
                'type' => 'file',
            ],
        ],
        'related' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.related',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_sfeventmgt_domain_model_event',
                'foreign_table' => 'tx_sfeventmgt_domain_model_event',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_sfeventmgt_domain_model_event_related_mm',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'additional_image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.additional_image',
            'config' => [
                'type' => 'file',
                'maxitems' => 999,
                'allowed' => 'common-image-types',
            ],
        ],
        'registration' => [
            'exclude' => true,
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
            'exclude' => true,
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
                    'useSortable' => 1,
                ],
            ],
        ],
        'registration_fields' => [
            'exclude' => true,
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
                    'showAllLocalizationLink' => 1,
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'notify_admin' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.notify_admin',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'notify_organisator' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.notify_organisator',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
            ],
        ],
        'unique_email_check' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.unique_email_check',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'price_options' => [
            'exclude' => true,
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
                    'showAllLocalizationLink' => 1,
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'meta_keywords' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.meta_keywords',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ],
        ],
        'meta_description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.meta_description',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ],
        ],
        'alternative_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.alternative_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'rowDescription' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            ],
        ],
    ],
];
