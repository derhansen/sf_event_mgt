<?php

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Update class for the extension manager.
 */
class ext_update
{
    /**
     * Array of flash messages (params) array[][status,title,message]
     *
     * @var array
     */
    protected $messageArray = [];

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * Main update function called by the extension manager.
     *
     * @return string
     */
    public function main()
    {
        $this->processUpdates();

        return $this->generateOutput();
    }

    /**
     * Returns if the update menu entry in EM should be shown.
     *
     * @return bool
     */
    public function access()
    {
        return true;
    }

    /**
     * The actual update function. Add your update task in here.
     *
     * @return void
     */
    protected function processUpdates()
    {
        $this->migrateEventCategoriesToSysCategories();
        $this->migrateFlexformFields();
    }

    /**
     * Generates output by using flash messages
     *
     * @return string
     */
    protected function generateOutput()
    {
        $output = '';
        foreach ($this->messageArray as $messageItem) {
            $output .= $this->getFlashMessage($messageItem[2], $messageItem[1], $messageItem[0]);
        }

        return $output;
    }

    /**
     * Renames flexform fields
     *
     * @return void
     */
    protected function migrateFlexformFields()
    {
        $this->renameFlexformField(
            'sfeventmgt_pievent',
            ['sDEF', 'settings.templateLayout'],
            ['template', 'settings.templateLayout']
        );
        $this->renameFlexformField(
            'sfeventmgt_pievent',
            ['sDEF', 'settings.detailPid'],
            ['additional', 'settings.detailPid']
        );
        $this->renameFlexformField(
            'sfeventmgt_pievent',
            ['sDEF', 'settings.listPid'],
            ['additional', 'settings.listPid']
        );
        $this->renameFlexformField(
            'sfeventmgt_pievent',
            ['sDEF', 'settings.registrationPid'],
            ['additional', 'settings.registrationPid']
        );
        $this->renameFlexformField(
            'sfeventmgt_pievent',
            ['sDEF', 'settings.paymentPid'],
            ['additional', 'settings.paymentPid']
        );
        $this->renameFlexformField(
            'sfeventmgt_pievent',
            ['sDEF', 'settings.disableOverrideDemand'],
            ['additional', 'settings.disableOverrideDemand']
        );
        $this->renameFlexformField(
            'sfeventmgt_pievent',
            ['sDEF', 'settings.restrictForeignRecordsToStoragePage'],
            ['additional', 'settings.restrictForeignRecordsToStoragePage']
        );
    }

    /**
     * Migrates old event categories to sys_categories if required
     *
     * @return void
     */
    protected function migrateEventCategoriesToSysCategories()
    {
        // check if tx_sfeventmgt_domain_model_category still exists
        $oldCategoryTableFields = $this->databaseConnection->admin_get_fields('tx_sfeventmgt_domain_model_category');
        if (count($oldCategoryTableFields) === 0) {
            $status = FlashMessage::NOTICE;
            $title = '';
            $message = 'Old category table does not exist anymore so no update needed';
            $this->messageArray[] = [$status, $title, $message];

            return;
        }
        // check if there are categories present else no update is needed
        $oldCategoryCount = $this->databaseConnection->exec_SELECTcountRows(
            'uid',
            'tx_sfeventmgt_domain_model_category',
            'deleted = 0'
        );
        if ($oldCategoryCount === 0) {
            $status = FlashMessage::NOTICE;
            $title = '';
            $message = 'No categories found in old table, no update needed';
            $this->messageArray[] = [$status, $title, $message];

            return;
        }
        $status = FlashMessage::NOTICE;
        $title = '';
        $message = 'Must migrate ' . $oldCategoryCount . ' categories.';
        $this->messageArray[] = [$status, $title, $message];

        // A temporary migration column is needed in old category table. Add this when not already present
        if (!array_key_exists('migrate_sys_category_uid', $oldCategoryTableFields)) {
            $this->databaseConnection->admin_query(
                "ALTER TABLE tx_sfeventmgt_domain_model_category ADD migrate_sys_category_uid int(11) DEFAULT '0' NOT NULL"
            );
        }

        // convert tx_sfeventmgt_domain_model_category records
        $this->migrateEventCategoryRecords();

        // set/update all relations
        $oldNewCategoryUidMapping = $this->getOldNewCategoryUidMapping();
        $this->updateParentFieldOfMigratedCategories($oldNewCategoryUidMapping);
        $this->migrateCategoryMmRecords($oldNewCategoryUidMapping);

        $this->updateFlexformCategories('sfeventmgt_pievent', $oldNewCategoryUidMapping, 'settings.category');

        /**
         * Finished category migration
         */
        $message = 'All categories are updated. Run <strong>DB compare</strong> in the install tool to remove the ' .
            'now obsolete `tx_sfeventmgt_domain_model_category` and `tx_sfeventmgt_event_category_mm` tables and ' .
            'run the <strong>DB check</strong> to update the reference index.';
        $status = FlashMessage::OK;
        $title = 'Updated all categories!';
        $this->messageArray[] = [$status, $title, $message];
    }

