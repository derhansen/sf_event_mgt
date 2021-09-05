<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Location;
use DERHANSEN\SfEventMgt\Domain\Model\Organisator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand.
 */
class EventDemandTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new EventDemand();
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
    public function getDisplayModeReturnsInitialValue()
    {
        self::assertSame(
            'all',
            $this->subject->getDisplayMode()
        );
    }

    /**
     * @test
     */
    public function getTopEventRestrictionReturnsInitialValueForInteger()
    {
        self::assertSame(
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
        self::assertSame(
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
        self::assertEquals('past', $this->subject->getDisplayMode());
    }

    /**
     * @test
     */
    public function getStoragePageReturnsInitialValue()
    {
        self::assertSame(
            '',
            $this->subject->getStoragePage()
        );
    }

    /**
     * @test
     */
    public function setStoragePageForStringSetsStoragePage()
    {
        $this->subject->setStoragePage('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function getCurrentDateTimeReturnsDateTimeObjectIfNoValueSet()
    {
        self::assertInstanceOf('DateTime', $this->subject->getCurrentDateTime());
    }

    /**
     * @test
     */
    public function getCurrentDateTimeReturnsGivenValueIfValueSet()
    {
        $this->subject->setCurrentDateTime(new \DateTime('01.01.2014'));
        self::assertEquals(
            new \DateTime('01.01.2014'),
            $this->subject->getCurrentDateTime()
        );
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getCategory());
    }

    /**
     * @test
     */
    public function getCategoryForStringSetsCategory()
    {
        $this->subject->setCategory('1,2,3,4');
        self::assertEquals(
            '1,2,3,4',
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setIncludeSubcategoriesReturnsInitialValueForBoolean()
    {
        self::assertFalse($this->subject->getIncludeSubcategories());
    }

    /**
     * @test
     */
    public function getOrderFieldReturnsEmptyStringIfNoValueSet()
    {
        self::assertSame(
            '',
            $this->subject->getOrderField()
        );
    }

    /**
     * @test
     */
    public function getOrderFieldReturnsGivenValueIfValueSet()
    {
        $this->subject->setOrderField('title');
        self::assertSame(
            'title',
            $this->subject->getOrderField()
        );
    }

    /**
     * @test
     */
    public function getOrderDirectionReturnsEmptyStringIfNoValueSet()
    {
        self::assertSame(
            '',
            $this->subject->getOrderDirection()
        );
    }

    /**
     * @test
     */
    public function getOrderDirectionReturnsGivenValueIfValueSet()
    {
        $this->subject->setOrderDirection('asc');
        self::assertSame(
            'asc',
            $this->subject->getOrderDirection()
        );
    }

    /**
     * @test
     */
    public function getQueryLimitReturnsNullIfNoValueSet()
    {
        self::assertEquals(0, $this->subject->getQueryLimit());
    }

    /**
     * @test
     */
    public function getQueryLimitReturnsExpectedQueryLimit()
    {
        $this->subject->setQueryLimit(10);
        self::assertSame(
            10,
            $this->subject->getQueryLimit()
        );
    }

    /**
     * @test
     */
    public function getLocationReturnsNullIfNoValueSet()
    {
        self::assertNull($this->subject->getLocation());
    }

    /**
     * @test
     */
    public function getLocationReturnsExpectedLocation()
    {
        $location = new Location();
        $this->subject->setLocation($location);
        self::assertSame(
            $location,
            $this->subject->getLocation()
        );
    }

    /**
     * Test if default value is returned
     *
     * @test
     */
    public function getLocationCityReturnsDefaultValue()
    {
        self::assertSame('', $this->subject->getLocationCity());
    }

    /**
     * Test if value can be set
     *
     * @test
     */
    public function getLocationCityReturnsExpectedValue()
    {
        $this->subject->setLocationCity('Flensburg');
        self::assertSame('Flensburg', $this->subject->getLocationCity());
    }

    /**
     * Test if default value is returned
     *
     * @test
     */
    public function getLocationCountryReturnsDefaultValue()
    {
        self::assertSame('', $this->subject->getLocationCountry());
    }

    /**
     * Test if value can be set
     *
     * @test
     */
    public function getLocationCountryReturnsExpectedValue()
    {
        $this->subject->setLocationCountry('Germany');
        self::assertSame('Germany', $this->subject->getLocationCountry());
    }

    /**
     * @test
     */
    public function getYearReturnsDefaultValue()
    {
        self::assertEquals(0, $this->subject->getYear());
    }

    /**
     * @test
     */
    public function setYearSetsYearForInteger()
    {
        $this->subject->setYear(2017);
        self::assertSame(2017, $this->subject->getYear());
    }

    /**
     * @test
     */
    public function getMonthReturnsDefaultValue()
    {
        self::assertEquals(0, $this->subject->getMonth());
    }

    /**
     * @test
     */
    public function setMonthSetsMonthForInteger()
    {
        $this->subject->setMonth(12);
        self::assertSame(12, $this->subject->getMonth());
    }

    /**
     * @test
     */
    public function getDayReturnsDefaultValue()
    {
        self::assertEquals(0, $this->subject->getDay());
    }

    /**
     * @test
     */
    public function setDaySetsDayForInteger()
    {
        $this->subject->setDay(1);
        self::assertSame(1, $this->subject->getDay());
    }

    /**
     * @test
     */
    public function getCategoryConjuctionReturnsInitialValue()
    {
        self::assertSame('', $this->subject->getCategoryConjunction());
    }

    /**
     * @test
     */
    public function getCategoryConjuctionSetsCategoryConjunctionForString()
    {
        $this->subject->setCategoryConjunction('AND');
        self::assertSame('AND', $this->subject->getCategoryConjunction());
    }

    /**
     * @test
     */
    public function getIgnoreEnableFieldsReturnsDefaultValue()
    {
        self::assertFalse($this->subject->getIgnoreEnableFields());
    }

    /**
     * @test
     */
    public function setIgnoreEnableFieldsSetsValueForBoolean()
    {
        $this->subject->setIgnoreEnableFields(true);
        self::assertTrue($this->subject->getIgnoreEnableFields());
    }

    /**
     * @test
     */
    public function getSearchDemandReturnsInitialValue()
    {
        self::assertNull($this->subject->getSearchDemand());
    }

    /**
     * @test
     */
    public function searchDemandCanBeSet()
    {
        $searchDemamd = new SearchDemand();
        $this->subject->setSearchDemand($searchDemamd);
        self::assertSame($searchDemamd, $this->subject->getSearchDemand());
    }

    /**
     * @test
     */
    public function getOrganisatorReturnsInitialValue()
    {
        self::assertNull($this->subject->getOrganisator());
    }

    /**
     * @test
     */
    public function organisatorCanBeSet()
    {
        $organisator = new Organisator();
        $this->subject->setOrganisator($organisator);
        self::assertSame($organisator, $this->subject->getOrganisator());
    }

    /**
     * @test
     */
    public function getIncludeCurrentReturnsInitialValue()
    {
        self::assertFalse($this->subject->getIncludeCurrent());
    }

    /**
     * @test
     */
    public function includeCurrentCanBeSet()
    {
        $this->subject->setIncludeCurrent(true);
        self::assertTrue($this->subject->getIncludeCurrent());
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectIfEmptySettings()
    {
        $expected = new EventDemand();
        $this->assertEquals($expected, EventDemand::createFromSettings());
    }
}
