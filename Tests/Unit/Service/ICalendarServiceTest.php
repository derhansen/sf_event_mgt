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
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\ICalendarService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarServiceTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Service\ICalendarService
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->subject = new ICalendarService();
    }

    /**
     * Teardown
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getiCalendarContentAssignsVariablesToView()
    {
        $_SERVER['HTTP_HOST'] = 'myhostname.tld';

        $mockEvent = $this->getMockBuilder(Event::class)->getMock();

        $iCalendarView = $this->getMockBuilder(StandaloneView::class)
            ->disableOriginalConstructor()
            ->getMock();
        $iCalendarView->expects(self::once())->method('setFormat')->with('txt');
        $iCalendarView->expects(self::once())->method('assignMultiple')->with([
            'event' => $mockEvent,
            'typo3Host' => 'myhostname.tld'
        ]);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects(self::once())->method('get')->willReturn($iCalendarView);
        $this->inject($this->subject, 'objectManager', $objectManager);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->getMock();
        $fluidStandaloneService->expects(self::any())->method('getTemplateFolders')->willReturn([]);
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $this->subject->getiCalendarContent($mockEvent);
    }
}
