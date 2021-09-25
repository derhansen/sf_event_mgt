<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\Location;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Location.
 */
class LocationTest extends UnitTestCase
{
    /**
     * Location object
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Location
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new Location();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if initial value for title is returned
     *
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * Test if title can be set
     *
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getAddress());
    }

    /**
     * @test
     */
    public function getZipReturnsInitialValueForInteger()
    {
        self::assertSame(
            '',
            $this->subject->getZip()
        );
    }

    /**
     * @test
     */
    public function setZipForIntegerSetsZip()
    {
        $this->subject->setZip('12');
        self::assertSame('12', $this->subject->getZip());
    }

    /**
     * @test
     */
    public function getCityReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCity()
        );
    }

    /**
     * @test
     */
    public function getCountryReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     */
    public function setCityForStringSetsCity()
    {
        $this->subject->setCity('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getCity());
    }

    /**
     * @test
     */
    public function setCountryForStringSetsCountry()
    {
        $this->subject->setCountry('A country');
        self::assertEquals('A country', $this->subject->getCountry());
    }

    /**
     * Test if description returns initial value
     *
     * @test
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * Test if description can be set
     *
     * @test
     */
    public function setDescriptionForStringSetsDescription()
    {
        $this->subject->setDescription('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getDescription());
    }

    /**
     * Test if link returns initial value
     *
     * @test
     */
    public function getLinkReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLink()
        );
    }

    /**
     * Test if link can be set
     *
     * @test
     */
    public function setLinkForStringSetsLink()
    {
        $this->subject->setLink('http://www.typo3.org');
        self::assertEquals('http://www.typo3.org', $this->subject->getLink());
    }

    /**
     * Test if initial value is returned
     *
     * @test
     */
    public function getLongitudeReturnsInitialValueForFloat()
    {
        self::assertSame(0.0, $this->subject->getLongitude());
    }

    /**
     * Test if longitude can be set
     *
     * @test
     */
    public function setLongitudeSetsLongitude()
    {
        $this->subject->setLongitude(12.345678);
        self::assertSame(12.345678, $this->subject->getLongitude());
    }

    /**
     * Test if initial value is returned
     *
     * @test
     */
    public function getLatitudeReturnsInitialValueForFloat()
    {
        self::assertSame(0.0, $this->subject->getLatitude());
    }

    /**
     * Test if latitude can be set
     *
     * @test
     */
    public function setLatitudeSetsLatitude()
    {
        $this->subject->setLatitude(12.345678);
        self::assertSame(12.345678, $this->subject->getLatitude());
    }

    public function getFullAddressReturnsExpectedResultDataProvider(): array
    {
        $location1 = new Location();
        $location1->setAddress('Address 123');
        $location1->setCity('A City');
        $location1->setZip('12345');
        $location1->setCountry('A Country');

        $location2 = new Location();
        $location2->setAddress('Address 123');

        $location3 = new Location();
        $location3->setAddress('Address 123');
        $location3->setZip('12345');

        $location4 = new Location();
        $location4->setAddress('Address 123');
        $location4->setCity('A City');

        return [
            'default location' => [
                new Location(),
                '<br/>',
                '',
            ],
            'location with all data with br as separator' => [
                $location1,
                '<br/>',
                'Address 123<br/>12345 A City<br/>A Country',
            ],
            'location with all data with comma as separator' => [
                $location1,
                ',',
                'Address 123,12345 A City,A Country',
            ],
            'location with no zip and city' => [
                $location2,
                ',',
                'Address 123',
            ],
            'location with no city' => [
                $location3,
                ',',
                'Address 123,12345',
            ],
            'location with no zip' => [
                $location4,
                ',',
                'Address 123,A City',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getFullAddressReturnsExpectedResultDataProvider
     */
    public function getFullAddressReturnsExpectedResult($location, $separator, $expected)
    {
        /** @var Location $location */
        $result = $location->getFullAddress($separator);
        self::assertEquals($expected, $result);
    }
}
