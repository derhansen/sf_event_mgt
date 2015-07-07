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
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryRepositoryTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase {

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
	protected $objectManager;

	/** @var \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository */
	protected $categoryRepository;

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
		$this->categoryRepository = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository');

		$this->importDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_category.xml');
	}

	/**
	 * Test if startingpoint is ignored
	 *
	 * @test
	 * @return void
	 */
	public function findRecordsByUid() {
		$categories = $this->categoryRepository->findAll();

		$this->assertEquals(2, $categories->count());
	}

}
