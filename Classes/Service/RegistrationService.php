<?php
namespace DERHANSEN\SfEventMgt\Service;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
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

use \TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * RegistrationService
 */
class RegistrationService {

	/**
	 * The object manager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;

	/**
	 * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
	 * @inject
	 */
	protected $registrationRepository;

	/**
	 * Handles expired registrations. If the $delete parameter is set, then
	 * registrations are deleted, else just hidden
	 *
	 * @param bool $delete
	 * @return void
	 */
	public function handleExpiredRegistrations($delete = FALSE) {
		$registrations = $this->registrationRepository->findExpiredRegistrations(new \DateTime());
		if ($registrations->count() > 0) {
			foreach ($registrations as $registration) {
				/** @var \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration */
				if ($delete) {
					$this->registrationRepository->remove($registration);
				} else {
					$registration->setHidden(TRUE);
					$this->registrationRepository->update($registration);
				}
			}
		}
	}

	/**
	 * Duplicates (all public accessable properties) the given registration the
	 * amount of times configured in amountOfRegistrations
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
	 * @return void
	 */
	public function createDependingRegistrations($registration) {
		for ($i = 1; $i <= $registration->getAmountOfRegistrations() - 1; $i++) {
			/** @var \DERHANSEN\SfEventMgt\Domain\Model\Registration $newReg */
			$newReg = $this->objectManager->get('DERHANSEN\SfEventMgt\Domain\Model\Registration');
			$properties = ObjectAccess::getGettableProperties($registration);
			foreach ($properties as $propertyName => $propertyValue) {
				ObjectAccess::setProperty($newReg, $propertyName, $propertyValue);
			}
			$newReg->setMainRegistration($registration);
			$newReg->setAmountOfRegistrations(1);
			$newReg->setIgnoreNotifications(TRUE);
			$this->registrationRepository->add($newReg);
		}
	}

	/**
	 * Confirms all depending registrations based on the given main registration
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
	 * @return void
	 */
	public function confirmDependingRegistrations($registration) {
		$registrations = $this->registrationRepository->findByMainRegistration($registration);
		foreach($registrations as $foundRegistration) {
			/** @var \DERHANSEN\SfEventMgt\Domain\Model\Registration $foundRegistration */
			$foundRegistration->setConfirmed(TRUE);
			$this->registrationRepository->update($foundRegistration);
		}
	}
}