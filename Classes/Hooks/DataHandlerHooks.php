<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Hooks;

use DERHANSEN\SfEventMgt\Service\EventCacheService;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hooks for DataHandler
 */
class DataHandlerHooks
{
    public const EVENT_TABLE = 'tx_sfeventmgt_domain_model_event';
    public const CUSTOMNOTIFICATIONLOG_TABLE = 'tx_sfeventmgt_domain_model_customnotificationlog';

    /**
     * Flushes the cache if a event record was edited.
     * This happens on two levels: by UID and by PID.
     *
     * @param array $params
     */
    public function clearCachePostProc(array $params): void
    {
        if (isset($params['table']) && $params['table'] === self::EVENT_TABLE) {
            $eventUid = $params['uid'] ?? 0;
            $pageUid = $params['uid_page'] ?? 0;
            if ($eventUid > 0 || $pageUid > 0) {
                $eventCacheService = GeneralUtility::makeInstance(EventCacheService::class);
                $eventCacheService->flushEventCache($eventUid, $pageUid);
            }
        }
    }

    /**
     * Checks if the fields defined in $checkFields are set in the data-array of pi_flexform.
     * If a field is present and contains an empty value, the field is unset. This way empty plugin settings
     * don't overwrite TypoScript settings.
     *
     * Structure of the checkFields array:
     *
     * array('sheet' => array('field1', 'field2'));
     *
     * @param string $status
     * @param string $table
     * @param string $id
     * @param array $fieldArray
     * @param DataHandler $dataHandler
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$dataHandler): void
    {
        if ($table === 'tt_content' &&
            $status == 'update' &&
            isset($fieldArray['pi_flexform']) &&
            $dataHandler->checkValue_currentRecord['CType'] === 'list' &&
            in_array(
                $dataHandler->checkValue_currentRecord['list_type'],
                [
                    'sfeventmgt_pieventlist',
                    'sfeventmgt_pieventdetail',
                    'sfeventmgt_pieventregistration',
                    'sfeventmgt_pieventsearch',
                    'sfeventmgt_pieventcalendar',
                    'sfeventmgt_piuserreg',
                    'sfeventmgt_pipayment',
                ]
            )
        ) {
            $checkFields = [
                'notification' => [
                    'settings.notification.senderEmail',
                    'settings.notification.senderName',
                    'settings.notification.adminEmail',
                    'settings.notification.replyToEmail',
                    'settings.notification.registrationNew.userSubject',
                    'settings.notification.registrationWaitlistNew.userSubject',
                    'settings.notification.registrationNew.adminSubject',
                    'settings.notification.registrationWaitlistNew.adminSubject',
                    'settings.notification.registrationConfirmed.userSubject',
                    'settings.notification.registrationWaitlistConfirmed.userSubject',
                    'settings.notification.registrationConfirmed.adminSubject',
                    'settings.notification.registrationWaitlistConfirmed.adminSubject',
                    'settings.notification.registrationCancelled.userSubject',
                    'settings.notification.registrationCancelled.adminSubject',
                    'settings.notification.registrationWaitlistMoveUp.userSubject',
                    'settings.notification.registrationWaitlistMoveUp.adminSubject',
                ],
                'sDEF' => [
                    'settings.displayMode',
                    'settings.orderField',
                    'settings.orderDirection',
                    'settings.topEventRestriction',
                    'settings.timeRestrictionLow',
                    'settings.timeRestrictionHigh',
                    'settings.includeCurrent',
                    'settings.queryLimit',
                    'settings.category',
                    'settings.storagePage',
                    'settings.registration.requiredFields',
                    'settings.userRegistration.displayMode',
                    'settings.userRegistration.orderField',
                    'settings.userRegistration.orderDirection',
                    'settings.userRegistration.storagePage',
                    'settings.userRegistration.recursive',
                ],
                'additional' => [
                    'settings.detailPid',
                    'settings.listPid',
                    'settings.registrationPid',
                    'settings.paymentPid',
                    'settings.restrictForeignRecordsToStoragePage',
                    'settings.disableOverrideDemand',
                ],
                'template' => [
                    'settings.templateLayout',
                ],
            ];

            $flexformData = GeneralUtility::xml2array($fieldArray['pi_flexform']);
            if (!is_array($flexformData)) {
                return;
            }

            foreach ($checkFields as $sheet => $fields) {
                foreach ($fields as $field) {
                    if (isset($flexformData['data'][$sheet]['lDEF'][$field]['vDEF']) &&
                        $flexformData['data'][$sheet]['lDEF'][$field]['vDEF'] === ''
                    ) {
                        unset($flexformData['data'][$sheet]['lDEF'][$field]);
                    }
                }

                // If remaining sheet does not contain fields, then remove the sheet
                if (isset($flexformData['data'][$sheet]['lDEF']) && $flexformData['data'][$sheet]['lDEF'] === []) {
                    unset($flexformData['data'][$sheet]);
                }
            }

            $flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
            $fieldArray['pi_flexform'] = $flexFormTools->flexArray2Xml($flexformData, true);
        }
    }

    /**
     * Sets the TCA type of the fields 'registration' and 'registration_waitlist' to 'none' for copy and localize,
     * so field values will not be duplicated in the copy of the object.
     *
     * @param string $command
     * @param string $table
     * @param int $id
     * @param mixed $value
     * @param DataHandler $pObj
     * @param bool $pasteUpdate
     */
    public function processCmdmap_preProcess($command, $table, $id, $value, $pObj, $pasteUpdate): void
    {
        if (in_array($command, ['copy', 'localize']) && $table === self::EVENT_TABLE) {
            $GLOBALS['TCA'][self::EVENT_TABLE]['columns']['registration']['config']['type'] = 'none';
            $GLOBALS['TCA'][self::EVENT_TABLE]['columns']['registration_waitlist']['config']['type'] = 'none';
        }
    }

    /**
     * (1) Sets the TCA type of certain fields back to their original state after a copy or move command
     * (2) Handles deletion of custom notifications when deleting an event
     *
     * @param string $command
     * @param string $table
     * @param int $id
     * @param string $value
     * @param DataHandler $pObj
     * @param bool $pasteUpdate
     * @param array $pasteDatamap
     */
    public function processCmdmap_postProcess($command, $table, $id, $value, $pObj, $pasteUpdate, $pasteDatamap): void
    {
        if (in_array($command, ['copy', 'localize']) && $table === self::EVENT_TABLE) {
            $GLOBALS['TCA'][self::EVENT_TABLE]['columns']['registration']['config']['type'] = 'inline';
            $GLOBALS['TCA'][self::EVENT_TABLE]['columns']['registration_waitlist']['config']['type'] = 'inline';
        } elseif ($command === 'delete' && $table === self::EVENT_TABLE) {
            $this->deleteCustomNotificationsByEvent($id);
        }
    }

    /**
     * Removes all custom notification log entried for the given event UID
     *
     * @param int $eventUid
     */
    protected function deleteCustomNotificationsByEvent(int $eventUid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::CUSTOMNOTIFICATIONLOG_TABLE);
        $queryBuilder
            ->delete(self::CUSTOMNOTIFICATIONLOG_TABLE)
            ->where(
                $queryBuilder->expr()->eq('event', $queryBuilder->createNamedParameter($eventUid, \PDO::PARAM_INT))
            )
            ->execute();
    }
}
