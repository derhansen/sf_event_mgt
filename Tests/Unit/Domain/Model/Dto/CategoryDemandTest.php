<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand
 */
class CategoryDemandTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new CategoryDemand();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getStoragePageReturnsIntialValueForString()
    {
        $this->assertEquals('', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function setStoragePageSetsStoragePageForString()
    {
        $this->subject->setStoragePage('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function getRestrictStoragePageReturnsInitialValue()
    {
        self::assertFalse($this->subject->getRestrictToStoragePage());
    }

    /**
     * @test
     */
    public function setRestrictStoragePageSetsValueForBoolean()
    {
        $this->subject->setRestrictToStoragePage(true);
        self::assertTrue($this->subject->getRestrictToStoragePage());
    }

    public function getCategoriesReturnsInitialValueForString()
    {
        $this->assertEquals('', $this->subject->getCategories());
    }

    /**
     * @test
     */
    public function setCategoriesSetsCategoriesForString()
    {
        $this->subject->setCategories('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getCategories());
    }

    /**
     * @test
     */
    public function getIncludeSubcategoriesReturnsInitialValue()
    {
        self::assertFalse($this->subject->getIncludeSubcategories());
    }

    /**
     * @test
     */
    public function setIncludeSubcategoriesSetsValueForBoolean()
    {
        $this->subject->setIncludeSubcategories(true);
        self::assertTrue($this->subject->getIncludeSubcategories());
    }

    /**
     * @test
     */
    public function orderFieldReturnsDefaultValue()
    {
        self::assertSame('uid', $this->subject->getOrderField());
    }

    /**
     * @test
     */
    public function orderFieldCanBeSet()
    {
        $this->subject->setOrderField('title');
        self::assertSame('title', $this->subject->getOrderField());
    }

    /**
     * @test
     */
    public function orderDirectionReturnsDefaultValue()
    {
        self::assertSame('asc', $this->subject->getOrderDirection());
    }

    /**
     * @test
     */
    public function orderDirectionCanBeSet()
    {
        $this->subject->setOrderDirection('desc');
        self::assertSame('desc', $this->subject->getOrderDirection());
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectIfEmptySettings()
    {
        $expected = new CategoryDemand();
        $current = CategoryDemand::createFromSettings();

        $this->assertEquals($expected, $current);
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectWithSettings()
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
            ]
        ];

        $current = CategoryDemand::createFromSettings($settings);

        $this->assertEquals($expected, $current);
    }
}
