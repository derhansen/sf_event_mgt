<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\AdministrationController;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class AdministrationControllerTest extends UnitTestCase
{
    protected AdministrationController $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            AdministrationController::class,
            ['redirect', 'addFlashMessage', 'redirectToUri', 'getLanguageService', 'initModuleTemplateAndReturnResponse'],
            [],
            '',
            false
        );
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function initializeActionAssignsPid(): void
    {
        $this->subject->_set('pid', 1);
        $this->subject->_set('request', $this->createMock(Request::class));
        $this->subject->initializeAction();
        self::assertSame(0, $this->subject->_get('pid'));
    }

    #[Test]
    public function initializeListActionSetsDefaultDateFormatIfEmpty(): void
    {
        $settings = [
            'search' => [],
        ];

        $this->subject->_set('settings', $settings);
        $this->subject->initializeListAction();

        self::assertEquals('H:i d-m-Y', $this->subject->_get('settings')['search']['dateFormat']);
    }

    #[Test]
    public function checkEventAccessReturnsFalseIfNoEventAccess(): void
    {
        $event = new Event();

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(null);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        self::assertFalse($this->subject->checkEventAccess($event));
    }
}