    /**
     * Process not yet migrated tx_sfeventmgt_domain_model_category records to sys_category records
     *
     * @return void
     */
    protected function migrateEventCategoryRecords()
    {
        // migrate default language category records
        $rows = $this->databaseConnection->exec_SELECTgetRows(
            'uid, pid, tstamp, crdate, cruser_id, starttime, endtime, sorting, ' .
            'sys_language_uid, l10n_parent, l10n_diffsource, title',
            'tx_sfeventmgt_domain_model_category',
            'migrate_sys_category_uid = 0 AND deleted = 0 AND sys_language_uid = 0'
        );
        if ($this->databaseConnection->sql_error()) {
            $message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
            $status = FlashMessage::ERROR;
            $title = 'Failed selecting old default language category records';
            $this->messageArray[] = [$status, $title, $message];
        }
        // Create a new sys_category record for each found record in default language, then
        $newCategoryRecords = 0;
        $oldNewDefaultLanguageCategoryUidMapping = [];
        foreach ($rows as $row) {
            $oldUid = $row['uid'];
            unset($row['uid']);
            if (is_null($row['l10n_diffsource'])) {
                $row['l10n_diffsource'] = '';
            }
            if ($this->databaseConnection->exec_INSERTquery('sys_category', $row) !== false) {
                $newUid = $this->databaseConnection->sql_insert_id();
                $oldNewDefaultLanguageCategoryUidMapping[$oldUid] = $newUid;
                $this->databaseConnection->exec_UPDATEquery(
                    'tx_sfeventmgt_domain_model_category',
                    'uid=' . $oldUid,
                    ['migrate_sys_category_uid' => $newUid]
                );
                $newCategoryRecords++;
            } else {
                $message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
                $status = FlashMessage::ERROR;
                $title = 'Failed copying [' . $oldUid . '] ' . htmlspecialchars($row['title']) . ' to sys_category';
                $this->messageArray[] = [$status, $title, $message];
            }
        }
        // migrate non-default language category records
        $rows = $this->databaseConnection->exec_SELECTgetRows(
            'uid, pid, tstamp, crdate, cruser_id, starttime, endtime, sorting, ' .
            'sys_language_uid, l10n_parent, l10n_diffsource, title',
            'tx_sfeventmgt_domain_model_category',
            'migrate_sys_category_uid = 0 AND deleted = 0 AND sys_language_uid != 0'
        );
        if ($this->databaseConnection->sql_error()) {
            $message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
            $status = FlashMessage::ERROR;
            $title = 'Failed selecting old non-default language category records';
            $this->messageArray[] = [$status, $title, $message];
        }
        foreach ($rows as $row) {
            $oldUid = $row['uid'];
            unset($row['uid']);
            if (is_null($row['l10n_diffsource'])) {
                $row['l10n_diffsource'] = '';
            }
            // set l10n_parent if category is a localized version
            if (array_key_exists($row['l10n_parent'], $oldNewDefaultLanguageCategoryUidMapping)) {
                $row['l10n_parent'] = $oldNewDefaultLanguageCategoryUidMapping[$row['l10n_parent']];
            }
            if ($this->databaseConnection->exec_INSERTquery('sys_category', $row) !== false) {
                $newUid = $this->databaseConnection->sql_insert_id();
                $oldNewDefaultLanguageCategoryUidMapping[$oldUid] = $newUid;
                $this->databaseConnection->exec_UPDATEquery(
                    'tx_sfeventmgt_domain_model_category',
                    'uid=' . $oldUid,
                    ['migrate_sys_category_uid' => $newUid]
                );
                $newCategoryRecords++;
            } else {
                $message = ' SQL ERROR: ' . $this->databaseConnection->sql_error();
                $status = FlashMessage::ERROR;
                $title = 'Failed copying [' . $oldUid . '] ' . htmlspecialchars($row['title']) . ' to sys_category';
                $this->messageArray[] = [$status, $title, $message];
            }
        }
        $message = 'Created ' . $newCategoryRecords . ' sys_category records';
        $status = FlashMessage::INFO;
        $title = '';
        $this->messageArray[] = [$status, $title, $message];
    }

