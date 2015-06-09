<?php

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase {
	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/** @var \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository */
	protected $eventRepository;

	/** @var \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository */
	protected $locationRepository;

	/** @var array  */
	protected $testExtensionsToLoad = array('typo3conf/ext/sf_event_mgt');

	/**
	 * Setup
	 *
	 * @throws \TYPO3\CMS\Core\Tests\Exception
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->eventRepository = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository');
		$this->locationRepository = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository');

		$this->importDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_event.xml');
	}

	/**
	 * Test if startingpoint is working
	 *
	 * @test
	 * @return void
	 */
	public function findRecordsByUid() {
		$events = $this->eventRepository->findByUid(1);

		$this->assertEquals($events->getTitle(), 'findRecordsByUid');
	}

	/**
	 * Test if storagePage restriction in demand works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByStoragePage() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(3);
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(3, $events->count());
	}

	/**
	 * Test if displayMode 'all' restriction in demand works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByDisplayModeAll() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(4);
		$demand->setDisplayMode('all');
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(4, $events->count());
	}

	/**
	 * Test if displayMode 'past' restriction in demand works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByDisplayModePast() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(4);
		$demand->setDisplayMode('past');
		$demand->setCurrentDateTime(new \DateTime('30.05.2014'));
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(1, $events->count());
	}

	/**
	 * Test if displayMode 'future' restriction in demand works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByDisplayModeFuture() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(4);
		$demand->setDisplayMode('future');
		$demand->setCurrentDateTime(new \DateTime('30.05.2014 14:00:00'));
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(2, $events->count());
	}

	/**
	 * Test if category restiction works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByCategory() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(5);

		$demand->setCategory('1');
		$this->assertEquals(1, $this->eventRepository->findDemanded($demand)->count());

		$demand->setCategory('2');
		$this->assertEquals(2, $this->eventRepository->findDemanded($demand)->count());

		$demand->setCategory('3');
		$this->assertEquals(1, $this->eventRepository->findDemanded($demand)->count());

		$demand->setCategory('1,2,3,4');
		$this->assertEquals(3, $this->eventRepository->findDemanded($demand)->count());
	}

	/**
	 * Test if location restriction works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByLocation() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(40);

		$location = $this->locationRepository->findByUid(1);
		$demand->setLocation($location);
		$this->assertEquals(1, $this->eventRepository->findDemanded($demand)->count());

		$location = $this->locationRepository->findByUid(2);
		$demand->setLocation($location);
		$this->assertEquals(1, $this->eventRepository->findDemanded($demand)->count());

		$location = $this->locationRepository->findByUid(3);
		$demand->setLocation($location);
		$this->assertEquals(0, $this->eventRepository->findDemanded($demand)->count());

	}

	/**
	 * Test if startDate restriction in demand works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByStartDate() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(6);

		$demand->setStartDate(new \DateTime('30.05.2014 14:00:00'));
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(2, $events->count());
	}

	/**
	 * Test if endDate restriction in demand works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByEndDate() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(7);

		$demand->setEndDate(new \DateTime('02.06.2014 08:00'));
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(2, $events->count());
	}

	/**
	 * Test if title restriction in demand works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByTitle() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(8);

		$demand->setTitle('TYPO3 CMS course');
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(2, $events->count());
	}

	/**
	 * Data provider for findDemandedRecordsByTopEvent
	 *
	 * @return array
	 */
	public function findDemandedRecordsByTopEventDataProvider() {
		return array(
			'noRestriction' => array(
				0,
				2
			),
			'onlyTopEvents' => array(
				1,
				1
			),
			'exceptTopEvents' => array(
				2,
				1
			),
		);
	}

	/**
	 * Test if top event restriction in demand works
	 *
	 * @dataProvider findDemandedRecordsByTopEventDataProvider
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByTopEvent($topEventRestriction, $expected) {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(30);

		$demand->setTopEventRestriction($topEventRestriction);
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals($expected, $events->count());
	}

	/**
	 * Data provider for findDemandedRecordsByTopEvent
	 *
	 * @return array
	 */
	public function findDemandedRecordsByOrderingDataProvider() {
		return array(
			'noSorting' => array(
				'',
				'',
				'Test2'
			),
			'titleAsc' => array(
				'title',
				'asc',
				'Test1'
			),
			'titleDesc' => array(
				'title',
				'desc',
				'Test4'
			),
			'startdateAsc' => array(
				'startdate',
				'asc',
				'Test2'
			),
			'startdateDesc' => array(
				'startdate',
				'desc',
				'Test3'
			),
			'enddateAsc' => array(
				'enddate',
				'asc',
				'Test2'
			),
			'enddateDesc' => array(
				'enddate',
				'desc',
				'Test4'
			),
		);
	}

	/**
	 * Test if ordering for findDemanded works
	 *
	 * @dataProvider findDemandedRecordsByOrderingDataProvider
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByOrdering($orderField, $orderDirection, $expected) {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(4);
		$demand->setDisplayMode('all');
		$demand->setOrderField($orderField);
		$demand->setOrderDirection($orderDirection);
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals($expected, $events->getFirst()->getTitle());
	}

	/**
	 * Test if limit restriction works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsSetsLimit() {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(4);
		$demand->setDisplayMode('all');
		$demand->setQueryLimit(2);

		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(2, $events->count());
	}
}
