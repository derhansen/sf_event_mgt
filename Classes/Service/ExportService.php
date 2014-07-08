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
	 * Export Registrations to CSV File
	 *
	 * @param int $uid
	 * @param string $fields
	 * @throws \RuntimeException
	 * @return string
	 */
	public function exportToCsvFile($uid, $fields = '') {
		$fieldsArray = array_map('trim', explode(',', $fields));
		$registrations = $this->registrationRepository->findByEvent($uid);
		$exportedRegistrations = Utility\GeneralUtility::csvValues($fieldsArray) . chr(10);;
		foreach ($registrations as $registration) {
			$exportedRegistration = array();
			foreach ($fieldsArray as $field) {
				if ($registration->_hasProperty($field)) {
					$exportedRegistration[] = $registration->_getCleanProperty($field);
				} else {
					throw new RuntimeException('Field ' . $field . ' is not a Property of Model Registration, please check your TS configuration');
				}
			}
			$exportedRegistrations .= Utility\GeneralUtility::csvValues($exportedRegistration) . chr(10);
		}
		return $exportedRegistrations;
	}
} 