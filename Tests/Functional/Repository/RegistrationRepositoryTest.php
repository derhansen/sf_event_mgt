<?php

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
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
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
 */
class RegistrationRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase {
	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/** @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository */
	protected $registrationRepository;

	/** @var array */
	protected $testExtensionsToLoad = array('typo3conf/ext/sf_event_mgt');

	public function setUp() {
		parent::setUp();
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->registrationRepository = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository');

		$this->importDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_registration.xml');
	}

	/**
	 * Test if findAll returns all records (expect hidden)
	 *
	 * @test
	 * @return void
	 */
	public function findAll() {
		$registrations = $this->registrationRepository->findAll();
		$this->assertEquals(4, $registrations->count());
	}

	/**
	 * Data provider for findExpiredRegistrations
	 *
	 * @return array
	 */
	public function findExpiredRegistrationsDataProvider() {
		return array(
			'allRegistrationsExpired' => array(
				1402826400, /* 15.06.2014 10:00 */
				3
			),
			'noRegistrationsExpired' => array(
				1402736400, /* 14.06.2014 09:00 */
				0
			),
			'nowIs1030Am' => array(
				1402741800, /* 14.06.2014 10:30 */
				1
			),
		);
	}

	/**
	 * @dataProvider findExpiredRegistrationsDataProvider
	 * @test
	 */
	public function findExpiredRegistrations($dateNow, $expected) {
		$registrations = $this->registrationRepository->findExpiredRegistrations($dateNow);
		$this->assertEquals($expected, $registrations->count());
	}
}
