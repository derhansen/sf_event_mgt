<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\UserRegistrationController;
use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class UserRegistrationControllerTest extends UnitTestCase
{
    protected UserRegistrationController $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            UserRegistrationController::class,
            [
                'htmlResponse',
            ],
            [],
            '',
            false
        );
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if listAction assigns registrations to view
     *
     * @test
     */
    public function listActionFetchesRegistrationsFromRepositoryAndAssignsThemToView(): void
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

        $view = $this->createMock(TemplateView::class);
        $view->expects(self::any())->method('assign')->with('registrations', $registrations);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function detailActionThrowsExpectedExceptionIfRegistrationDoesNotBelongToFrontendUser(): void
    {
        $GLOBALS['TSFE'] = $this->createMock(TypoScriptFrontendController::class);
        $GLOBALS['TSFE']->fe_user = $this->createMock(FrontendUserAuthentication::class);
        $GLOBALS['LANG'] = $this->createMock(LanguageService::class);

        $request = $this->createMock(Request::class);
        $this->subject->_set('request', $request);

        $registration = new Registration();

        $this->expectExceptionCode(1671627320);
        $this->expectException(PropagateResponseException::class);

        $this->subject->detailAction($registration);
    }

    /**
     * @test
     */
    public function detailActionAssignsRegistrationToViewIfRegistrationBelongsToFrontendUser(): void
    {
        $GLOBALS['TSFE'] = $this->createMock(TypoScriptFrontendController::class);
        $GLOBALS['TSFE']->fe_user = $this->createMock(FrontendUserAuthentication::class);
        $GLOBALS['TSFE']->fe_user->user['uid'] = 1;
        $GLOBALS['LANG'] = $this->createMock(LanguageService::class);

        $request = $this->createMock(Request::class);
        $this->subject->_set('request', $request);

        $frontendUser = new FrontendUser();
        $frontendUser->_setProperty('uid', 1);
        $registration = new Registration();
        $registration->setFeUser($frontendUser);

        $view = $this->createMock(TemplateView::class);
        $view->expects(self::any())->method('assign')->with('registration', $registration);
        $this->subject->_set('view', $view);

        $this->subject->detailAction($registration);
    }
}
