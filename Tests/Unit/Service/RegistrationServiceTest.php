<?php
namespace SKYFILLERS\SfEventMgt\Tests\Unit\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class SKYFILLERS\SfEventMgt\Service\RegistrationService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \SKYFILLERS\SfEventMgt\Service\RegistrationService
	 */
	protected $subject = NULL;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager
	 */
	protected $objectManager;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

		$this->subject = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Service\\RegistrationService');
	}

	/**
	 * Teardown
	 *
	 * @return void
	 */
	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function handleExpiredRegistrationsWithoutDeleteOption() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration',
			array('setHidden'), array(), '', FALSE);
		$registration->expects($this->once())->method('setHidden')->with(TRUE);

		/** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
		$registrations = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
		$registrations->attach($registration);

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findExpiredRegistrations', 'update'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findExpiredRegistrations')->will(
			$this->returnValue($registrations));
		$registrationRepository->expects($this->once())->method('update');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->handleExpiredRegistrations();
	}

	/**
	 * @test
	 */
	public function handleExpiredRegistrationsWithDeleteOption() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);

		/** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
		$registrations = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
		$registrations->attach($registration);

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findExpiredRegistrations', 'remove'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findExpiredRegistrations')->will(
			$this->returnValue($registrations));
		$registrationRepository->expects($this->once())->method('remove');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->handleExpiredRegistrations(TRUE);
	}

}
