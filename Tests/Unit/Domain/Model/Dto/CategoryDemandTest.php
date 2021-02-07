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
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryDemandTest extends UnitTestCase
{
    /**
     * @var CategoryDemand
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
        $this->assertSame('uid', $this->subject->getOrderField());
    }

    /**
     * @test
     */
    public function orderFieldCanBeSet()
    {
        $this->subject->setOrderField('title');
        $this->assertSame('title', $this->subject->getOrderField());
    }

    /**
     * @test
     */
    public function orderDirectionReturnsDefaultValue()
    {
        $this->assertSame('asc', $this->subject->getOrderDirection());
    }

    /**
     * @test
     */
    public function orderDirectionCanBeSet()
    {
        $this->subject->setOrderDirection('desc');
        $this->assertSame('desc', $this->subject->getOrderDirection());
    }
}
