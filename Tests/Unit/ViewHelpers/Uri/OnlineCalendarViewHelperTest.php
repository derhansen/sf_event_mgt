<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Uri;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Location;
use DERHANSEN\SfEventMgt\ViewHelpers\Uri\OnlineCalendarViewHelper;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Test case for IsRequiredField viewhelper
 */
class OnlineCalendarViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var OnlineCalendarViewHelper
     */
    protected $viewHelper;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->viewHelper = new OnlineCalendarViewHelper();
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->viewHelper);
    }

    public function onlineCalendarViewHelperReturnsExpectedResultsDataProvider(): array
    {
        return [
            'google' => [
                'google',
                'https://www.google.com/calendar/render?action=TEMPLATE&text=A%20test%20event&dates=20210101T180000Z%2B0100%2F20210101T200000Z%2B0100&details=A%20description%20for%20the%20event'
            ],
            'outlook' => [
                'outlook',
                'https://outlook.live.com/calendar/0/deeplink/compose?subject=A%20test%20event&startdt=2021-01-01T18%3A00%3A00%2B0100&enddt=2021-01-01T20%3A00%3A00%2B0100&body=A%20description%20for%20the%20event&path=%2Fcalendar%2Faction%2Fcompose%26rru%3Daddevent'
            ],
            'office365' => [
                'office365',
                'https://outlook.office.com/calendar/0/deeplink/compose?subject=A%20test%20event&startdt=2021-01-01T18%3A00%3A00%2B0100&enddt=2021-01-01T20%3A00%3A00%2B0100&body=A%20description%20for%20the%20event&path=%2Fcalendar%2Faction%2Fcompose%26rru%3Daddevent'
            ],
            'yahoo' => [
                'yahoo',
                'https://calendar.yahoo.com/?title=A%20test%20event&st=20210101T180000Z%2B0100&et=20210101T200000Z%2B0100&desc=A%20description%20for%20the%20event&v=60'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider onlineCalendarViewHelperReturnsExpectedResultsDataProvider
     */
    public function onlineCalendarViewHelperReturnsExpectedResults(string $type, string $expected)
    {
        $location = new Location();
        $location->setTitle('A location');
        $location->setAddress('Street 123');
        $location->setZip('12345');
        $location->setCity('A City');
        $location->setCountry('A Country');

        $event = new Event();
        $event->setTitle('A test event');
        $event->setDescription('A description for the event');
        $event->setStartdate(new \DateTime('01.01.2021 18:00:00'));
        $event->setEnddate(new \DateTime('01.01.2021 20:00:00'));

        $result = $this->viewHelper::renderStatic(
            [
                'event' => $event,
                'type' => $type
            ],
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );
        self::assertEquals($expected, $result);
    }
}
