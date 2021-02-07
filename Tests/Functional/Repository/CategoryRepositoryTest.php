<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand;
use DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository
 */
class CategoryRepositoryTest extends FunctionalTestCase
{
    /** @var ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var CategoryRepository */
    protected $categoryRepository;

    /** @var array */
    protected $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->categoryRepository = $this->objectManager->get(CategoryRepository::class);
        $this->importDataSet(__DIR__ . '/../Fixtures/sys_category.xml');
    }

    /**
     * Test if startingpoint is working
     *
     * @test
     */
    public function findRecordsByUid()
    {
        $events = $this->categoryRepository->findByUid(1);
        self::assertEquals($events->getTitle(), 'Category 1');
    }

    /**
     * Test if storagePage restriction in demand works
     *
     * @test
     */
    public function findDemandedRecordsByStoragePageRestriction()
    {
        /** @var CategoryDemand $demand */
        $demand = $this->objectManager->get(CategoryDemand::class);
        $demand->setStoragePage(1);
        $demand->setRestrictToStoragePage(true);
        $events = $this->categoryRepository->findDemanded($demand);
        self::assertEquals(3, $events->count());
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
     * @param mixed $category
     * @param mixed $includeSubcategory
     * @param mixed $expected
     */
    public function findDemandedRecordsByCategory($category, $includeSubcategory, $expected)
    {
        /** @var CategoryDemand $demand */
        $demand = $this->objectManager->get(CategoryDemand::class);
        $demand->setIncludeSubcategories($includeSubcategory);

        $demand->setCategories($category);
        self::assertEquals($expected, $this->categoryRepository->findDemanded($demand)->count());
    }
}
