<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand;
use DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class CategoryRepositoryTest extends FunctionalTestCase
{
    protected CategoryRepository $categoryRepository;
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];
    protected array $coreExtensionsToLoad = ['core', 'extbase', 'fluid'];

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->getContainer()->get(CategoryRepository::class);
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/sys_category.csv');

        $request = (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $GLOBALS['TYPO3_REQUEST'] = $request;
    }

    /**
     * @test
     */
    public function findRecordsByUid(): void
    {
        $events = $this->categoryRepository->findByUid(1);
        self::assertEquals($events->getTitle(), 'Category 1');
    }

    /**
     * @test
     */
    public function findDemandedRecordsByStoragePageRestriction(): void
    {
        $demand = new CategoryDemand();
        $demand->setStoragePage('1');
        $demand->setRestrictToStoragePage(true);
        $events = $this->categoryRepository->findDemanded($demand);
        self::assertEquals(3, $events->count());
    }

    public function findDemandedRecordsByCategoryDataProvider(): array
    {
        return [
            'category 1' => [
                '1',
                false,
                1,
            ],
            'category 1,2' => [
                '1,2',
                false,
                2,
            ],
            'category 3 excluding subcategories' => [
                '3',
                false,
                1,
            ],
            'category 3 including subcategories' => [
                '3',
                true,
                3,
            ],
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
    public function findDemandedRecordsByCategory($category, $includeSubcategory, $expected): void
    {
        $demand = new CategoryDemand();
        $demand->setIncludeSubcategories($includeSubcategory);

        $demand->setCategories($category);
        self::assertEquals($expected, $this->categoryRepository->findDemanded($demand)->count());
    }
}
