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

/**
 * RegistrationService
 */
class RegistrationService {

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
}