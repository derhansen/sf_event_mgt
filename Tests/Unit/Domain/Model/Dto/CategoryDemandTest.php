<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryDemandTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function setStoragePageSetsStoragePageForString()
    {
        $this->subject->setStoragePage('1,2,3');
        $this->assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function getRestrictStoragePageReturnsInitialValue()
    {
        $this->assertFalse($this->subject->getRestrictToStoragePage());
    }

    /**
     * @test
     */
    public function setRestrictStoragePageSetsValueForBoolean()
    {
        $this->subject->setRestrictToStoragePage(true);
        $this->assertTrue($this->subject->getRestrictToStoragePage());
    }

    /**
     * @test
     */
    public function setCategoriesSetsCategoriesForString()
    {
        $this->subject->setCategories('1,2,3');
        $this->assertEquals('1,2,3', $this->subject->getCategories());
    }

    /**
     * @test
     */
    public function getIncludeSubcategoriesReturnsInitialValue()
    {
        $this->assertFalse($this->subject->getIncludeSubcategories());
    }

    /**
     * @test
     */
    public function setIncludeSubcategoriesSetsValueForBoolean()
    {
        $this->subject->setIncludeSubcategories(true);
        $this->assertTrue($this->subject->getIncludeSubcategories());
    }
}
