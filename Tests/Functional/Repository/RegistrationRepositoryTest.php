<?php

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

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

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager
     */
    protected $objectManager;

    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     */
    protected $registrationRepository;

    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * Setup
     *
     * @throws \TYPO3\CMS\Core\Tests\Exception
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->registrationRepository = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository');

        $this->importDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_registration.xml');
    }

    /**
     * Test if findAll returns all records (expect hidden)
     *
     * @test
     * @return void
     */
    public function findAll()
    {
        $registrations = $this->registrationRepository->findAll();
        $this->assertEquals(11, $registrations->count());
    }

    /**
     * Data provider for findExpiredRegistrations
     *
     * @return array
     */
    public function findExpiredRegistrationsDataProvider()
    {
        return [
            'allRegistrationsExpired' => [
                1402826400, /* 15.06.2014 10:00 */
                3
            ],
            'noRegistrationsExpired' => [
                1402736400, /* 14.06.2014 09:00 */
                0
            ],
            'nowIs1030Am' => [
                1402741800, /* 14.06.2014 10:30 */
                1
            ],
        ];
    }

    /**
     * @dataProvider findExpiredRegistrationsDataProvider
     * @test
     */
    public function findExpiredRegistrations($dateNow, $expected)
    {
        $registrations = $this->registrationRepository->findExpiredRegistrations($dateNow);
        $this->assertEquals($expected, $registrations->count());
    }

    /**
     * Test with no parameters
     *
     * @test
     */
    public function findNotificationRegistrationsWithNoParameters()
    {
        $registrations = $this->registrationRepository->findNotificationRegistrations(null, null);
        $this->assertEquals(0, $registrations->count());
    }

    /**
     * Test for match on Event
     *
     * @test
     */
    public function findNotificationRegistrationsForEventUid2()
    {
        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $event->expects($this->once())->method('getUid')->will($this->returnValue(2));
        $registrations = $this->registrationRepository->findNotificationRegistrations($event, null);
        $this->assertEquals(1, $registrations->count());
    }

    /**
     * Data provider for findExpiredRegistrations
     *
     * @return array
     */
    public function findNotificationRegistrationsDataProvider()
    {
        return [
            'withEmptyConstraints' => [
                [],
                3
            ],
            'allPaidEquals1' => [
                [
                    'paid' => ['equals' => '1']
                ],
                2
            ],
            'confirmationUntilLessThan' => [
                [
                    'confirmationUntil' => ['lessThan' => '1402743600']
                ],
                2
            ],
            'confirmationUntilLessThanOrEqual' => [
                [
                    'confirmationUntil' => ['lessThanOrEqual' => '1402743600']
                ],
                3
            ],
            'confirmationUntilGreaterThan' => [
                [
                    'confirmationUntil' => ['greaterThan' => '1402740000']
                ],
                1
            ],
            'confirmationUntilGreaterThanOrEqual' => [
                [
                    'confirmationUntil' => ['greaterThanOrEqual' => '1402740000']
                ],
                3
            ],
            'multipleContraints' => [
                [
                    'confirmationUntil' => ['lessThan' => '1402743600'],
                    'paid' => ['equals' => '0']
                ],
                1
            ],
        ];
    }

    /**
     * Test for match on Event
     *
     * @dataProvider findNotificationRegistrationsDataProvider
     * @test
     */
    public function findNotificationRegistrationsForEventUid1WithConstraints($constraints, $expected)
    {
        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $event->expects($this->once())->method('getUid')->will($this->returnValue(1));
        $registrations = $this->registrationRepository->findNotificationRegistrations($event, $constraints);
        $this->assertEquals($expected, $registrations->count());
    }

    /**
     * Test for match on Event with unknown condition
     *
     * @expectedException \InvalidArgumentException
     * @test
     */
    public function findNotificationRegistrationsForEventWithConstraintsButWrongCondition()
    {
        $constraints = ['confirmationUntil' => ['wrongcondition' => '0']];
        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $this->registrationRepository->findNotificationRegistrations($event, $constraints);
    }

    /**
     * Test if ignoreNotifications is respected
     *
     * @test
     */
    public function findNotificationRegistrationsRespectsIgnoreNotificationsForEventUid3()
    {
        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $event->expects($this->once())->method('getUid')->will($this->returnValue(3));
        $registrations = $this->registrationRepository->findNotificationRegistrations($event, null);
        $this->assertEquals(1, $registrations->count());
    }

    /**
     * Test if findEventRegistrationsByEmail finds expected amount of registrations
     *
     * @test
     */
    public function findEventRegistrationsByEmailReturnsExpectedAmountOfRegistrations()
    {
        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $event->expects($this->once())->method('getUid')->will($this->returnValue(10));
        $registrations = $this->registrationRepository->findEventRegistrationsByEmail($event, 'email@domain.tld');
        $this->assertEquals(1, $registrations->count());
    }

}
