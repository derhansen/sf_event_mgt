<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

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

	/**
	 * @test
	 */
	public function cancelDependingRegistrationsRemovesDependingRegistrations() {
		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);

		$foundRegistration1 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);

		$foundRegistration2 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array(), array(), '', FALSE);

		/** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
		$registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$registrations->attach($foundRegistration1);
		$registrations->attach($foundRegistration2);

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByMainRegistration', 'remove'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByMainRegistration')->will($this->returnValue($registrations));
		$registrationRepository->expects($this->exactly(2))->method('remove');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->cancelDependingRegistrations($mockRegistration);
	}

	/**
	 * Test if expected array is returned if HMAC validations fails
	 *
	 * @test
	 * @return void
	 */
	public function checkConfirmRegistrationIfHmacValidationFailsTest() {
		$reguid = 1;
		$hmac = 'invalid-hmac';

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(FALSE));
		$this->inject($this->subject, 'hashService', $hashService);

		$result = $this->subject->checkConfirmRegistration($reguid, $hmac);
		$expected = array(
			TRUE,
			NULL,
			'event.message.confirmation_failed_wrong_hmac',
			'confirmRegistration.title.failed'
		);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if expected array is returned if no Registration found
	 *
	 * @test
	 * @return void
	 */
	public function checkConfirmRegistrationIfNoRegistrationTest() {
		$reguid = 1;
		$hmac = 'valid-hmac';

		$mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1);
		$this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

		$mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $mockHashService);

		$result = $this->subject->checkConfirmRegistration($reguid, $hmac);
		$expected = array(
			TRUE,
			NULL,
			'event.message.confirmation_failed_registration_not_found',
			'confirmRegistration.title.failed'
		);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if expected array is returned if confirmation date expired
	 *
	 * @test
	 * @return void
	 */
	public function checkConfirmRegistrationIfConfirmationDateExpiredTest() {
		$reguid = 1;
		$hmac = 'valid-hmac';

		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$mockRegistration->expects($this->any())->method('getConfirmationUntil')->will($this->returnValue(new \DateTime('yesterday')));

		$mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
		$this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

		$mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $mockHashService);

		$result = $this->subject->checkConfirmRegistration($reguid, $hmac);
		$expected = array(
			TRUE,
			$mockRegistration,
			'event.message.confirmation_failed_confirmation_until_expired',
			'confirmRegistration.title.failed'
		);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if expected array is returned if registration already confirmed
	 *
	 * @test
	 * @return void
	 */
	public function checkConfirmRegistrationIfRegistrationConfirmedTest() {
		$reguid = 1;
		$hmac = 'valid-hmac';

		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$mockRegistration->expects($this->any())->method('getConfirmationUntil')->will($this->returnValue(new \DateTime('tomorrow')));
		$mockRegistration->expects($this->any())->method('getConfirmed')->will($this->returnValue(TRUE));

		$mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
		$this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

		$mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $mockHashService);

		$result = $this->subject->checkConfirmRegistration($reguid, $hmac);
		$expected = array(
			TRUE,
			$mockRegistration,
			'event.message.confirmation_failed_already_confirmed',
			'confirmRegistration.title.failed'
		);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if expected array is returned if HMAC validations fails
	 *
	 * @test
	 * @return void
	 */
	public function checkCancelRegistrationIfHmacValidationFailsTest() {
		$reguid = 1;
		$hmac = 'invalid-hmac';

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(FALSE));
		$this->inject($this->subject, 'hashService', $hashService);

		$result = $this->subject->checkCancelRegistration($reguid, $hmac);
		$expected = array(
			TRUE,
			NULL,
			'event.message.cancel_failed_wrong_hmac',
			'cancelRegistration.title.failed'
		);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if expected array is returned if no Registration found
	 *
	 * @test
	 * @return void
	 */
	public function checkCancelRegistrationIfNoRegistrationTest() {
		$reguid = 1;
		$hmac = 'valid-hmac';

		$mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1);
		$this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

		$mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $mockHashService);

		$result = $this->subject->checkCancelRegistration($reguid, $hmac);
		$expected = array(
			TRUE,
			NULL,
			'event.message.cancel_failed_registration_not_found_or_cancelled',
			'cancelRegistration.title.failed'
		);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if expected array is returned if cancellation is not enabled
	 *
	 * @test
	 * @return void
	 */
	public function checkCancelRegistrationIfCancellationIsNotEnabledTest() {
		$reguid = 1;
		$hmac = 'valid-hmac';

		$mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(FALSE));

		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));

		$mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
		$this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

		$mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $mockHashService);

		$result = $this->subject->checkCancelRegistration($reguid, $hmac);
		$expected = array(
			TRUE,
			$mockRegistration,
			'event.message.confirmation_failed_cancel_disabled',
			'cancelRegistration.title.failed'
		);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test if expected array is returned if cancellation deadline expired
	 *
	 * @test
	 * @return void
	 */
	public function checkCancelRegistrationIfCancellationDeadlineExpiredTest() {
		$reguid = 1;
		$hmac = 'valid-hmac';

		$mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(TRUE));
		$mockEvent->expects($this->any())->method('getCancelDeadline')->will($this->returnValue(new \DateTime('yesterday')));

		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));

		$mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
		$this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

		$mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $mockHashService);

		$result = $this->subject->checkCancelRegistration($reguid, $hmac);
		$expected = array(
			TRUE,
			$mockRegistration,
			'event.message.cancel_failed_deadline_expired',
			'cancelRegistration.title.failed'
		);
		$this->assertEquals($expected, $result);
	}
}
