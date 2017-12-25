<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
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

use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \DERHANSEN\SfEventMgt\Exception;

/**
 * Class ExportService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ExportService
{

    /**
     * Repository with registrations for the events
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     */
    protected $registrationRepository;

    /**
     * ResourceFactory
     *
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     */
    protected $resourceFactory = null;

    /**
     * ExportService constructor.
     *
     * @param RegistrationRepository $registrationRepository
     * @param ResourceFactory $resourceFactory
     */
    public function __construct(RegistrationRepository $registrationRepository, ResourceFactory $resourceFactory)
    {
        $this->registrationRepository = $registrationRepository;
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
        $storage = $this->resourceFactory->getDefaultStorage();
        if ($storage === null) {
            throw new Exception('Could not get the default storage', 1475590001);
        }
        $registrations = $this->exportRegistrationsCsv($eventUid, $settings);
        $tempFolder = $storage->getFolder('_temp_');
        $tempFile = $storage->createFile('sf_events_export.csv', $tempFolder);
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
        $fieldsArray = array_map('trim', explode(',', $settings['fields']));
        $registrations = $this->registrationRepository->findByEvent($eventUid);
        $exportedRegistrations = GeneralUtility::csvValues(
            $fieldsArray,
            $settings['fieldDelimiter'],
            $settings['fieldQuoteCharacter']
        ) . chr(10);
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
            $exportedRegistrations .= GeneralUtility::csvValues(
                $exportedRegistration,
                $settings['fieldDelimiter'],
                $settings['fieldQuoteCharacter']
            ) . chr(10);
        }
        return $this->prependByteOrderMark($exportedRegistrations, $settings);
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
}