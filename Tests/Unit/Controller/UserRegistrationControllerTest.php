<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Service\RegistrationService;
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
        $mockController = $this->getMockBuilder('DERHANSEN\\SfEventMgt\\Controller\\UserRegistrationController')
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();

        $settings = [];
        $settings['userRegistration'] = [
            'displayMode' => 'all',
            'storagePage' => 1,
            'orderField' => 'event.title',
            'orderDirection' => 'asc',
        ];

        $mockDemand = $this->getMockBuilder('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\UserRegistrationDemand')
            ->getMock();
        $mockDemand->expects($this->at(0))->method('setDisplayMode')->with('all');
        $mockDemand->expects($this->at(1))->method('setStoragePage')->with(1);
        $mockDemand->expects($this->at(2))->method('setOrderField')->with('event.title');
        $mockDemand->expects($this->at(3))->method('setOrderDirection')->with('asc');

        $objectManager = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
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
        $demand = $this->getMockBuilder('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\UserRegistrationDemand')
            ->setMethods(['setUser'])
            ->disableOriginalConstructor()
            ->getMock();
        $demand->expects($this->once())->method('setUser');
        $registrations = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage')
            ->disableOriginalConstructor()
            ->getMock();

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createUserRegistrationDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $registrationServiceMock = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['getCurrentFeUserObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationServiceMock->expects($this->once())->method('getCurrentFeUserObject');
        $this->inject($this->subject, 'registrationService', $registrationServiceMock);

        $registrationRepository = $this->getMockBuilder(
            'DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository'
        )->setMethods(['findRegistrationsByUserRegistrationDemand'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects($this->once())->method('findRegistrationsByUserRegistrationDemand')
            ->will($this->returnValue($registrations));
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $view = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface')->getMock();
        $view->expects($this->at(0))->method('assign')->with('registrations', $registrations);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}
