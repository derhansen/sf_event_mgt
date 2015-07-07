<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'searchFields' => 'title,',
		'hideTable' => TRUE,
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('sf_event_mgt') . 'Resources/Public/Icons/logfile.png'
	),
	'interface' => array(
		'showRecordFieldList' => '',
	),
	'types' => array(
		'1' => array('showitem' => ''),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(

		'cruser_id' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.cruser',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'be_users',
				'foreign_class' => '\TYPO3\CMS\Beuser\Domain\Model\BackendUser',
				'maxitems' => 1
			)
		),
		'event' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.event',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_sfeventmgt_domain_model_event',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'details' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.details',
			'config' => array(
				'type' => 'input',
			),
		),
		'emails_sent' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.emailsSent',
			'config' => array(
				'type' => 'input',
			),
		),
		'tstamp' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.emailsSent',
			'config' => array(
				'type' => 'input',
			),
		),

	),
);
