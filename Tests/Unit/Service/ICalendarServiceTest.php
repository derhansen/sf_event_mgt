<?php

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
use Prophecy\Argument;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\ICalendarService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarServiceTest extends UnitTestCase
{
    /**
     * @var ICalendarService
     */
    protected $subject;

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

        $eventProphecy = $this->prophesize(Event::class)->reveal();

        $standAloneView = $this->prophesize(StandaloneView::class);
        $standAloneView->setLayoutRootPaths([])->shouldBeCalled();
        $standAloneView->setPartialRootPaths([])->shouldBeCalled();
        $standAloneView->setTemplateRootPaths([])->shouldBeCalled();
        $standAloneView->setTemplate('Event/ICalendar.txt')->shouldBeCalled();
        $standAloneView->setFormat('txt')->shouldBeCalled();
        $standAloneView->assignMultiple(
            [
                'event' => $eventProphecy,
                'typo3Host' => 'myhostname.tld'
            ]
        )->shouldbeCalled();
        $standAloneView->render()->willReturn('foo');
        GeneralUtility::addInstance(StandaloneView::class, $standAloneView->reveal());

        $fluidStandaloneService = $this->prophesize(FluidStandaloneService::class);
        $fluidStandaloneService->getTemplateFolders(Argument::any())->willReturn([]);
        $this->subject->injectFluidStandaloneService($fluidStandaloneService->reveal());

        $this->subject->getiCalendarContent($eventProphecy);
    }
}
