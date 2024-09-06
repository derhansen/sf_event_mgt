<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Location;
use DERHANSEN\SfEventMgt\Domain\Model\Organisator;
use DERHANSEN\SfEventMgt\Domain\Model\Speaker;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EventDemandTest extends UnitTestCase
{
    protected EventDemand $subject;

    protected function setUp(): void
    {
        $this->subject = new EventDemand();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getDisplayModeReturnsInitialValue(): void
    {
        self::assertSame(
            'all',
            $this->subject->getDisplayMode()
        );
    }

    #[Test]
    public function getTopEventRestrictionReturnsInitialValueForInteger(): void
    {
        self::assertSame(
            0,
            $this->subject->getTopEventRestriction()
        );
    }

    #[Test]
    public function setTopEventRestrictionForIntegerSetsTopEventRestriction(): void
    {
        $this->subject->setTopEventRestriction(1);
        self::assertSame(
            1,
            $this->subject->getTopEventRestriction()
        );
    }

    #[Test]
    public function setDisplayModeForStringSetsDisplayMode(): void
    {
        $this->subject->setDisplayMode('past');
        self::assertEquals('past', $this->subject->getDisplayMode());
    }

    #[Test]
    public function getStoragePageReturnsInitialValue(): void
    {
        self::assertSame(
            '',
            $this->subject->getStoragePage()
        );
    }

    #[Test]
    public function setStoragePageForStringSetsStoragePage(): void
    {
        $this->subject->setStoragePage('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    #[Test]
    public function getCurrentDateTimeReturnsGivenValueIfValueSet(): void
    {
        $this->subject->setCurrentDateTime(new DateTime('01.01.2014'));
        self::assertEquals(
            new DateTime('01.01.2014'),
            $this->subject->getCurrentDateTime()
        );
    }

    #[Test]
    public function getCategoryReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getCategory());
    }

    #[Test]
    public function getCategoryForStringSetsCategory(): void
    {
        $this->subject->setCategory('1,2,3,4');
        self::assertEquals(
            '1,2,3,4',
            $this->subject->getCategory()
        );
    }

    #[Test]
    public function setIncludeSubcategoriesReturnsInitialValueForBoolean(): void
    {
        self::assertFalse($this->subject->getIncludeSubcategories());
    }

    #[Test]
    public function getOrderFieldReturnsEmptyStringIfNoValueSet(): void
    {
        self::assertSame(
            '',
            $this->subject->getOrderField()
        );
    }

    #[Test]
    public function getOrderFieldReturnsGivenValueIfValueSet(): void
    {
        $this->subject->setOrderField('title');
        self::assertSame(
            'title',
            $this->subject->getOrderField()
        );
    }

    #[Test]
    public function getOrderDirectionReturnsEmptyStringIfNoValueSet(): void
    {
        self::assertSame(
            '',
            $this->subject->getOrderDirection()
        );
    }

    #[Test]
    public function getOrderDirectionReturnsGivenValueIfValueSet(): void
    {
        $this->subject->setOrderDirection('asc');
        self::assertSame(
            'asc',
            $this->subject->getOrderDirection()
        );
    }

    #[Test]
    public function getQueryLimitReturnsNullIfNoValueSet(): void
    {
        self::assertEquals(0, $this->subject->getQueryLimit());
    }

    #[Test]
    public function getQueryLimitReturnsExpectedQueryLimit(): void
    {
        $this->subject->setQueryLimit(10);
        self::assertSame(
            10,
            $this->subject->getQueryLimit()
        );
    }

    #[Test]
    public function getLocationReturnsNullIfNoValueSet(): void
    {
        self::assertNull($this->subject->getLocation());
    }

    #[Test]
    public function getLocationReturnsExpectedLocation(): void
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
     */
    #[Test]
    public function getLocationCityReturnsDefaultValue(): void
    {
        self::assertSame('', $this->subject->getLocationCity());
    }

    /**
     * Test if value can be set
     */
    #[Test]
    public function getLocationCityReturnsExpectedValue(): void
    {
        $this->subject->setLocationCity('Flensburg');
        self::assertSame('Flensburg', $this->subject->getLocationCity());
    }

    /**
     * Test if default value is returned
     */
    #[Test]
    public function getLocationCountryReturnsDefaultValue(): void
    {
        self::assertSame('', $this->subject->getLocationCountry());
    }

    /**
     * Test if value can be set
     */
    #[Test]
    public function getLocationCountryReturnsExpectedValue(): void
    {
        $this->subject->setLocationCountry('Germany');
        self::assertSame('Germany', $this->subject->getLocationCountry());
    }

    #[Test]
    public function getYearReturnsDefaultValue(): void
    {
        self::assertEquals(0, $this->subject->getYear());
    }

    #[Test]
    public function setYearSetsYearForInteger(): void
    {
        $this->subject->setYear(2017);
        self::assertSame(2017, $this->subject->getYear());
    }

    #[Test]
    public function getMonthReturnsDefaultValue(): void
    {
        self::assertEquals(0, $this->subject->getMonth());
    }

    #[Test]
    public function setMonthSetsMonthForInteger(): void
    {
        $this->subject->setMonth(12);
        self::assertSame(12, $this->subject->getMonth());
    }

    #[Test]
    public function getDayReturnsDefaultValue(): void
    {
        self::assertEquals(0, $this->subject->getDay());
    }

    #[Test]
    public function setDaySetsDayForInteger(): void
    {
        $this->subject->setDay(1);
        self::assertSame(1, $this->subject->getDay());
    }

    #[Test]
    public function getCategoryConjuctionReturnsInitialValue(): void
    {
        self::assertSame('', $this->subject->getCategoryConjunction());
    }

    #[Test]
    public function getCategoryConjuctionSetsCategoryConjunctionForString(): void
    {
        $this->subject->setCategoryConjunction('AND');
        self::assertSame('AND', $this->subject->getCategoryConjunction());
    }

    #[Test]
    public function getIgnoreEnableFieldsReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->getIgnoreEnableFields());
    }

    #[Test]
    public function setIgnoreEnableFieldsSetsValueForBoolean(): void
    {
        $this->subject->setIgnoreEnableFields(true);
        self::assertTrue($this->subject->getIgnoreEnableFields());
    }

    #[Test]
    public function getSearchDemandReturnsInitialValue(): void
    {
        self::assertNull($this->subject->getSearchDemand());
    }

    #[Test]
    public function searchDemandCanBeSet(): void
    {
        $searchDemamd = new SearchDemand();
        $this->subject->setSearchDemand($searchDemamd);
        self::assertSame($searchDemamd, $this->subject->getSearchDemand());
    }

    #[Test]
    public function getOrganisatorReturnsInitialValue(): void
    {
        self::assertNull($this->subject->getOrganisator());
    }

    #[Test]
    public function organisatorCanBeSet(): void
    {
        $organisator = new Organisator();
        $this->subject->setOrganisator($organisator);
        self::assertSame($organisator, $this->subject->getOrganisator());
    }

    #[Test]
    public function getIncludeCurrentReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getIncludeCurrent());
    }

    #[Test]
    public function includeCurrentCanBeSet(): void
    {
        $this->subject->setIncludeCurrent(true);
        self::assertTrue($this->subject->getIncludeCurrent());
    }

    #[Test]
    public function createFromSettingsReturnsExpectedObjectIfEmptySettings(): void
    {
        $expected = new EventDemand();
        self::assertEquals($expected, EventDemand::createFromSettings());
    }

    #[Test]
    public function createFromSettingsReturnsExpectedObjectWithSettings(): void
    {
        $location = new Location();
        $organisator = new Organisator();
        $speaker = new Speaker();

        $expected = new EventDemand();
        $expected->setDisplayMode('current');
        $expected->setStoragePage('1,2,3');
        $expected->setCategoryConjunction('AND');
        $expected->setCategory('1');
        $expected->setIncludeSubcategories(true);
        $expected->setTopEventRestriction(1);
        $expected->setOrderField('title');
        $expected->setOrderFieldAllowed('uid,title');
        $expected->setOrderDirection('desc');
        $expected->setQueryLimit(1);
        $expected->setTimeRestrictionLow('low');
        $expected->setTimeRestrictionHigh('high');
        $expected->setIncludeCurrent(true);
        $expected->setSpeaker($speaker);
        $expected->setOrganisator($organisator);
        $expected->setLocation($location);

        $settings = [
            'displayMode' => 'current',
            'storagePage' => '1,2,3',
            'categoryConjunction' => 'AND',
            'category' => '1',
            'includeSubcategories' => true,
            'topEventRestriction' => 1,
            'orderField' => 'title',
            'orderFieldAllowed' => 'uid,title',
            'orderDirection' => 'desc',
            'queryLimit' => 1,
            'timeRestrictionLow' => 'low',
            'timeRestrictionHigh' => 'high',
            'includeCurrent' => true,
            'location' => $location,
            'organisator' => $organisator,
            'speaker' => $speaker,
        ];

        self::assertEquals($expected, EventDemand::createFromSettings($settings));
    }
}