    /**
     * Create a mapping array of old->new category uids
     *
     * @return array
     */
    protected function getOldNewCategoryUidMapping()
    {
        $rows = $this->databaseConnection->exec_SELECTgetRows(
            'uid, migrate_sys_category_uid',
            'tx_sfeventmgt_domain_model_category',
            'migrate_sys_category_uid > 0'
        );
        $oldNewCategoryUidMapping = [];
        foreach ($rows as $row) {
            $oldNewCategoryUidMapping[$row['uid']] = $row['migrate_sys_category_uid'];
        }

        return $oldNewCategoryUidMapping;
    }

    /**
     * Update parent column of migrated categories
     *
     * @param array $oldNewCategoryUidMapping
     * @return void
     */
    protected function updateParentFieldOfMigratedCategories(array $oldNewCategoryUidMapping)
    {
        $updatedRecords = 0;
        $toUpdate = $this->databaseConnection->exec_SELECTgetRows(
            'uid, parent',
            'tx_sfeventmgt_domain_model_category',
            'parent > 0'
        );
        foreach ($toUpdate as $row) {
            if (!empty($oldNewCategoryUidMapping[$row['parent']])) {
                $sysCategoryUid = $oldNewCategoryUidMapping[$row['uid']];
                $newParentUId = $oldNewCategoryUidMapping[$row['parent']];
                $this->databaseConnection->exec_UPDATEquery(
                    'sys_category',
                    'uid=' . $sysCategoryUid,
                    ['parent' => $newParentUId]
                );
                $updatedRecords++;
            }
        }
        $message = 'Set for ' . $updatedRecords . ' sys_category records the parent field';
        $status = FlashMessage::INFO;
        $title = '';
        $this->messageArray[] = [$status, $title, $message];
    }

    /**
     * Create new category MM records
     *
     * @param array $oldNewCategoryUidMapping
     * @return void
     */
    protected function migrateCategoryMmRecords(array $oldNewCategoryUidMapping)
    {
        $newMmCount = 0;
        $oldMmRecords = $this->databaseConnection->exec_SELECTgetRows(
            'uid_local, uid_foreign, sorting',
            'tx_sfeventmgt_event_category_mm',
            ''
        );
        foreach ($oldMmRecords as $oldMmRecord) {
            $oldCategoryUid = $oldMmRecord['uid_foreign'];
            if (!empty($oldNewCategoryUidMapping[$oldCategoryUid])) {
                $newMmRecord = [
                    'uid_local' => $oldNewCategoryUidMapping[$oldCategoryUid],
                    'uid_foreign' => $oldMmRecord['uid_local'],
                    'tablenames' => $oldMmRecord['tablenames'] ?: 'tx_sfeventmgt_domain_model_event',
                    'sorting_foreign' => $oldMmRecord['sorting'],
                    'fieldname' => 'category',
                ];
                // check if relation already exists
                $foundRelations = $this->databaseConnection->exec_SELECTcountRows(
                    'uid_local',
                    'sys_category_record_mm',
                    'uid_local=' . $newMmRecord['uid_local'] .
                    ' AND uid_foreign=' . $newMmRecord['uid_foreign'] .
                    ' AND tablenames="' . $newMmRecord['tablenames'] . '"' .
                    ' AND fieldname="' . $newMmRecord['fieldname'] . '"'
                );
                if ($foundRelations === 0) {
                    $this->databaseConnection->exec_INSERTquery('sys_category_record_mm', $newMmRecord);
                    if ($this->databaseConnection->sql_affected_rows()) {
                        $newMmCount++;
                    }
                }
            }
        }
        $message = 'Created ' . $newMmCount . ' new MM relations';
        $status = FlashMessage::INFO;
        $title = '';
        $this->messageArray[] = [$status, $title, $message];
    }

