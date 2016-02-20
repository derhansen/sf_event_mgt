<?php

return array(
    'ctrl' => array(
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
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'requestUpdate' => 'enable_registration, enable_cancel',
        'searchFields' => 'title,description,startdate,enddate,max_participants,price,currency,category,image,registration,location,enable_registration',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('sf_event_mgt') . 'Resources/Public/Icons/tx_sfeventmgt_domain_model_event.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, teaser, description, program, link, top_event, startdate, enddate, enable_registration, max_participants, max_registrations_per_user, registration_deadline, price, currency, category, image, files, youtube, additional_image, registration, location, organisator, notify_admin, notify_organisator, unique_email_check',
    ),
    'types' => array(
        '1' => array(
            'showitem' => 'l10n_parent, l10n_diffsource,
			--palette--;;paletteCore, title,--palette--;;paletteDates, teaser,
			description;;;richtext:rte_transform[mode=ts_links],

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.additional,
				--palette--;;palettePrice, location, organisator, link, program;;;richtext:rte_transform[mode=ts_links],

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.media,
				image, files, youtube,additional_image,

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.category,
				category,

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registration_options,
				enable_registration, registration_deadline, --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.cancellation;paletteCancellation, max_participants, max_registrations_per_user, unique_email_check, --palette--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.sections.notification;paletteNotification,

			--div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.registrations,
				registration,

			--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'
        ),
    ),
    'palettes' => array(
        '1' => array(
            'showitem' => '',
        ),
        'paletteCore' => array(
            'showitem' => 'sys_language_uid, top_event, hidden,',
            'canNotCollapse' => true
        ),
        'paletteDates' => array(
            'showitem' => 'startdate, enddate,',
            'canNotCollapse' => true
        ),
        'palettePrice' => array(
            'showitem' => 'price, currency,',
            'canNotCollapse' => true
        ),
        'paletteNotification' => array(
            'showitem' => 'notify_admin, notify_organisator,',
            'canNotCollapse' => true
        ),
        'paletteCancellation' => array(
            'showitem' => 'enable_cancel, cancel_deadline,',
            'canNotCollapse' => true
        ),
    ),
    'columns' => array(
        'sys_language_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => array(
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
                ),
            ),
        ),
        'l10n_parent' => array(
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('', 0),
                ),
                'foreign_table' => 'tx_sfeventmgt_domain_model_event',
                'foreign_table_where' => 'AND tx_sfeventmgt_domain_model_event.pid=###CURRENT_PID### AND tx_sfeventmgt_domain_model_event.sys_language_uid IN (-1,0)',
            ),
        ),
        'l10n_diffsource' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        't3ver_label' => array(
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            )
        ),
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'starttime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'endtime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'title' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.title',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ),
        ),
        'teaser' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.teaser',
            'config' => array(
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            )
        ),
        'description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.description',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'wizard_rte2.gif',
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte',
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
        ),
        'program' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.program',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'wizard_rte2.gif',
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte',
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
        ),
        'link' => array(
            'exclude' => 0,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.link',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'link_popup.gif',
                        'module' => array(
                            'name' => 'wizard_element_browser',
                            'urlParameters' => array(
                                'mode' => 'wizard'
                            )
                        ),
                        'JSopenParams' => 'height=600,width=800,status=0,menubar=0,scrollbars=1'
                    )
                )
            )
        ),
        'top_event' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.top_event',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'startdate' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.startdate',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time()
            ),
        ),
        'enddate' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enddate',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time()
            ),
        ),
        'enable_registration' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_registration',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'registration_deadline' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registration_deadline',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1
            ),
        ),
        'enable_cancel' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.enable_cancel',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'cancel_deadline' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.cancel_deadline',
            'displayCond' => 'FIELD:enable_cancel:REQ:TRUE',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 1
            ),
        ),
        'max_participants' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.maxParticipants',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => array(
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            )
        ),
        'max_registrations_per_user' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.maxRegistrationsPerUser',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => array(
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            )
        ),
        'price' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.price',
            'config' => array(
                'type' => 'input',
                'size' => 5,
                'eval' => 'double2'
            )
        ),
        'currency' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.currency',
            'config' => array(
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ),
        ),
        'location' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.location',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_location',
                'foreign_table' => 'tx_sfeventmgt_domain_model_location',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                ),
            ),
        ),
        'organisator' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.organisator',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_organisator',
                'foreign_table' => 'tx_sfeventmgt_domain_model_organisator',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                ),
            ),
        ),
        'category' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.category',
            'config' => array(
                'type' => 'select',
                'renderMode' => 'tree',
                'treeConfig' => array(
                    'parentField' => 'parent',
                    'appearance' => array(
                        'expandAll' => true,
                        'showHeader' => true,
                    ),
                ),
                'MM' => 'tx_sfeventmgt_event_category_mm',
                'foreign_table' => 'tx_sfeventmgt_domain_model_category',
                'foreign_table_where' => ' AND (tx_sfeventmgt_domain_model_category.sys_language_uid = 0 OR tx_sfeventmgt_domain_model_category.l10n_parent = 0) ORDER BY tx_sfeventmgt_domain_model_category.sorting ASC',
                'minitems' => 0,
                'maxitems' => 99,
            ),
        ),
        'image' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image', array(
                'appearance' => array(
                    'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                ),
                'minitems' => 0,
                'maxitems' => 999,
            ), $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
        ),
        'files' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.files',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('files', array(
                    'appearance' => array(
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:media.addFileReference'
                    ),
                )
            ),
        ),
        'youtube' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.youtube',
            'config' => array(
                'type' => 'text',
                'cols' => 60,
                'rows' => 3,
            )
        ),
        'additional_image' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.additional_image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('additional_image',
                array(
                    'appearance' => array(
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                    ),
                    'minitems' => 0,
                    'maxitems' => 999,
                ), $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
        ),
        'registration' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.registrations',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_sfeventmgt_domain_model_registration',
                'foreign_field' => 'event',
                'maxitems' => 9999,
                'appearance' => array(
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ),
            ),
        ),
        'notify_admin' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.notify_admin',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'notify_organisator' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.notify_organisator',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'unique_email_check' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_event.unique_email_check',
            'displayCond' => 'FIELD:enable_registration:REQ:TRUE',
            'config' => array(
                'type' => 'check',
            ),
        ),

    ),
);
