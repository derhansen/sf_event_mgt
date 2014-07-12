<?php
namespace SKYFILLERS\SfEventMgt\Service;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Thies Kracht <t.kracht@skyfillers.com>, Skyfillers GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use \TYPO3\CMS\Core\Utility;
use \RuntimeException;

/**
 * Class ExportService
 */
class ExportService {

	/**
	 * Repository with registrations for the events
	 *
	 * @var \SKYFILLERS\SfEventMgt\Domain\Repository\RegistrationRepository
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
	 * @param int $eventUid
	 * @param array $settings
	 * @throws \RuntimeException
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
	 * @param int $eventUid
	 * @param array $settings
	 * @throws \RuntimeException
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