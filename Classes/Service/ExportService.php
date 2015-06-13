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

use \TYPO3\CMS\Core\Utility;
use \RuntimeException;

/**
 * Class ExportService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ExportService {

	/**
	 * Repository with registrations for the events
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
	 * @inject
	 */
	protected $registrationRepository;

	/**
	 * ResourceFactory
	 *
	 * @var \TYPO3\CMS\Core\Resource\ResourceFactory
	 * @inject
	 */
	protected $resourceFactory = NULL;

	/**
	 * Initiates the CSV downloads for registrations of the given event uid
	 *
	 * @param int $eventUid EventUid
	 * @param array $settings Settings
	 *
	 * @throws \RuntimeException RuntimeException
	 * @return void
	 */
	public function downloadRegistrationsCsv($eventUid, $settings = array()) {
		$storage = $this->resourceFactory->getDefaultStorage();
		if ($storage === NULL) {
			throw new RuntimeException('Could not get the default storage', 1475590001);
		}
		$registrations = $this->exportRegistrationsCsv($eventUid, $settings);
		$tempFolder = $storage->getFolder('_temp_');
		$tempFile = $storage->createFile('sf_events_export.csv', $tempFolder);
		$tempFile->setContents($registrations);
		$storage->dumpFileContents($tempFile, TRUE, 'registrations_' . date('dmY_His') . '.csv');
	}

	/**
	 * Returns all Registrations for the given eventUid as a CSV string
	 *
	 * @param int $eventUid EventUid
	 * @param array $settings Settings
	 *
	 * @throws \RuntimeException RuntimeException
	 * @return string
	 */
	public function exportRegistrationsCsv($eventUid, $settings = array()) {
		$fieldsArray = array_map('trim', explode(',', $settings['fields']));
		$registrations = $this->registrationRepository->findByEvent($eventUid);
		$exportedRegistrations = Utility\GeneralUtility::csvValues($fieldsArray,
				$settings['fieldDelimiter'], $settings['fieldQuoteCharacter']) . chr(10);
		foreach ($registrations as $registration) {
			$exportedRegistration = array();
			foreach ($fieldsArray as $field) {
				if ($registration->_hasProperty($field)) {
					$exportedRegistration[] = $registration->_getCleanProperty($field);
				} else {
					throw new RuntimeException('Field ' . $field .
						' is not a Property of Model Registration, please check your TS configuration', 1475590002);
				}
			}
			$exportedRegistrations .= Utility\GeneralUtility::csvValues($exportedRegistration,
					$settings['fieldDelimiter'], $settings['fieldQuoteCharacter']) . chr(10);
		}
		return $exportedRegistrations;
	}

}