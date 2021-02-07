<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\UserRegistrationController;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\UserRegistrationController
 */
class UserRegistrationControllerTest extends UnitTestCase
{
    /**
     * @var UserRegistrationController
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            UserRegistrationController::class,
            ['createUserRegistrationDemandObjectFromSettings'],
            [],
            '',
            false
        );
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if settings are used in UserRegistrationDemand object
     *
     * @test
     */
    public function createUserRegistrationDemandObjectFromSettingsTest()
    {
        $mockController = $this->getMockBuilder(UserRegistrationController::class)
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

        $mockDemand = $this->getMockBuilder(UserRegistrationDemand::class)
            ->getMock();
        $mockDemand->expects(self::at(0))->method('setDisplayMode')->with('all');
        $mockDemand->expects(self::at(1))->method('setStoragePage')->with(1);
        $mockDemand->expects(self::at(2))->method('setOrderField')->with('event.title');
        $mockDemand->expects(self::at(3))->method('setOrderDirection')->with('asc');

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects(self::any())->method('get')->willReturn($mockDemand);
        $this->inject($mockController, 'objectManager', $objectManager);

        $mockController->createUserRegistrationDemandObjectFromSettings($settings);
    }

    /**
     * Test if listAction assigns registrations to view
     *
     * @test
     */
    public function listActionFetchesRegistrationsFromRepositoryAndAssignsThemToView()
    {
        $demand = $this->getMockBuilder(UserRegistrationDemand::class)
            ->setMethods(['setUser'])
            ->disableOriginalConstructor()
            ->getMock();
        $demand->expects(self::once())->method('setUser');
        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createUserRegistrationDemandObjectFromSettings')
            ->with($settings)->willReturn($demand);

        $registrationServiceMock = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['getCurrentFeUserObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationServiceMock->expects(self::once())->method('getCurrentFeUserObject');
        $this->inject($this->subject, 'registrationService', $registrationServiceMock);

        $registrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->setMethods(['findRegistrationsByUserRegistrationDemand'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('findRegistrationsByUserRegistrationDemand')
            ->willReturn($registrations);
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::at(0))->method('assign')->with('registrations', $registrations);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}
