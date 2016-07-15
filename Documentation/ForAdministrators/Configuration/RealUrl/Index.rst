.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _realurl:

RealURL
=======

Below follows an example configuration for RealURL.

RealURL Configuration::

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'] = array (
        '_DEFAULT' => array (
            'init' => array (
                'enableCHashCache' => '1',
                'appendMissingSlash' => 'ifNotFile',
                'enableUrlDecodeCache' => '1',
                'enableUrlEncodeCache' => '1',
            ),
            'fixedPostVars' => array (
                    'eventDetailConfiguration' => array(
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[action]',
                                    'valueMap' => array(
                                            'detail' => '',
                                    ),
                                    'noMatch' => 'bypass'
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[controller]',
                                    'valueMap' => array(
                                            'Event' => '',
                                    ),
                                    'noMatch' => 'bypass'
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[event]',
                                    'lookUpTable' => array(
                                            'table' => 'tx_sfeventmgt_domain_model_event',
                                            'id_field' => 'uid',
                                            'alias_field' => 'title',
                                            'addWhereClause' => ' AND NOT deleted',
                                            'useUniqueCache' => 1,
                                            'useUniqueCache_conf' => array(
                                                    'strtolower' => 1,
                                                    'spaceCharacter' => '-'
                                            ),
                                            'languageGetVar' => 'L',
                                            'languageExceptionUids' => '',
                                            'languageField' => 'sys_language_uid',
                                            'transOrigPointerField' => 'l10n_parent',
                                            'autoUpdate' => 1,
                                            'expireDays' => 180,
                                    )
                            )
                    ),
                    '123' => 'eventDetailConfiguration',
                    'eventCategoryListConfiguration' => array(
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[action]',
                                    'valueMap' => array(
                                    ),
                                    'noMatch' => 'bypass'
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[controller]',
                                    'valueMap' => array(
                                    ),
                                    'noMatch' => 'bypass'
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[overwriteDemand][category]',
                                    'lookUpTable' => array(
                                            'table' => 'tx_sfeventmgt_domain_model_category',
                                            'id_field' => 'uid',
                                            'alias_field' => 'title',
                                            'addWhereClause' => ' AND NOT deleted',
                                            'useUniqueCache' => 1,
                                            'useUniqueCache_conf' => array(
                                                    'strtolower' => 1,
                                                    'spaceCharacter' => '-'
                                            ),
                                            'languageGetVar' => 'L',
                                            'languageExceptionUids' => '',
                                            'languageField' => 'sys_language_uid',
                                            'transOrigPointerField' => 'l10n_parent',
                                            'autoUpdate' => 1,
                                            'expireDays' => 180,
                                    )
                            )
                    ),
                    '124' => 'eventCategoryListConfiguration',
                    'eventRegistrationConfiguration' => array(
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[action]',
                                    'valueMap' => array(
                                            'register' => 'registration',
                                            'register-save' => 'saveRegistration',
                                            'registration-result' => 'saveRegistrationResult',
                                            'registration-confirm' => 'confirmRegistration',
                                            'registration-cancel' => 'cancelRegistration'
                                    ),
                                    'noMatch' => 'bypass'
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[controller]',
                                    'valueMap' => array(
                                    ),
                                    'noMatch' => 'bypass'
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[result]',
                                    'valueMap' => array(
                                        'result' => '',
                                    ),
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[reguid]',
                                    'valueMap' => array(
                                        'reguid' => '',
                                    ),
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[hmac]',
                                    'valueMap' => array(
                                        'hmac' => '',
                                    ),
                            ),
                            array(
                                    'GETvar' => 'tx_sfeventmgt_pievent[event]',
                                    'lookUpTable' => array(
                                            'table' => 'tx_sfeventmgt_domain_model_event',
                                            'id_field' => 'uid',
                                            'alias_field' => 'title',
                                            'addWhereClause' => ' AND NOT deleted',
                                            'useUniqueCache' => 1,
                                            'useUniqueCache_conf' => array(
                                                    'strtolower' => 1,
                                                    'spaceCharacter' => '-'
                                            ),
                                            'languageGetVar' => 'L',
                                            'languageExceptionUids' => '',
                                            'languageField' => 'sys_language_uid',
                                            'transOrigPointerField' => 'l10n_parent',
                                            'autoUpdate' => 1,
                                            'expireDays' => 180,
                                    )
                            )
                    ),
                    '125' => 'eventRegistrationConfiguration',

            ),
    );

The above example includes a section for the detailview "eventDetailConfiguration". You must assign the **page ID** of
each page which contains the plugin configured in detail mode. The example shows, that page ID "123" is assigned
the "eventDetailConfiguration". If you have multiple detail views, add a new line with the page ID for each detail
page.

The configuration above also includes a section for event category listing (eventCategoryListConfiguration - ID 124)
and also for the registration page (eventRegistrationConfiguration - ID 125). Please be aware of, that the registration
link must include **all parameters** as shown above.