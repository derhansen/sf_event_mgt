<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Service\CalendarService;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\CalendarServiceTest.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CalendarServiceTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Service\CalendarService
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new CalendarService();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if the calendar array returns an expected amount of weeks for a given date
     *
     * @test
     */
    public function getCalendarArrayReturnsExpectedAmountOfWeeksForGivenDate()
    {
        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 1);
        self::assertEquals(6, count($calendarArray));
    }

    /**
     * Test, if the first weekday of the calendar is a sunday if the first day of week setting is set to sunday
     *
     * @test
     */
    public function getCalendarArrayRespectsFirstDayOfWeekParameter()
    {
        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 0);
        self::assertEquals(date('w', $calendarArray[0][0]['timestamp']), 0);
    }

    /**
     * Test, if isCurrentDay is set
     *
     * @test
     */
    public function getCalendarArraySetsIsCurrentDay()
    {
        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 2, 2017), 1);
        self::assertTrue($calendarArray[1][0]['isCurrentDay']);
    }

    /**
     * Test, if an event for the 02.01.2017 will be returned in the array
     *
     * @test
     */
    public function getCalendarArrayReturnsArrayWithEventForOneDay()
    {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getStartdate')->willReturn(
            \DateTime::createFromFormat('d.m.Y', sprintf('2.%s.%s', 1, 2017))->setTime(10, 0, 0)
        );
        $mockEvent->expects(self::any())->method('getEnddate')->willReturn(
            \DateTime::createFromFormat('d.m.Y', sprintf('2.%s.%s', 1, 2017))->setTime(12, 0, 0)
        );

        $events = new ObjectStorage();
        $events->attach($mockEvent);

        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 1, $events);
        self::assertEquals(1, count($calendarArray[1][0]['events']));
    }

    /**
     * Test, if an event for the 02.01.2017 to 04.01.2017 will be returned in the array
     *
     * @test
     */
    public function getCalendarArrayReturnsArrayWithEventForMultipleDays()
    {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getStartdate')->willReturn(
            \DateTime::createFromFormat('d.m.Y', sprintf('2.%s.%s', 1, 2017))->setTime(10, 0, 0)
        );
        $mockEvent->expects(self::any())->method('getEnddate')->willReturn(
            \DateTime::createFromFormat('d.m.Y', sprintf('4.%s.%s', 1, 2017))->setTime(12, 0, 0)
        );

        $events = new ObjectStorage();
        $events->attach($mockEvent);

        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 1, $events);
        self::assertEquals(1, count($calendarArray[1][0]['events']));
        self::assertEquals(1, count($calendarArray[1][1]['events']));
        self::assertEquals(1, count($calendarArray[1][2]['events']));
    }

    /**
     * Data provider for getCalendarDateRangeReturnsExpectedValues
     *
     * @return array
     */
    public function calendarDateRangeDataProvider()
    {
        return [
            'january-2017-first-day-of-week-monday' => [
                1,
                2017,
                1,
                [
                    'firstDayOfMonth' => strtotime('01.01.2017'),
                    'lastDayOfMonth' => strtotime('31.01.2017'),
                    'firstDayOfCalendar' => strtotime('26.12.2016'),
                    'lastDayOfCalendar' => strtotime('05.02.2017')
                ]
            ],
            'january-2017-first-day-of-week-sunday' => [
                1,
                2017,
                0,
                [
                    'firstDayOfMonth' => strtotime('01.01.2017'),
                    'lastDayOfMonth' => strtotime('31.01.2017'),
                    'firstDayOfCalendar' => strtotime('01.01.2017'),
                    'lastDayOfCalendar' => strtotime('04.02.2017')
                ]
            ],
            'december-2017-first-day-of-week-monday' => [
                12,
                2017,
                1,
                [
                    'firstDayOfMonth' => strtotime('01.12.2017'),
                    'lastDayOfMonth' => strtotime('31.12.2017'),
                    'firstDayOfCalendar' => strtotime('27.11.2017'),
                    'lastDayOfCalendar' => strtotime('31.12.2017')
                ]
            ],
            'december-2017-first-day-of-week-sunday' => [
                12,
                2017,
                0,
                [
                    'firstDayOfMonth' => strtotime('01.12.2017'),
                    'lastDayOfMonth' => strtotime('31.12.2017'),
                    'firstDayOfCalendar' => strtotime('26.11.2017'),
                    'lastDayOfCalendar' => strtotime('06.01.2018')
                ]
            ],
            'october-2018-first-day-of-week-monday' => [
                10,
                2018,
                1,
                [
                    'firstDayOfMonth' => strtotime('01.10.2018'),
                    'lastDayOfMonth' => strtotime('31.10.2018'),
                    'firstDayOfCalendar' => strtotime('01.10.2018'),
                    'lastDayOfCalendar' => strtotime('04.11.2018')
                ]
            ],
            'october-2018-first-day-of-week-sunday' => [
                10,
                2018,
                0,
                [
                    'firstDayOfMonth' => strtotime('01.10.2018'),
                    'lastDayOfMonth' => strtotime('31.10.2018'),
                    'firstDayOfCalendar' => strtotime('30.09.2018'),
                    'lastDayOfCalendar' => strtotime('03.11.2018')
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider calendarDateRangeDataProvider
     * @param mixed $month
     * @param mixed $year
     * @param mixed $firstDayOfWeek
     * @param mixed $expected
     */
    public function getCalendarDateRangeReturnsExpectedValues($month, $year, $firstDayOfWeek, $expected)
    {
        $result = $this->subject->getCalendarDateRange($month, $year, $firstDayOfWeek);
        self::assertEquals($expected, $result);
    }

    /**
     * Data Provider for getDateConfigReturnsExpectedValues
     *
     * @return array
     */
    public function dateConfigDataProvider()
    {
        return [
            'january-2017-no-modifier' => [
                1,
                2017,
                '',
                [
                    'date' => \DateTime::createFromFormat('d.m.Y', sprintf('1.%s.%s', 1, 2017))->setTime(0, 0, 0),
                    'month' => 1,
                    'year' => 2017
                ]
            ],
            'january-2017-plus-one-month' => [
                1,
                2017,
                '+1 month',
                [
                    'date' => \DateTime::createFromFormat('d.m.Y', sprintf('1.%s.%s', 2, 2017))->setTime(0, 0, 0),
                    'month' => 2,
                    'year' => 2017
                ]
            ],
            'january-2017-minus-one-month' => [
                1,
                2017,
                '-1 month',
                [
                    'date' => \DateTime::createFromFormat('d.m.Y', sprintf('1.%s.%s', 12, 2016))->setTime(0, 0, 0),
                    'month' => 12,
                    'year' => 2016
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider dateConfigDataProvider
     * @param mixed $month
     * @param mixed $year
     * @param mixed $modifier
     * @param mixed $expected
     */
    public function getDateConfigReturnsExpectedValues($month, $year, $modifier, $expected)
    {
        $result = $this->subject->getDateConfig($month, $year, $modifier);
        self::assertEquals($expected, $result);
    }
}
