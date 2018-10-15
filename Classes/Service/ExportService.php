<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Exception;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ExportService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ExportService
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     */
    protected $registrationRepository = null;

    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository
     */
    protected $eventRepository = null;

    /**
     * ResourceFactory
     *
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     */
    protected $resourceFactory = null;

    /**
     * @param RegistrationRepository $registrationRepository
     */
    public function injectRegistrationRepository(
        \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository $registrationRepository
    ) {
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository $eventRepository
     */
    public function injectEventRepository(\DERHANSEN\SfEventMgt\Domain\Repository\EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param ResourceFactory $resourceFactory
     */
    public function injectResourceFactory(\TYPO3\CMS\Core\Resource\ResourceFactory $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * Initiates the CSV downloads for registrations of the given event uid
     *
     * @param int $eventUid EventUid
     * @param array $settings Settings
     * @throws Exception RuntimeException
     * @return void
     */
    public function downloadRegistrationsCsv($eventUid, $settings = [])
    {
        $tempFolder = $this->getBackendUser()->getDefaultUploadTemporaryFolder();
        $storage = $this->resourceFactory->getDefaultStorage();
        if ($storage === null || $tempFolder === null) {
            throw new Exception('Could not get the default storage or default upload temp folder', 1475590001);
        }
        $registrations = $this->exportRegistrationsCsv($eventUid, $settings);
        $tempFilename = md5($eventUid . '_sf_events_export_' . time()) . '.csv';
        $tempFile = $storage->createFile($tempFilename, $tempFolder);
        $tempFile->setContents($registrations);
        $storage->dumpFileContents($tempFile, true, 'registrations_' . date('dmY_His') . '.csv');
        $tempFile->delete();
    }

   /**
     * Returns all Registrations for the given eventUid as a CSV string
     *
     * @param int $eventUid EventUid
     * @param array $settings Settings
     * @throws Exception RuntimeException
     * @return string
     */
    public function exportRegistrationsCsv($eventUid, $settings = [])
    {
        $hasRegistrationFields = false;
        $registrationFieldData = [];
        $fieldsArray = array_map('trim', explode(',', $settings['fields']));

        if (in_array('registration_fields', $fieldsArray)) {
            $hasRegistrationFields = true;
            $registrationFieldData = $this->getRegistrationFieldData($eventUid);
            $fieldsArray = array_diff($fieldsArray, ['registration_fields']);
        }
        $registrations = $this->registrationRepository->findByEvent($eventUid);
        $exportedRegistrations = GeneralUtility::csvValues(
            array_merge($fieldsArray, $registrationFieldData),
            $settings['fieldDelimiter'],
            $settings['fieldQuoteCharacter']
        ) . chr(10);
        /** @var Registration $registration */
        foreach ($registrations as $registration) {
            $exportedRegistration = [];
            foreach ($fieldsArray as $field) {
                if ($registration->_hasProperty($field)) {
                    $exportedRegistration[] = $this->getFieldValue($registration, $field);
                } else {
                    throw new Exception('Field ' . $field .
                        ' is not a Property of Model Registration, please check your TS configuration', 1475590002);
                }
            }
            if ($hasRegistrationFields) {
                $exportedRegistration = array_merge(
                    $exportedRegistration,
                    $this->getRegistrationFieldValues($registration, $registrationFieldData)
                );
            }
            $exportedRegistrations .= GeneralUtility::csvValues(
                $exportedRegistration,
                $settings['fieldDelimiter'],
                $settings['fieldQuoteCharacter']
            ) . chr(10);
        }

        return $this->prependByteOrderMark($exportedRegistrations, $settings);
    }

    /**
     * Returns an array with fieldvalues for the given registration
     *
     * @param Registration $registration
     * @param array $registrationFieldData
     * @return array
     */
    protected function getRegistrationFieldValues($registration, $registrationFieldData)
    {
        $result = [];
        $registrationFieldValues = [];
        /** @var Registration\FieldValue $fieldValue */
        foreach ($registration->getFieldValues() as $fieldValue) {
            $registrationFieldValues[$fieldValue->getField()->getUid()] = $fieldValue->getValueForCsvExport();
        }
        foreach ($registrationFieldData as $fieldUid => $fieldTitle) {
            if (isset($registrationFieldValues[$fieldUid])) {
                $result[] = $registrationFieldValues[$fieldUid];
            } else {
                $result[] = '';
            }
        }

        return $result;
    }

    /**
     * Returns an array of registration field uids and title
     *
     * @param int $eventUid
     * @return array
     */
    protected function getRegistrationFieldData($eventUid)
    {
        $result = [];
        /** @var Event $event */
        $event = $this->eventRepository->findByUid($eventUid);
        if ($event) {
            $result = $event->getRegistrationFieldUidsWithTitle();
        }

        return $result;
    }

    /**
     * Prepends Byte Order Mark to exported registrations
     *
     * @param string $exportedRegistrations
     * @param array $settings
     * @return string
     */
    protected function prependByteOrderMark($exportedRegistrations, $settings)
    {
        if ((bool)$settings['prependBOM']) {
            $exportedRegistrations = chr(239) . chr(187) . chr(191) . $exportedRegistrations;
        }

        return $exportedRegistrations;
    }

    /**
     * Returns the requested field from the given registration. If the field is a DateTime object,
     * a formatted date string is returned
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $field
     * @return string
     */
    protected function getFieldValue($registration, $field)
    {
        $value = $registration->_getCleanProperty($field);
        if ($value instanceof \DateTime) {
            $value = $value->format('d.m.Y');
        }

        return $value;
    }

    /**
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
}
