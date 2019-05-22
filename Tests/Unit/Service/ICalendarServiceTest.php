<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new ICalendarService();
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
     * @test
     * @runInSeparateProcess
     * @return void
     */
    public function downloadiCalendarFileReturnsExpectedHeaders()
    {
        $mockedICalendarService = $this->getAccessibleMock(ICalendarService::class, ['getiCalendarContent']);
        $mockedICalendarService->expects($this->once())->method('getiCalendarContent')
            ->will($this->returnValue('ICAL-CONTENT')); // 12 Chars - must match in Content-Length header

        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects($this->once())->method('getUid')
            ->will($this->returnValue(1)); // UID 1 - must be in event ics filename

        $mockedICalendarService->downloadiCalendarFile($mockEvent);

        $headers = xdebug_get_headers();
        $this->assertContains('Content-Disposition: attachment; filename="event1.ics"', $headers);
        $this->assertContains('Content-Length: 12', $headers);
    }

    /**
     * @test
     * @return void
     */
    public function getiCalendarContentAssignsVariablesToView()
    {
        $_SERVER['HTTP_HOST'] = 'myhostname.tld';

        $mockEvent = $this->getMockBuilder(Event::class)->getMock();

        $iCalendarView = $this->getMockBuilder(StandaloneView::class)
            ->disableOriginalConstructor()
            ->getMock();
        $iCalendarView->expects($this->once())->method('setFormat')->with('txt');
        $iCalendarView->expects($this->once())->method('assignMultiple')->with([
            'event' => $mockEvent,
            'typo3Host' => 'myhostname.tld'
        ]);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects($this->once())->method('get')->will($this->returnValue($iCalendarView));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->getMock();
        $fluidStandaloneService->expects($this->any())->method('getTemplateFolders')->will($this->returnValue([]));
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $this->subject->getiCalendarContent($mockEvent);
    }
}
