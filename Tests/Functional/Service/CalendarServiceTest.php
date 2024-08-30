<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Service;

use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Service\CalendarService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class CalendarServiceTest extends FunctionalTestCase
{
    protected EventRepository $eventRepository;
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_calendarservice.csv');

        $this->eventRepository = $this->getContainer()->get(EventRepository::class);
    }

    /**
     * Test, if events for the 27.10.2024 (date with timeshift) will be returned in the array
     */
    #[Test]
    public function getCalendarArrayReturnsArrayWithExpectedEventOnADayWithTimeshiftOctober(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $events = $this->eventRepository->findDemanded($demand);

        $calendarService = GeneralUtility::makeInstance(CalendarService::class);
        $calendarArray = $calendarService->getCalendarArray(10, 2024, mktime(0, 0, 0, 10, 27, 2024), 1, $events);
        self::assertCount(2, $calendarArray[43][6]['events']);
    }

    /**
     * Test, if events for the 31.03.2024 (date with timeshift) will be returned in the array
     */
    #[Test]
    public function getCalendarArrayReturnsArrayWithExpectedEventOnADayWithTimeshiftMarch(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $events = $this->eventRepository->findDemanded($demand);

        $calendarService = GeneralUtility::makeInstance(CalendarService::class);
        $calendarArray = $calendarService->getCalendarArray(3, 2024, mktime(0, 0, 0, 3, 31, 2024), 1, $events);
        self::assertCount(1, $calendarArray[13][6]['events']);
    }
}
