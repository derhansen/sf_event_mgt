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
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hooks for DataHandler
 */
class DataHandlerHooks
{
    public const EVENT_TABLE = 'tx_sfeventmgt_domain_model_event';
    public const REGISTRATION_TABLE = 'tx_sfeventmgt_domain_model_registration';
    public const CUSTOMNOTIFICATIONLOG_TABLE = 'tx_sfeventmgt_domain_model_customnotificationlog';

    /**
     * Flushes the cache if a event record was edited.
     * This happens on two levels: by UID and by PID.
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
     */
    public function processDatamap_postProcessFieldArray(
        string $status,
        string $table,
        string|int $id,
        array &$fieldArray,
        DataHandler $dataHandler
    ): void {
        if ($table === 'tt_content' &&
            ($status === 'update' || $status === 'new') &&
            isset($fieldArray['pi_flexform']) &&
            in_array(
                $dataHandler->checkValue_currentRecord['CType'],
                [
                    'sfeventmgt_pieventlist',
                    'sfeventmgt_pieventdetail',
                    'sfeventmgt_pieventregistration',
                    'sfeventmgt_pieventsearch',
                    'sfeventmgt_pieventcalendar',
                    'sfeventmgt_piuserreg',
                    'sfeventmgt_pipayment',
                ],
                true
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
                    'settings.singleEvent',
                ],
                'additional' => [
                    'settings.detailPid',
                    'settings.listPid',
                    'settings.registrationPid',
                    'settings.paymentPid',
                    'settings.restrictForeignRecordsToStoragePage',
                ],
                'pagination' => [
                    'settings.pagination.enablePagination',
                    'settings.pagination.itemsPerPage',
                    'settings.pagination.maxNumPages',
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
                        ($flexformData['data'][$sheet]['lDEF'][$field]['vDEF'] === '' ||
                            $flexformData['data'][$sheet]['lDEF'][$field]['vDEF'] === '0')
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
            $fieldArray['pi_flexform'] = $flexFormTools->flexArray2Xml($flexformData);
        }
    }

    /**
     * Hides non deleted event registrations before copy and localize in order to prevent registrations from being
     * copied or localized.
     *
     * @todo: Try to extend TYPO3 core to allow 3rd party extension to modifying `$excludeFields` in
     *        `DataHandler->copyRecord()` function.
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
        if ($table === self::EVENT_TABLE && in_array($command, ['copy', 'localize'])) {
            $this->hideRegistrationsBeforeCopyAndLocalize($id);
        }
    }

    /**
     * (1) Unhides non deleted event registrations after copy and localize
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
        if ($table !== self::EVENT_TABLE) {
            return;
        }

        if (in_array($command, ['copy', 'localize'])) {
            $this->unhideRegistrationsAfterCopyAndLocalize($id);
        } elseif ($command === 'delete') {
            $this->deleteCustomNotificationsByEvent($id);
        }
    }

    /**
     * Removes all custom notification log entries for the given event UID
     */
    protected function deleteCustomNotificationsByEvent(int $eventUid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::CUSTOMNOTIFICATIONLOG_TABLE);
        $queryBuilder
            ->delete(self::CUSTOMNOTIFICATIONLOG_TABLE)
            ->where(
                $queryBuilder->expr()->eq('event', $queryBuilder->createNamedParameter($eventUid, Connection::PARAM_INT))
            )
            ->executeStatement();
    }

    protected function hideRegistrationsBeforeCopyAndLocalize(int $eventUid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::CUSTOMNOTIFICATIONLOG_TABLE);
        $queryBuilder
            ->update(self::REGISTRATION_TABLE)
            ->set('deleted', 1)
            ->set('temp_event_uid', $eventUid)
            ->where(
                $queryBuilder->expr()->eq('event', $queryBuilder->createNamedParameter($eventUid, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT))
            )
            ->executeStatement();
    }

    protected function unhideRegistrationsAfterCopyAndLocalize(int $eventUid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::CUSTOMNOTIFICATIONLOG_TABLE);
        $queryBuilder
            ->update(self::REGISTRATION_TABLE)
            ->set('deleted', 0)
            ->set('temp_event_uid', 0)
            ->where(
                $queryBuilder->expr()->eq('temp_event_uid', $queryBuilder->createNamedParameter($eventUid, Connection::PARAM_INT))
            )
            ->executeStatement();
    }
}
