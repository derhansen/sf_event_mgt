<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

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

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\UserRegistrationController
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class UserRegistrationControllerTest extends UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Controller\UserRegistrationController
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\Controller\\UserRegistrationController',
            ['createUserRegistrationDemandObjectFromSettings'],
            [],
            '',
            false
        );
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * Test if settings are used in UserRegistrationDemand object
     *
     * @test
     * @return void
     */
    public function createUserRegistrationDemandObjectFromSettingsTest()
    {
        $mockController = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Controller\\UserRegistrationController',
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );

        $settings = [];
        $settings['userRegistration'] = [
            'displayMode' => 'all',
            'storagePage' => 1,
            'orderField' => 'event.title',
            'orderDirection' => 'asc',
        ];

        $mockDemand = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\UserRegistrationDemand',
            [],
            [],
            '',
            false
        );
        $mockDemand->expects($this->at(0))->method('setDisplayMode')->with('all');
        $mockDemand->expects($this->at(1))->method('setStoragePage')->with(1);
        $mockDemand->expects($this->at(2))->method('setOrderField')->with('event.title');
        $mockDemand->expects($this->at(3))->method('setOrderDirection')->with('asc');

        $objectManager = $this->getMock(
            'TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            [],
            [],
            '',
            false
        );
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($mockDemand));
        $this->inject($mockController, 'objectManager', $objectManager);

        $mockController->createUserRegistrationDemandObjectFromSettings($settings);
    }

    /**
     * Test if listAction assigns registrations to view
     *
     * @test
     * @return void
     */
    public function listActionFetchesRegistrationsFromRepositoryAndAssignsThemToView()
    {
        $demand = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\UserRegistrationDemand',
            ['setUser'],
            [],
            '',
            false
        );
        $demand->expects($this->once())->method('setUser');
        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createUserRegistrationDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $registrationServiceMock = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['getCurrentFeUserObject'],
            [],
            '',
            false
        );
        $registrationServiceMock->expects($this->once())->method('getCurrentFeUserObject');
        $this->inject($this->subject, 'registrationService', $registrationServiceMock);

        $registrationRepository = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findRegistrationsByUserRegistrationDemand'],
            [],
            '',
            false
        );
        $registrationRepository->expects($this->once())->method('findRegistrationsByUserRegistrationDemand')
            ->will($this->returnValue($registrations));
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('registrations', $registrations);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}
