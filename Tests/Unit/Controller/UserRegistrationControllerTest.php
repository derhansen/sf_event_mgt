<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\UserRegistrationController;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\View\TemplateView;
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
            ['dummy'],
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
     * Test if listAction assigns registrations to view
     *
     * @test
     */
    public function listActionFetchesRegistrationsFromRepositoryAndAssignsThemToView()
    {
        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $settings = ['settings'];
        $this->subject->_set('settings', $settings);

        $registrationServiceMock = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['getCurrentFeUserObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationServiceMock->expects(self::once())->method('getCurrentFeUserObject');
        $this->subject->injectRegistrationService($registrationServiceMock);

        $registrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->onlyMethods(['findRegistrationsByUserRegistrationDemand'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('findRegistrationsByUserRegistrationDemand')
            ->willReturn($registrations);
        $this->subject->injectRegistrationRepository($registrationRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::any())->method('assign')->with('registrations', $registrations);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }
}