    /**
     * Update categories in flexforms
     *
     * @param string $pluginName
     * @param array $oldNewCategoryUidMapping
     * @param string $flexformField name of the flexform's field to look for
     * @return void
     */
    protected function updateFlexformCategories($pluginName, $oldNewCategoryUidMapping, $flexformField)
    {
        $count = 0;
        $title = 'Update flexforms categories (' . $pluginName . ':' . $flexformField . ')';
        $res = $this->databaseConnection->exec_SELECTquery(
            'uid, pi_flexform',
            'tt_content',
            'CType=\'list\' AND list_type=\'' . $pluginName . '\' AND deleted=0'
        );

        /** @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools $flexformTools */
        $flexformTools = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools');
        while ($row = $this->databaseConnection->sql_fetch_assoc($res)) {
            $status = null;
            $xmlArray = GeneralUtility::xml2array($row['pi_flexform']);
            if (!is_array($xmlArray) || !isset($xmlArray['data'])) {
                $status = FlashMessage::ERROR;
                $message = 'Flexform data of plugin "' . $pluginName . '" not found.';
            } elseif (!isset($xmlArray['data']['sDEF']['lDEF'])) {
                $status = FlashMessage::WARNING;
                $message = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain sheet: sDEF';
            } elseif (isset($xmlArray[$flexformField . '_updated'])) {
                $status = FlashMessage::NOTICE;
                $message = 'Flexform data of record tt_content:' . $row['uid'] . ' is already updated for ' . $flexformField . '. No update needed...';
            } else {
                // Some flexforms may have displayCond
                if (isset($xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'])) {
                    $updated = false;
                    $oldCategories = GeneralUtility::trimExplode(
                        ',',
                        $xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'],
                        true
                    );
                    if (!empty($oldCategories)) {
                        $newCategories = [];
                        foreach ($oldCategories as $uid) {
                            if (isset($oldNewCategoryUidMapping[$uid])) {
                                $newCategories[] = $oldNewCategoryUidMapping[$uid];
                                $updated = true;
                            } else {
                                $status = FlashMessage::WARNING;
                                $message = 'The category ' . $uid . ' of record tt_content:' . $row['uid'] . ' was not found in sys_category records. Maybe the category was deleted before the migration? Please check manually...';
                            }
                        }
                        if ($updated) {
                            $count++;
                            $xmlArray[$flexformField . '_updated'] = 1;
                            $xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'] = implode(',', $newCategories);
                            $this->databaseConnection->exec_UPDATEquery('tt_content', 'uid=' . $row['uid'], [
                                'pi_flexform' => $flexformTools->flexArray2Xml($xmlArray)
                            ]);
                        }
                    }
                }
            }
            if ($status !== null) {
                $this->messageArray[] = [$status, $title, $message];
            }
        }
        $status = FlashMessage::INFO;
        $message = 'Updated ' . $count . ' tt_content flexforms for  "' . $pluginName . ':' . $flexformField . '"';
        $this->messageArray[] = [$status, $title, $message];
    }

    /**
     * Renames a flex form field
     *
     * @param  string $pluginName The pluginName used in list_type
     * @param  array $oldFieldPointer Pointer array the old field. E.g. array('sheetName', 'fieldName');
     * @param  array $newFieldPointer  Pointer array the new field. E.g. array('sheetName', 'fieldName');
     * @return void
     */
    protected function renameFlexformField($pluginName, array $oldFieldPointer, array $newFieldPointer)
    {
        $title = 'Renaming flexform field for "' . $pluginName . '" - ' .
            ' sheet: ' . $oldFieldPointer[0] . ', field: ' . $oldFieldPointer[1] . ' to ' .
            ' sheet: ' . $newFieldPointer[0] . ', field: ' . $newFieldPointer[1];
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid, pi_flexform',
            'tt_content',
            'CType=\'list\' AND list_type=\'' . $pluginName . '\''
        );
        $flexformTools = GeneralUtility::makeInstance(TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class);
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $xmlArray = GeneralUtility::xml2array($row['pi_flexform']);
            if (!is_array($xmlArray) || !isset($xmlArray['data'])) {
                $status = FlashMessage::ERROR;
                $message = 'Flexform data of plugin "' . $pluginName . '" not found or invalid. (tt_content UID: ' . $row['uid'] . ')';
            } elseif (!$xmlArray['data'][$oldFieldPointer[0]]) {
                $status = FlashMessage::WARNING;
                $message = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain ' .
                    'sheet: ' . $oldFieldPointer[0];
            } else {
                $updated = false;
                foreach ($xmlArray['data'][$oldFieldPointer[0]] as $language => $fields) {
                    if ($fields[$oldFieldPointer[1]]) {
                        $xmlArray['data'][$newFieldPointer[0]][$language][$newFieldPointer[1]] = $fields[$oldFieldPointer[1]];
                        unset($xmlArray['data'][$oldFieldPointer[0]][$language][$oldFieldPointer[1]]);
                        $updated = true;
                    }
                }
                if ($updated === true) {
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid=' . $row['uid'], [
                        'pi_flexform' => $flexformTools->flexArray2Xml($xmlArray)
                    ]);
                    $message = 'OK!';
                    $status = FlashMessage::OK;
                } else {
                    $status = FlashMessage::NOTICE;
                    $message = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain ' .
                        'sheet: ' . $oldFieldPointer[0] . ', field: ' . $oldFieldPointer[1] . '. This can
                        also be because field has been updated already...';
                }
            }
            $this->messageArray[] = [$status, $title, $message];
        }
    }

    /**
     * Gets the message severity class name
     *
     * @param int $severity
     * @return string The message severity class name
     */
    public function getClass($severity)
    {
        $classes = [
            FlashMessage::NOTICE => 'notice',
            FlashMessage::INFO => 'info',
            FlashMessage::OK => 'success',
            FlashMessage::WARNING => 'warning',
            FlashMessage::ERROR => 'danger'
        ];

        return 'alert-' . $classes[$severity];
    }

    /**
     * Gets the message severity icon name
     *
     * @param int $severity
     * @return string The message severity icon name
     */
    public function getIconName($severity)
    {
        $icons = [
            FlashMessage::NOTICE => 'lightbulb-o',
            FlashMessage::INFO => 'info',
            FlashMessage::OK => 'check',
            FlashMessage::WARNING => 'exclamation',
            FlashMessage::ERROR => 'times'
        ];

        return $icons[$severity];
    }

    /**
     * Returns the HTML for the given message. Due to removal of the render() method of a FlashMessage in TYPO3 8
     * the functionality is directly implemented in this class, so both TYPO3 7.6 and 8.7 will show the messages
     * in the expected output.
     *
     * @param string $message
     * @param string $title
     * @param int $severity
     * @return string
     */
    protected function getFlashMessage($message, $title, $severity)
    {
        if (!empty($title)) {
            $title = '<h4 class="alert-title">' . $title . '</h4>';
        }
        $message = '
			<div class="alert ' . $this->getClass($severity) . '">
				<div class="media">
					<div class="media-left">
						<span class="fa-stack fa-lg">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-' . $this->getIconName($severity) . ' fa-stack-1x"></i>
						</span>
					</div>
					<div class="media-body">
						' . $title . '
						<div class="alert-message">' . $message . '</div>
					</div>
				</div>
			</div>';

        return $message;
    }
}
