<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\SpeakerRepository;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class EventRepositoryTest extends FunctionalTestCase
{
    protected EventRepository $eventRepository;
    protected LocationRepository $locationRepository;
    protected SpeakerRepository $speakerRepository;
    protected OrganisatorRepository $organisatorRepository;

    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = $this->getContainer()->get(EventRepository::class);
        $this->locationRepository = $this->getContainer()->get(LocationRepository::class);
        $this->organisatorRepository = $this->getContainer()->get(OrganisatorRepository::class);
        $this->speakerRepository = $this->getContainer()->get(SpeakerRepository::class);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_storagepage.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_displaymode.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbydate.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbytitle.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbytopevent.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbylocation.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbyyearmonthday.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbyorganisator.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbyspeaker.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbycategory.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_ignoreenablefields.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/events_findbysearchdemand.csv');

        $request = (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $GLOBALS['TYPO3_REQUEST'] = $request;
    }

    public static function findDemandedRecordsByStoragePageDataProvider(): array
    {
        return [
            'pid is string and valid' => [
                '3',
                3,
            ],
            'pid is zero' => [
                '0',
                0,
            ],
            'pid not set' => [
                '',
                51,
            ],
        ];
    }

    /**
     * Test if storagePage restriction in demand works
     * @param mixed $pid
     * @param int $expected
     */
    #[DataProvider('findDemandedRecordsByStoragePageDataProvider')]
    #[Test]
    public function findDemandedRecordsByStoragePage(string $pid, int $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage($pid);
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame($expected, $events->count());
    }

    /**
     * Test if displayMode 'all' restriction in demand works
     */
    #[Test]
    public function findDemandedRecordsByDisplayModeAll(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $demand->setDisplayMode('all');
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(5, $events->count());
    }

    /**
     * Test if displayMode 'past' restriction in demand works.
     */
    #[Test]
    public function findDemandedRecordsByDisplayModePast(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $demand->setDisplayMode('past');
        $demand->setCurrentDateTime(new \DateTime('30.05.2014'));
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(1, $events->count());
    }

    /**
     * Test if displayMode 'past' restriction in demand works and ignores events with no enddate
     */
    #[Test]
    public function findDemandedRecordsByDisplayModePastForEventWithNoEnddate(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $demand->setDisplayMode('past');
        $demand->setCurrentDateTime(new \DateTime('01.06.2014 11:00'));
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());
    }

    /**
     * Test if displayMode 'future' restriction in demand works
     */
    #[Test]
    public function findDemandedRecordsByDisplayModeFuture(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $demand->setDisplayMode('future');
        $demand->setCurrentDateTime(new \DateTime('30.05.2014 14:00:00'));
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(3, $events->count());
    }

    /**
     * Test if displayMode 'current_future' restriction in demand works
     */
    #[Test]
    public function findDemandedRecordsByDisplayModeCurrentFuture(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $demand->setDisplayMode('current_future');
        $demand->setCurrentDateTime(new \DateTime('02.06.2014 08:00:00'));
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(1, $events->count());
    }

    /**
     * Test if displayMode 'time_restriction' in demand works
     */
    #[Test]
    public function findDemandedRecordsByDisplayModeTimeRestriction(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('130');
        $demand->setDisplayMode('time_restriction');
        $demand->setTimeRestrictionLow('2021-02-01');
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());

        $eventIds = [$events[0]->getUid(), $events[1]->getUid()];
        sort($eventIds);

        self::assertSame('132,133', implode(',', $eventIds));

        $demand->setTimeRestrictionHigh('2021-02-28 23:59:59');
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(1, $events->count());

        $demand->setIncludeCurrent(true);
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());

        $eventIds = [$events[0]->getUid(), $events[1]->getUid()];
        sort($eventIds);

        self::assertSame('131,132', implode(',', $eventIds));
    }

    public static function findDemandedRecordsByCategoryWithConjunctionDataProvider(): array
    {
        return [
            'no conjuction' => [
                '5',
                '',
                false,
                5,
            ],
            'category 5 with AND - no subcategories' => [
                '5',
                'and',
                false,
                4,
            ],
            'category 5,6 with AND - no subcategories' => [
                '5,6',
                'and',
                false,
                3,
            ],
            'category 5,6,7 with AND - no subcategories' => [
                '5,6,7',
                'and',
                false,
                2,
            ],
            'category 5,6,7,8 with AND - no subcategories' => [
                '5,6,7,8',
                'and',
                false,
                1,
            ],
            'category 5,6 with OR - no subcategories' => [
                '5,6',
                'or',
                false,
                4,
            ],
            'category 7,8 with OR - no subcategories' => [
                '7,8',
                'or',
                false,
                2,
            ],
            'category 7,8 with NOTAND - no subcategories' => [
                '7,8',
                'notand',
                false,
                4,
            ],
            'category 7,8 with NOTOR - no subcategories' => [
                '7,8',
                'notor',
                false,
                3,
            ],
            'category 8 with AND - with subcategories' => [
                '8',
                'or',
                true,
                2,
            ],
        ];
    }

    /**
     * Test if category restiction with conjunction works
     *
     * @param mixed $category
     * @param mixed $conjunction
     * @param mixed $includeSub
     * @param mixed $expected
     */
    #[DataProvider('findDemandedRecordsByCategoryWithConjunctionDataProvider')]
    #[Test]
    public function findDemandedRecordsByCategoryWithConjunction($category, $conjunction, $includeSub, $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('90');
        $demand->setCategoryConjunction($conjunction);
        $demand->setCategory($category);
        $demand->setIncludeSubcategories($includeSub);
        self::assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    public static function findDemandedRecordsByLocationDataProvider(): array
    {
        return [
            'location 1' => [
                1,
                1,
            ],
            'location 2' => [
                2,
                1,
            ],
            'location 3' => [
                3,
                0,
            ],
        ];
    }

    /**
     * Test if location restriction works
     *
     * @param mixed $locationUid
     * @param mixed $expected
     */
    #[DataProvider('findDemandedRecordsByLocationDataProvider')]
    #[Test]
    public function findDemandedRecordsByLocation($locationUid, $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('40');

        $location = $this->locationRepository->findByUid($locationUid);
        $demand->setLocation($location);
        self::assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    public static function findDemandedRecordsByLocationCityDataProvider(): array
    {
        return [
            'City: Flensburg' => [
                'Flensburg',
                2,
            ],
            'City: Hamburg' => [
                'Hamburg',
                1,
            ],
        ];
    }

    /**
     * Test if location.city restriction works
     */
    #[DataProvider('findDemandedRecordsByLocationCityDataProvider')]
    #[Test]
    public function findDemandedRecordsByLocationCity(string $locationCity, int $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('50');

        $demand->setLocationCity($locationCity);
        self::assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    public static function findDemandedRecordsByLocationCountryDataProvider(): array
    {
        return [
            'Country: Germany' => [
                'Germany',
                2,
            ],
            'Country: Denmark' => [
                'Denmark',
                1,
            ],
        ];
    }

    /**
     * Test if location.country restriction works
     */
    #[DataProvider('findDemandedRecordsByLocationCountryDataProvider')]
    #[Test]
    public function findDemandedRecordsByLocationCountry(string $locationCountry, int $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('60');

        $demand->setLocationCountry($locationCountry);
        self::assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    /**
     * Test if startDate restriction in demand works
     */
    #[Test]
    public function findSearchDemandedRecordsByStartDate(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('6');

        $searchDemand = new SearchDemand();
        $searchDemand->setStartDate(new \DateTime('30.05.2014 14:00:00'));
        $demand->setSearchDemand($searchDemand);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());
    }

    /**
     * Test if endDate restriction in demand works
     */
    #[Test]
    public function findSearchDemandedRecordsByEndDate(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('7');

        $searchDemand = new SearchDemand();
        $searchDemand->setEndDate(new \DateTime('02.06.2014 08:00'));
        $demand->setSearchDemand($searchDemand);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());
    }

    /**
     * Test if title restriction in demand works
     */
    #[Test]
    public function findSearchDemandedRecordsByFieldTitle(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('8');

        $searchDemand = new SearchDemand();
        $searchDemand->setSearch('TYPO3 CMS course');
        $searchDemand->setFields('title');
        $demand->setSearchDemand($searchDemand);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());
    }

    public static function findDemandedRecordsByTopEventDataProvider(): array
    {
        return [
            'noRestriction' => [
                0,
                2,
            ],
            'onlyTopEvents' => [
                1,
                1,
            ],
            'exceptTopEvents' => [
                2,
                1,
            ],
        ];
    }

    /**
     * Test if top event restriction in demand works
     *
     * @param mixed $topEventRestriction
     * @param mixed $expected
     */
    #[DataProvider('findDemandedRecordsByTopEventDataProvider')]
    #[Test]
    public function findDemandedRecordsByTopEvent($topEventRestriction, $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('30');

        $demand->setTopEventRestriction($topEventRestriction);
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame($expected, $events->count());
    }

    public static function findDemandedRecordsByOrderingDataProvider(): array
    {
        return [
            'noSorting' => [
                '',
                '',
                'Test2',
            ],
            'titleAsc' => [
                'title',
                'asc',
                'Test1',
            ],
            'titleDesc' => [
                'title',
                'desc',
                'Test5',
            ],
            'startdateAsc' => [
                'startdate',
                'asc',
                'Test2',
            ],
            'startdateDesc' => [
                'startdate',
                'desc',
                'Test5',
            ],
            'enddateAsc' => [
                'enddate',
                'asc',
                'Test5',
            ],
            'enddateDesc' => [
                'enddate',
                'desc',
                'Test4',
            ],
        ];
    }

    /**
     * Test if ordering for findDemanded works
     *
     * @param mixed $orderField
     * @param mixed $orderDirection
     * @param mixed $expected
     */
    #[DataProvider('findDemandedRecordsByOrderingDataProvider')]
    #[Test]
    public function findDemandedRecordsByOrdering($orderField, $orderDirection, $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $demand->setDisplayMode('all');
        $demand->setOrderField($orderField);
        $demand->setOrderFieldAllowed($orderField);
        $demand->setOrderDirection($orderDirection);
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame($expected, $events->getFirst()->getTitle());
    }

    /**
     * Test if ordering for findDemanded works but ignores unknown order by fields
     */
    #[Test]
    public function findDemandedRecordsByOrderingIgnoresUnknownOrderField(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $demand->setDisplayMode('all');
        $demand->setOrderField('unknown_field');
        $demand->setOrderFieldAllowed('title');
        $demand->setOrderDirection('asc');
        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame('Test2', $events->getFirst()->getTitle());
    }

    /**
     * Test if limit restriction works
     */
    #[Test]
    public function findDemandedRecordsSetsLimit(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('4');
        $demand->setDisplayMode('all');
        $demand->setQueryLimit(2);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());
    }

    /**
     * Test if year restriction works
     */
    #[Test]
    public function findDemandedRecordsByYear(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('70');
        $demand->setDisplayMode('all');
        $demand->setYear(2018);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());
    }

    /**
     * Test if month restriction works
     */
    #[Test]
    public function findDemandedRecordsByMonth(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('70');
        $demand->setDisplayMode('all');
        $demand->setYear(2017);
        $demand->setMonth(10);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());
    }

    /**
     * Test if month restriction works, when start/enddate oi event span more than one month
     */
    #[Test]
    public function findDemandedRecordsByMonthWithStartdateInGivenMonth(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('70');
        $demand->setDisplayMode('all');
        $demand->setYear(2018);
        $demand->setMonth(2);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(75, $events->getFirst()->getUid());
    }

    /**
     * Test if month restriction works, when start/enddate oi event span more than one month
     */
    #[Test]
    public function findDemandedRecordsByMonthWithEnddateInGivenMonth(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('70');
        $demand->setDisplayMode('all');
        $demand->setYear(2018);
        $demand->setMonth(3);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(75, $events->getFirst()->getUid());
    }

    /**
     * Test if day restriction works
     */
    #[Test]
    public function findDemandedRecordsByDay(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('70');
        $demand->setDisplayMode('all');
        $demand->setYear(2017);
        $demand->setMonth(10);
        $demand->setDay(1);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(2, $events->count());
    }

    /**
     * Test if day restriction works, when event spans multiple days and restriction is limited to a
     * day, which is between the event start- and enddate
     */
    #[Test]
    public function findDemandedRecordsByDayForEventSpanningDateRange(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('70');
        $demand->setDisplayMode('all');
        $demand->setYear(2017);
        $demand->setMonth(10);
        $demand->setDay(2);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(1, $events->count());
    }

    public static function findDemandedRecordsBySpeakerDataProvider(): array
    {
        return [
            'events with speaker 1' => [
                1,
                1,
            ],
            'events with speaker 2' => [
                2,
                2,
            ],
            'events with speaker 3' => [
                3,
                1,
            ],
        ];
    }

    /**
     * Test if speaker restriction works
     *
     * @param mixed $speakerUid
     * @param mixed $expected
     */
    #[DataProvider('findDemandedRecordsBySpeakerDataProvider')]
    #[Test]
    public function findDemandedRecordsBySpeaker($speakerUid, $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('100');

        $speaker = $this->speakerRepository->findByUid($speakerUid);
        $demand->setSpeaker($speaker);
        self::assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    /**
     * Test if startDate and endDate restriction in combination work
     */
    #[Test]
    public function findSearchDemandedRecordsByStartAndEndDate(): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('110');

        $searchDemand = new SearchDemand();
        $searchDemand->setStartDate(new \DateTime('01.07.2019 00:00:00'));
        $searchDemand->setEndDate(new \DateTime('04.08.2019 23:59:59'));
        $demand->setSearchDemand($searchDemand);

        $events = $this->eventRepository->findDemanded($demand);

        self::assertSame(1, $events->count());
    }

    public static function findDemandedRespectsIgnoreEnableFieldsDataProvider(): array
    {
        return [
            'ignoreEnableFields inactive' => [
                false,
                0,
            ],
            'ignoreEnableFields active' => [
                true,
                1,
            ],
        ];
    }

    /**
     * Test if ignoreEnableFields setting is respected
     *
     * @param bool $ignoreEnableFields
     * @param bool $expected
     */
    #[DataProvider('findDemandedRespectsIgnoreEnableFieldsDataProvider')]
    #[Test]
    public function findDemandedRespectsIgnoreEnableFields($ignoreEnableFields, $expected): void
    {
        $demand = new EventDemand();
        $demand->setStoragePage('120');
        $demand->setIgnoreEnableFields($ignoreEnableFields);
        self::assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }
}
