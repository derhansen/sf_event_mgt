<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CategoryDemandTest extends UnitTestCase
{
    protected CategoryDemand $subject;

    protected function setUp(): void
    {
        $this->subject = new CategoryDemand();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getStoragePageReturnsIntialValueForString(): void
    {
        self::assertEquals('', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function setStoragePageSetsStoragePageForString(): void
    {
        $this->subject->setStoragePage('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function getRestrictStoragePageReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getRestrictToStoragePage());
    }

    /**
     * @test
     */
    public function setRestrictStoragePageSetsValueForBoolean(): void
    {
        $this->subject->setRestrictToStoragePage(true);
        self::assertTrue($this->subject->getRestrictToStoragePage());
    }

    public function getCategoriesReturnsInitialValueForString(): void
    {
        self::assertEquals('', $this->subject->getCategories());
    }

    /**
     * @test
     */
    public function setCategoriesSetsCategoriesForString(): void
    {
        $this->subject->setCategories('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getCategories());
    }

    /**
     * @test
     */
    public function getIncludeSubcategoriesReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getIncludeSubcategories());
    }

    /**
     * @test
     */
    public function setIncludeSubcategoriesSetsValueForBoolean(): void
    {
        $this->subject->setIncludeSubcategories(true);
        self::assertTrue($this->subject->getIncludeSubcategories());
    }

    /**
     * @test
     */
    public function orderFieldReturnsDefaultValue(): void
    {
        self::assertSame('uid', $this->subject->getOrderField());
    }

    /**
     * @test
     */
    public function orderFieldCanBeSet(): void
    {
        $this->subject->setOrderField('title');
        self::assertSame('title', $this->subject->getOrderField());
    }

    /**
     * @test
     */
    public function orderDirectionReturnsDefaultValue(): void
    {
        self::assertSame('asc', $this->subject->getOrderDirection());
    }

    /**
     * @test
     */
    public function orderDirectionCanBeSet(): void
    {
        $this->subject->setOrderDirection('desc');
        self::assertSame('desc', $this->subject->getOrderDirection());
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectIfEmptySettings(): void
    {
        $expected = new CategoryDemand();
        $current = CategoryDemand::createFromSettings();

        self::assertEquals($expected, $current);
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectWithSettings(): void
    {
        $expected = new CategoryDemand();
        $expected->setStoragePage('1,2,3');
        $expected->setRestrictToStoragePage(true);
        $expected->setCategories('1,2,3');
        $expected->setIncludeSubcategories(true);
        $expected->setOrderField('title');
        $expected->setOrderDirection('desc');

        $settings = [
            'storagePage' => '1,2,3',
            'recursive' => 0,
            'restrictForeignRecordsToStoragePage' => true,
            'categoryMenu' => [
                'categories' => '1,2,3',
                'includeSubcategories' => true,
                'orderField' => 'title',
                'orderDirection' => 'desc',
            ],
        ];

        $current = CategoryDemand::createFromSettings($settings);

        self::assertEquals($expected, $current);
    }
}
