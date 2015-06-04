<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/**
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

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\RegistrationService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DERHANSEN\SfEventMgt\Service\RegistrationService
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
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
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array('setHidden'), array(), '', FALSE);
		$registration->expects($this->once())->method('setHidden')->with(TRUE);

		/** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
		$registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$registrations->attach($registration);

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
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
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);

		/** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
		$registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$registrations->attach($registration);

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findExpiredRegistrations', 'remove'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findExpiredRegistrations')->will(
			$this->returnValue($registrations));
		$registrationRepository->expects($this->once())->method('remove');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->handleExpiredRegistrations(TRUE);
	}

	/**
	 * @test
	 */
	public function createDependingRegistrationsCreatesAmountOfExpectedRegistrations() {
		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);
		$mockRegistration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(5));

		$newRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);
		$newRegistration->expects($this->any())->method('setMainRegistration');
		$newRegistration->expects($this->any())->method('setAmountOfRegistrations');
		$newRegistration->expects($this->any())->method('setIgnoreNotifications');

		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array(), array(), '', FALSE);
		$objectManager->expects($this->any())->method('get')->will($this->returnValue($newRegistration));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('add'), array(), '', FALSE);
		$registrationRepository->expects($this->exactly(4))->method('add')->with($newRegistration);
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->createDependingRegistrations($mockRegistration);
	}

	/**
	 * @test
	 */
	public function confirmDependingRegistrationsConfirmsDependingRegistrations() {
		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);

		$foundRegistration1 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);
		$foundRegistration1->expects($this->any())->method('setConfirmed');

		$foundRegistration2 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);
		$foundRegistration2->expects($this->any())->method('setConfirmed');

		/** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
		$registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$registrations->attach($foundRegistration1);
		$registrations->attach($foundRegistration2);

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByMainRegistration', 'update'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByMainRegistration')->will($this->returnValue($registrations));
		$registrationRepository->expects($this->exactly(2))->method('update');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->confirmDependingRegistrations($mockRegistration);
	}
}
