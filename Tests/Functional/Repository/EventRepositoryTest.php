<?php

namespace SKYFILLERS\SfEventMgt\Tests\Functional\Repository;

	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
	 *
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 2 of the License, or
	 *  (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

use \TYPO3\CMS\Core\Utility\GeneralUtility;
/**
 * Test case for class \SKYFILLERS\SfEventMgt\Domain\Model\Category.
 */
class EventRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase {
	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/** @var \SKYFILLERS\SfEventMgt\Domain\Repository\EventRepository */
	protected $eventRepository;

	/** @var array  */
	protected $testExtensionsToLoad = array('typo3conf/ext/sf_event_mgt');

	public function setUp() {
		parent::setUp();
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->eventRepository = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\EventRepository');

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
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
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
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
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
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
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
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
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
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
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
	 * Test if startDate restriction in demand works
	 *
	 * @test
	 * @return void
	 */
	public function findDemandedRecordsByStartDate() {
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
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
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
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
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setStoragePage(8);

		$demand->setTitle('TYPO3 CMS course');
		$events = $this->eventRepository->findDemanded($demand);

		$this->assertEquals(2, $events->count());
	}
}
