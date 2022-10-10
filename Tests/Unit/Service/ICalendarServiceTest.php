<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use DERHANSEN\SfEventMgt\Service\ICalendarService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\ICalendarService.
 */
class ICalendarServiceTest extends UnitTestCase
{
    protected ICalendarService $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new ICalendarService();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getiCalendarContentAssignsVariablesToView()
    {
        $_SERVER['HTTP_HOST'] = 'myhostname.tld';

        $eventMock = $this->getMockBuilder(Event::class)->getMock();

        $standAloneView = $this->getMockBuilder(StandaloneView::class)->disableOriginalConstructor()->getMock();
        $standAloneView->expects(self::once())->method('setLayoutRootPaths');
        $standAloneView->expects(self::once())->method('setPartialRootPaths');
        $standAloneView->expects(self::once())->method('setTemplateRootPaths');
        $standAloneView->expects(self::once())->method('setTemplate')->with('Event/ICalendar.txt');
        $standAloneView->expects(self::once())->method('setFormat')->with('txt');
        $standAloneView->expects(self::once())->method('assignMultiple')->with(
            [
                'event' => $eventMock,
                'typo3Host' => 'myhostname.tld',
            ]
        );
        GeneralUtility::addInstance(StandaloneView::class, $standAloneView);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->disableOriginalConstructor()->getMock();
        $fluidStandaloneService->expects(self::any())->method('getTemplateFolders')->willReturn([]);
        $this->subject->injectFluidStandaloneService($fluidStandaloneService);

        $this->subject->getiCalendarContent($eventMock);
    }
}
