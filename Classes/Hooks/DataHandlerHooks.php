<?php
namespace DERHANSEN\SfEventMgt\Hooks;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Service\EventCacheService;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hooks for DataHandler
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class DataHandlerHooks
{
    /**
     * Flushes the cache if a event record was edited.
     * This happens on two levels: by UID and by PID.
     *
     * @param array $params
     */
    public function clearCachePostProc(array $params)
    {
        if (isset($params['table']) && $params['table'] === 'tx_sfeventmgt_domain_model_event') {
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
     * If a field is present and contains an empty value, the field is unset.
     *
     * Structure of the checkFields array:
     *
     * array('sheet' => array('field1', 'field2'));
     *
     * @param string $status
     * @param string $table
     * @param string $id
     * @param array $fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $reference
     *
     * @return void
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$reference)
    {
        if ($table === 'tt_content' && $status == 'update' && isset($fieldArray['pi_flexform'])) {
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
                ],
                'sDEF' => [
                    'settings.displayMode',
                    'settings.orderField',
                    'settings.orderDirection',
                    'settings.topEventRestriction',
                    'settings.queryLimit',
                    'settings.category',
                    'settings.storagePage',
                    'settings.registration.requiredFields',
                    'settings.userRegistration.displayMode',
                    'settings.userRegistration.orderField',
                    'settings.userRegistration.orderDirection',
                    'settings.userRegistration.storagePage',
                    'settings.userRegistration.recursive'
                ],
                'additional' => [
                    'settings.detailPid',
                    'settings.listPid',
                    'settings.registrationPid',
                    'settings.paymentPid',
                    'settings.restrictForeignRecordsToStoragePage',
                    'settings.disableOverrideDemand'
                ],
                'template' => [
                    'settings.templateLayout'
                ]
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

            /** @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools $flexFormTools */
            $flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
            $fieldArray['pi_flexform'] = $flexFormTools->flexArray2Xml($flexformData, true);
        }
    }
}
