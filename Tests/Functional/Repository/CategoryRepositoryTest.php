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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryRepositoryTest extends FunctionalTestCase
{
    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository */
    protected $categoryRepository;

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
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->categoryRepository = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository');
        $this->importDataSet(__DIR__ . '/../Fixtures/sys_category.xml');
    }

    /**
     * Test if startingpoint is working
     *
     * @test
     * @return void
     */
    public function findRecordsByUid()
    {
        $events = $this->categoryRepository->findByUid(1);
        $this->assertEquals($events->getTitle(), 'Category 1');
    }

    /**
     * Test if storagePage restriction in demand works
     *
     * @test
     * @return void
     */
    public function findDemandedRecordsByStoragePageRestriction()
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\CategoryDemand');
        $demand->setStoragePage(1);
        $demand->setRestrictToStoragePage(true);
        $events = $this->categoryRepository->findDemanded($demand);
        $this->assertEquals(3, $events->count());
    }

    /**
     * DataProvider for findDemandedRecordsByCategory
     *
     * @return array
     */
    public function findDemandedRecordsByCategoryDataProvider()
    {
        return [
            'category 1' => [
                '1',
                false,
                1
            ],
            'category 1,2' => [
                '1,2',
                false,
                2
            ],
            'category 3 excluding subcategories' => [
                '3',
                false,
                1
            ],
            'category 3 including subcategories' => [
                '3',
                true,
                3
            ]
        ];
    }

    /**
     * Test if category restiction works
     *
     * @dataProvider findDemandedRecordsByCategoryDataProvider
     * @test
     * @return void
     */
    public function findDemandedRecordsByCategory($category, $includeSubcategory, $expected)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\CategoryDemand');
        $demand->setIncludeSubcategories($includeSubcategory);

        $demand->setCategories($category);
        $this->assertEquals($expected, $this->categoryRepository->findDemanded($demand)->count());
    }
}
