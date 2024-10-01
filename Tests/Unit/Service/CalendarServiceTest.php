<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Service\CalendarService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CalendarServiceTest extends UnitTestCase
{
    protected CalendarService $subject;

    protected function setUp(): void
    {
        $this->subject = new CalendarService();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if the calendar array returns an expected amount of weeks for a given date
     */
    #[Test]
    public function getCalendarArrayReturnsExpectedAmountOfWeeksForGivenDate(): void
    {
        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 1);
        self::assertCount(6, $calendarArray);
    }

    /**
     * Test if the calendar array index contains week numbers
     */
    #[Test]
    public function getCalendarArrayHasWeekNumbersAsIndex(): void
    {
        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 1);
        self::assertArrayHasKey(52, $calendarArray);
        self::assertArrayHasKey(1, $calendarArray);
        self::assertArrayHasKey(2, $calendarArray);
        self::assertArrayHasKey(3, $calendarArray);
        self::assertArrayHasKey(4, $calendarArray);
        self::assertArrayHasKey(5, $calendarArray);
    }

    /**
     * Test, if the first weekday of the calendar is a sunday if the first day of week setting is set to sunday
     */
    #[Test]
    public function getCalendarArrayRespectsFirstDayOfWeekParameter(): void
    {
        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 0);
        self::assertEquals(date('w', $calendarArray[52][0]['timestamp']), 0);
    }

    /**
     * Test, if isCurrentDay is set
     */
    #[Test]
    public function getCalendarArraySetsIsCurrentDay(): void
    {
        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 2, 2017), 1);
        self::assertTrue($calendarArray[1][0]['isCurrentDay']);
    }

    /**
     * Test, if an event for the 02.01.2017 will be returned in the array
     */
    #[Test]
    public function getCalendarArrayReturnsArrayWithEventForOneDay(): void
    {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getStartdate')->willReturn(
            DateTime::createFromFormat('d.m.Y', sprintf('2.%s.%s', 1, 2017))->setTime(10, 0, 0)
        );
        $mockEvent->expects(self::any())->method('getEnddate')->willReturn(
            DateTime::createFromFormat('d.m.Y', sprintf('2.%s.%s', 1, 2017))->setTime(12, 0, 0)
        );

        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 1, [$mockEvent]);
        self::assertCount(1, $calendarArray[1][0]['events']);
    }

    /**
     * Test, if an event for the 02.01.2017 to 04.01.2017 will be returned in the array
     */
    #[Test]
    public function getCalendarArrayReturnsArrayWithEventForMultipleDays(): void
    {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getStartdate')->willReturn(
            DateTime::createFromFormat('d.m.Y', sprintf('2.%s.%s', 1, 2017))->setTime(10, 0, 0)
        );
        $mockEvent->expects(self::any())->method('getEnddate')->willReturn(
            DateTime::createFromFormat('d.m.Y', sprintf('4.%s.%s', 1, 2017))->setTime(12, 0, 0)
        );

        $calendarArray = $this->subject->getCalendarArray(1, 2017, mktime(0, 0, 0, 1, 1, 2017), 1, [$mockEvent]);
        self::assertCount(1, $calendarArray[1][0]['events']);
        self::assertCount(1, $calendarArray[1][1]['events']);
        self::assertCount(1, $calendarArray[1][2]['events']);
    }

    public static function calendarDateRangeDataProvider(): array
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
                    'lastDayOfCalendar' => strtotime('05.02.2017'),
                ],
            ],
            'january-2017-first-day-of-week-sunday' => [
                1,
                2017,
                0,
                [
                    'firstDayOfMonth' => strtotime('01.01.2017'),
                    'lastDayOfMonth' => strtotime('31.01.2017'),
                    'firstDayOfCalendar' => strtotime('01.01.2017'),
                    'lastDayOfCalendar' => strtotime('04.02.2017'),
                ],
            ],
            'december-2017-first-day-of-week-monday' => [
                12,
                2017,
                1,
                [
                    'firstDayOfMonth' => strtotime('01.12.2017'),
                    'lastDayOfMonth' => strtotime('31.12.2017'),
                    'firstDayOfCalendar' => strtotime('27.11.2017'),
                    'lastDayOfCalendar' => strtotime('31.12.2017'),
                ],
            ],
            'december-2017-first-day-of-week-sunday' => [
                12,
                2017,
                0,
                [
                    'firstDayOfMonth' => strtotime('01.12.2017'),
                    'lastDayOfMonth' => strtotime('31.12.2017'),
                    'firstDayOfCalendar' => strtotime('26.11.2017'),
                    'lastDayOfCalendar' => strtotime('06.01.2018'),
                ],
            ],
            'october-2018-first-day-of-week-monday' => [
                10,
                2018,
                1,
                [
                    'firstDayOfMonth' => strtotime('01.10.2018'),
                    'lastDayOfMonth' => strtotime('31.10.2018'),
                    'firstDayOfCalendar' => strtotime('01.10.2018'),
                    'lastDayOfCalendar' => strtotime('04.11.2018'),
                ],
            ],
            'october-2018-first-day-of-week-sunday' => [
                10,
                2018,
                0,
                [
                    'firstDayOfMonth' => strtotime('01.10.2018'),
                    'lastDayOfMonth' => strtotime('31.10.2018'),
                    'firstDayOfCalendar' => strtotime('30.09.2018'),
                    'lastDayOfCalendar' => strtotime('03.11.2018'),
                ],
            ],
        ];
    }

    #[DataProvider('calendarDateRangeDataProvider')]
    #[Test]
    public function getCalendarDateRangeReturnsExpectedValues(
        int $month,
        int $year,
        int $firstDayOfWeek,
        array $expected
    ): void {
        $result = $this->subject->getCalendarDateRange($month, $year, $firstDayOfWeek);
        self::assertEquals($expected, $result);
    }

    public static function dateConfigDataProvider(): array
    {
        return [
            'january-2017-no-modifier' => [
                1,
                2017,
                '',
                [
                    'date' => DateTime::createFromFormat('d.m.Y', sprintf('1.%s.%s', 1, 2017))->setTime(0, 0, 0),
                    'month' => 1,
                    'year' => 2017,
                ],
            ],
            'january-2017-plus-one-month' => [
                1,
                2017,
                '+1 month',
                [
                    'date' => DateTime::createFromFormat('d.m.Y', sprintf('1.%s.%s', 2, 2017))->setTime(0, 0, 0),
                    'month' => 2,
                    'year' => 2017,
                ],
            ],
            'january-2017-minus-one-month' => [
                1,
                2017,
                '-1 month',
                [
                    'date' => DateTime::createFromFormat('d.m.Y', sprintf('1.%s.%s', 12, 2016))->setTime(0, 0, 0),
                    'month' => 12,
                    'year' => 2016,
                ],
            ],
        ];
    }

    #[DataProvider('dateConfigDataProvider')]
    #[Test]
    public function getDateConfigReturnsExpectedValues(int $month, int $year, string $modifier, array $expected): void
    {
        $result = $this->subject->getDateConfig($month, $year, $modifier);
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function getWeekConfigReturnsExpectedValue(): void
    {
        $firstDayOfWeek = DateTime::createFromFormat('d.m.Y', '3.1.2022')->setTime(0, 0);

        $expected = [
            'previous' => [
                'weeknumber' => 52,
                'year' => 2021,
            ],
            'current' => [
                'weeknumber' => 1,
                'year' => 2022,
            ],
            'next' => [
                'weeknumber' => 2,
                'year' => 2022,
            ],
        ];

        self::assertEquals($expected, $this->subject->getWeekConfig($firstDayOfWeek));
    }
}
