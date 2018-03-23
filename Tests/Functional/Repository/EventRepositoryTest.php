<?php
namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventRepositoryTest extends FunctionalTestCase
{
    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository */
    protected $eventRepository;

    /** @var \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository */
    protected $locationRepository;

    /** @var \DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository */
    protected $organisatorRepository;

    /** @var array */
    protected $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * Setup
     *
     * @throws \TYPO3\CMS\Core\Tests\Exception
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->eventRepository = $this->objectManager->get(EventRepository::class);
        $this->locationRepository = $this->objectManager->get(LocationRepository::class);
        $this->organisatorRepository = $this->objectManager->get(OrganisatorRepository::class);

        $this->importDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_event.xml');
    }

    /**
     * Test if startingpoint is working
     *
     * @test
     * @return void
     */
    public function findRecordsByUid()
    {
        $events = $this->eventRepository->findByUid(1);

        $this->assertSame($events->getTitle(), 'findRecordsByUid');
    }

    /**
     * Test if storagePage restriction in demand works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByStoragePage()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(3);
        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(3, $events->count());
    }

    /**
     * Test if displayMode 'all' restriction in demand works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByDisplayModeAll()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(4);
        $demand->setDisplayMode('all');
        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(4, $events->count());
    }

    /**
     * Test if displayMode 'past' restriction in demand works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByDisplayModePast()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(4);
        $demand->setDisplayMode('past');
        $demand->setCurrentDateTime(new \DateTime('30.05.2014'));
        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(1, $events->count());
    }

    /**
     * Test if displayMode 'future' restriction in demand works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByDisplayModeFuture()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(4);
        $demand->setDisplayMode('future');
        $demand->setCurrentDateTime(new \DateTime('30.05.2014 14:00:00'));
        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(2, $events->count());
    }

    /**
     * Test if displayMode 'current_future' restriction in demand works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByDisplayModeCurrentFuture()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(4);
        $demand->setDisplayMode('current_future');
        $demand->setCurrentDateTime(new \DateTime('02.06.2014 08:00:00'));
        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(1, $events->count());
    }

    /**
     * DataProvider for findDemandedRecordsByCategoryWithConjunction
     *
     * @return array
     */
    public function findDemandedRecordsByCategoryWithConjunctionDataProvider()
    {
        return [
            'no conjuction' => [
                '5',
                '',
                false,
                5
            ],
            'category 5 with AND - no subcategories' => [
                '5',
                'and',
                false,
                4
            ],
            'category 5,6 with AND - no subcategories' => [
                '5,6',
                'and',
                false,
                3
            ],
            'category 5,6,7 with AND - no subcategories' => [
                '5,6,7',
                'and',
                false,
                2
            ],
            'category 5,6,7,8 with AND - no subcategories' => [
                '5,6,7,8',
                'and',
                false,
                1
            ],
            'category 5,6 with OR - no subcategories' => [
                '5,6',
                'or',
                false,
                4
            ],
            'category 7,8 with OR - no subcategories' => [
                '7,8',
                'or',
                false,
                2
            ],
            'category 7,8 with NOTAND - no subcategories' => [
                '7,8',
                'notand',
                false,
                4
            ],
            'category 7,8 with NOTOR - no subcategories' => [
                '7,8',
                'notor',
                false,
                3
            ],
            'category 8 with AND - with subcategories' => [
                '8',
                'or',
                true,
                2
            ],
        ];
    }

    /**
     * Test if category restiction with conjunction works
     *
     * @dataProvider findDemandedRecordsByCategoryWithConjunctionDataProvider
     * @test
     * @param mixed $category
     * @param mixed $conjunction
     * @param mixed $includeSub
     * @param mixed $expected
     * @return void
     */
    public function findDemandedRecordsByCategoryWithConjunction($category, $conjunction, $includeSub, $expected)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(90);
        $demand->setCategoryConjunction($conjunction);
        $demand->setCategory($category);
        $demand->setIncludeSubcategories($includeSub);
        $this->assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    /**
     * DataProvider for findDemandedRecordsByLocation
     *
     * @return array
     */
    public function findDemandedRecordsByLocationDataProvider()
    {
        return [
            'location 1' => [
                1,
                1
            ],
            'location 2' => [
                2,
                1
            ],
            'location 3' => [
                3,
                0
            ]
        ];
    }

    /**
     * Test if location restriction works
     *
     * @dataProvider findDemandedRecordsByLocationDataProvider
     * @test
     * @param mixed $locationUid
     * @param mixed $expected
     * @return void
     */
    public function findDemandedRecordsByLocation($locationUid, $expected)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(40);

        $location = $this->locationRepository->findByUid($locationUid);
        $demand->setLocation($location);
        $this->assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    /**
     * DataProvider for findDemandedRecordsByLocationCity
     *
     * @return array
     */
    public function findDemandedRecordsByLocationCityDataProvider()
    {
        return [
            'City: Flensburg' => [
                'Flensburg',
                2
            ],
            'City: Hamburg' => [
                'Hamburg',
                1
            ]
        ];
    }

    /**
     * Test if location.city restriction works
     *
     * @dataProvider findDemandedRecordsByLocationCityDataProvider
     * @test
     * @param mixed $locationCity
     * @param mixed $expected
     * @return void
     */
    public function findDemandedRecordsByLocationCity($locationCity, $expected)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(50);

        $demand->setLocationCity($locationCity);
        $this->assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    /**
     * DataProvider for findDemandedRecordsByLocationCountry
     *
     * @return array
     */
    public function findDemandedRecordsByLocationCountryDataProvider()
    {
        return [
            'Country: Germany' => [
                'Germany',
                2
            ],
            'Country: Denmark' => [
                'Denmark',
                1
            ]
        ];
    }

    /**
     * Test if location.country restriction works
     *
     * @dataProvider findDemandedRecordsByLocationCountryDataProvider
     * @test
     * @param mixed $locationCountry
     * @param mixed $expected
     * @return void
     */
    public function findDemandedRecordsByLocationCountry($locationCountry, $expected)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(60);

        $demand->setLocationCountry($locationCountry);
        $this->assertSame($expected, $this->eventRepository->findDemanded($demand)->count());
    }

    /**
     * Test if startDate restriction in demand works
     *
     * @test
     * @return void
     */
    public function findSearchDemandedRecordsByStartDate()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(6);

        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand $searchDemand */
        $searchDemand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\SearchDemand');
        $searchDemand->setStartDate(new \DateTime('30.05.2014 14:00:00'));
        $demand->setSearchDemand($searchDemand);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(2, $events->count());
    }

    /**
     * Test if endDate restriction in demand works
     *
     * @test
     * @return void
     */
    public function findSearchDemandedRecordsByEndDate()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(7);

        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand $searchDemand */
        $searchDemand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\SearchDemand');
        $searchDemand->setEndDate(new \DateTime('02.06.2014 08:00'));
        $demand->setSearchDemand($searchDemand);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(2, $events->count());
    }

    /**
     * Test if title restriction in demand works
     *
     * @test
     * @return void
     */
    public function findSearchDemandedRecordsByFieldTitle()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(8);

        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand $searchDemand */
        $searchDemand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\SearchDemand');
        $searchDemand->setSearch('TYPO3 CMS course');
        $searchDemand->setFields('title');
        $demand->setSearchDemand($searchDemand);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(2, $events->count());
    }

    /**
     * Data provider for findDemandedRecordsByTopEvent
     *
     * @return array
     */
    public function findDemandedRecordsByTopEventDataProvider()
    {
        return [
            'noRestriction' => [
                0,
                2
            ],
            'onlyTopEvents' => [
                1,
                1
            ],
            'exceptTopEvents' => [
                2,
                1
            ],
        ];
    }

    /**
     * Test if top event restriction in demand works
     *
     * @dataProvider findDemandedRecordsByTopEventDataProvider
     * @test
     * @param mixed $topEventRestriction
     * @param mixed $expected
     * @return void
     */
    public function findDemandedRecordsByTopEvent($topEventRestriction, $expected)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(30);

        $demand->setTopEventRestriction($topEventRestriction);
        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame($expected, $events->count());
    }

    /**
     * Data provider for findDemandedRecordsByTopEvent
     *
     * @return array
     */
    public function findDemandedRecordsByOrderingDataProvider()
    {
        return [
            'noSorting' => [
                '',
                '',
                'Test2'
            ],
            'titleAsc' => [
                'title',
                'asc',
                'Test1'
            ],
            'titleDesc' => [
                'title',
                'desc',
                'Test4'
            ],
            'startdateAsc' => [
                'startdate',
                'asc',
                'Test2'
            ],
            'startdateDesc' => [
                'startdate',
                'desc',
                'Test3'
            ],
            'enddateAsc' => [
                'enddate',
                'asc',
                'Test2'
            ],
            'enddateDesc' => [
                'enddate',
                'desc',
                'Test4'
            ],
        ];
    }

    /**
     * Test if ordering for findDemanded works
     *
     * @dataProvider findDemandedRecordsByOrderingDataProvider
     * @test
     * @param mixed $orderField
     * @param mixed $orderDirection
     * @param mixed $expected
     * @return void
     */
    public function findDemandedRecordsByOrdering($orderField, $orderDirection, $expected)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(4);
        $demand->setDisplayMode('all');
        $demand->setOrderField($orderField);
        $demand->setOrderFieldAllowed($orderField);
        $demand->setOrderDirection($orderDirection);
        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame($expected, $events->getFirst()->getTitle());
    }

    /**
     * Test if ordering for findDemanded works but ignores unknown order by fields
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByOrderingIgnoresUnknownOrderField()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(4);
        $demand->setDisplayMode('all');
        $demand->setOrderField('unknown_field');
        $demand->setOrderFieldAllowed('title');
        $demand->setOrderDirection('asc');
        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame('Test2', $events->getFirst()->getTitle());
    }

    /**
     * Test if limit restriction works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsSetsLimit()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(4);
        $demand->setDisplayMode('all');
        $demand->setQueryLimit(2);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(2, $events->count());
    }

    /**
     * Test if year restriction works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByYear()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(70);
        $demand->setDisplayMode('all');
        $demand->setYear(2018);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(2, $events->count());
    }

    /**
     * Test if month restriction works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByMonth()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(70);
        $demand->setDisplayMode('all');
        $demand->setYear(2017);
        $demand->setMonth(10);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(2, $events->count());
    }

    /**
     * Test if month restriction works, when start/enddate oi event span more than one month
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByMonthWithStartdateInGivenMonth()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(70);
        $demand->setDisplayMode('all');
        $demand->setYear(2018);
        $demand->setMonth(2);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(75, $events->getFirst()->getUid());
    }

    /**
     * Test if month restriction works, when start/enddate oi event span more than one month
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByMonthWithEnddateInGivenMonth()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(70);
        $demand->setDisplayMode('all');
        $demand->setYear(2018);
        $demand->setMonth(3);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(75, $events->getFirst()->getUid());
    }

    /**
     * Test if day restriction works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByDay()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(70);
        $demand->setDisplayMode('all');
        $demand->setYear(2017);
        $demand->setMonth(10);
        $demand->setDay(1);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(2, $events->count());
    }

    /**
     * Test if day restriction works, when event spans multiple days and restriction is limited to a
     * day, which is between the event start- and enddate
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByDayForEventSpanningDateRange()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setStoragePage(70);
        $demand->setDisplayMode('all');
        $demand->setYear(2017);
        $demand->setMonth(10);
        $demand->setDay(2);

        $events = $this->eventRepository->findDemanded($demand);

        $this->assertSame(1, $events->count());
    }
}
