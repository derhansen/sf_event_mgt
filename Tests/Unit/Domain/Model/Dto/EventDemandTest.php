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
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventDemandTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
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
    public function getDisplayModeReturnsInitialValue()
    {
        $this->assertSame(
            'all',
            $this->subject->getDisplayMode()
        );
    }

    /**
     * @test
     */
    public function getTopEventRestrictionReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getTopEventRestriction()
        );
    }

    /**
     * @test
     */
    public function setTopEventRestrictionForIntegerSetsTopEventRestriction()
    {
        $this->subject->setTopEventRestriction(1);
        $this->assertSame(
            1,
            $this->subject->getTopEventRestriction()
        );
    }

    /**
     * @test
     */
    public function setDisplayModeForStringSetsDisplayMode()
    {
        $this->subject->setDisplayMode('past');

        $this->assertAttributeEquals(
            'past',
            'displayMode',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStoragePageReturnsInitialValue()
    {
        $this->assertSame(
            null,
            $this->subject->getStoragePage()
        );
    }

    /**
     * @test
     */
    public function setStoragePageForStringSetsStoragePage()
    {
        $this->subject->setStoragePage('1,2,3');

        $this->assertAttributeEquals(
            '1,2,3',
            'storagePage',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCurrentDateTimeReturnsDateTimeObjectIfNoValueSet()
    {
        $this->assertInstanceOf('DateTime', $this->subject->getCurrentDateTime());
    }

    /**
     * @test
     */
    public function getCurrentDateTimeReturnsGivenValueIfValueSet()
    {
        $this->subject->setCurrentDateTime(new \DateTime('01.01.2014'));
        $this->assertEquals(
            new \DateTime('01.01.2014'),
            $this->subject->getCurrentDateTime()
        );
    }

    /**
     * @test
     */
    public function getCategoryForStringSetsCategory()
    {
        $this->subject->setCategory('1,2,3,4');
        $this->assertEquals(
            '1,2,3,4',
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setIncludeSubcategoriesReturnsInitialValueForBoolean()
    {
        $this->assertFalse($this->subject->getIncludeSubcategories());
    }

    /**
     * @test
     * @return void
     */
    public function getOrderFieldReturnsEmptyStringIfNoValueSet()
    {
        $this->assertSame(
            '',
            $this->subject->getOrderField()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getOrderFieldReturnsGivenValueIfValueSet()
    {
        $this->subject->setOrderField('title');
        $this->assertSame(
            'title',
            $this->subject->getOrderField()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getOrderDirectionReturnsEmptyStringIfNoValueSet()
    {
        $this->assertSame(
            '',
            $this->subject->getOrderDirection()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getOrderDirectionReturnsGivenValueIfValueSet()
    {
        $this->subject->setOrderDirection('asc');
        $this->assertSame(
            'asc',
            $this->subject->getOrderDirection()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getQueryLimitReturnsNullIfNoValueSet()
    {
        $this->assertNull($this->subject->getQueryLimit());
    }

    /**
     * @test
     * @return void
     */
    public function getQueryLimitReturnsExpectedQueryLimit()
    {
        $this->subject->setQueryLimit(10);
        $this->assertSame(
            10,
            $this->subject->getQueryLimit()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getLocationReturnsNullIfNoValueSet()
    {
        $this->assertNull($this->subject->getLocation());
    }

    /**
     * @test
     * @return void
     */
    public function getLocationReturnsExpectedLocation()
    {
        $location = new \DERHANSEN\SfEventMgt\Domain\Model\Location();
        $this->subject->setLocation($location);
        $this->assertSame(
            $location,
            $this->subject->getLocation()
        );
    }

    /**
     * Test if default value is returned
     *
     * @test
     * @return void
     */
    public function getLocationCityReturnsDefaultValue()
    {
        $this->assertSame('', $this->subject->getLocationCity());
    }

    /**
     * Test if value can be set
     *
     * @test
     * @return void
     */
    public function getLocationCityReturnsExpectedValue()
    {
        $this->subject->setLocationCity('Flensburg');
        $this->assertSame('Flensburg', $this->subject->getLocationCity());
    }

    /**
     * Test if default value is returned
     *
     * @test
     * @return void
     */
    public function getLocationCountryReturnsDefaultValue()
    {
        $this->assertSame('', $this->subject->getLocationCountry());
    }

    /**
     * Test if value can be set
     *
     * @test
     * @return void
     */
    public function getLocationCountryReturnsExpectedValue()
    {
        $this->subject->setLocationCountry('Germany');
        $this->assertSame('Germany', $this->subject->getLocationCountry());
    }

    /**
     * @test
     */
    public function getYearReturnsDefaultValue()
    {
        $this->assertNull($this->subject->getYear());
    }

    /**
     * @test
     */
    public function setYearSetsYearForInteger()
    {
        $this->subject->setYear(2017);
        $this->assertSame(2017, $this->subject->getYear());
    }

    /**
     * @test
     */
    public function getMonthReturnsDefaultValue()
    {
        $this->assertNull($this->subject->getMonth());
    }

    /**
     * @test
     */
    public function setMonthSetsMonthForInteger()
    {
        $this->subject->setMonth(12);
        $this->assertSame(12, $this->subject->getMonth());
    }

    /**
     * @test
     */
    public function getDayReturnsDefaultValue()
    {
        $this->assertNull($this->subject->getDay());
    }

    /**
     * @test
     */
    public function setDaySetsDayForInteger()
    {
        $this->subject->setDay(1);
        $this->assertSame(1, $this->subject->getDay());
    }

    /**
     * @test
     */
    public function getCategoryConjuctionReturnsInitialValue()
    {
        $this->assertSame('', $this->subject->getCategoryConjunction());
    }

    /**
     * @test
     */
    public function getCategoryConjuctionSetsCategoryConjunctionForString()
    {
        $this->subject->setCategoryConjunction('AND');
        $this->assertSame('AND', $this->subject->getCategoryConjunction());
    }
}
