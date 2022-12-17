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
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ICalendarServiceTest extends UnitTestCase
{
    protected ICalendarService $subject;

    protected function setUp(): void
    {
        $this->subject = new ICalendarService();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getiCalendarContentAssignsVariablesToView(): void
    {
        $_SERVER['HTTP_HOST'] = 'myhostname.tld';

        $eventMock = $this->createMock(Event::class);
        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->disableOriginalConstructor()->getMock();
        $fluidStandaloneService->expects(self::once())->method('renderTemplate')->with(
            'Event/ICalendar.txt',
            [
                'event' => $eventMock,
                'typo3Host' => 'myhostname.tld',
            ],
            'SfEventMgt',
            'Pieventdetail',
            'txt'
        );
        $this->subject->injectFluidStandaloneService($fluidStandaloneService);

        $this->subject->getiCalendarContent($eventMock);
    }
}
