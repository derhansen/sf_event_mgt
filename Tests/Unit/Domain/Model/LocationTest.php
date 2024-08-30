<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DERHANSEN\SfEventMgt\Domain\Model\Location;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class LocationTest extends UnitTestCase
{
    protected Location $subject;

    protected function setUp(): void
    {
        $this->subject = new Location();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if initial value for title is returned
     */
    #[Test]
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * Test if title can be set
     */
    #[Test]
    public function setTitleForStringSetsTitle(): void
    {
        $this->subject->setTitle('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getTitle());
    }

    #[Test]
    public function getAddressReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    #[Test]
    public function setAddressForStringSetsAddress(): void
    {
        $this->subject->setAddress('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getAddress());
    }

    #[Test]
    public function getZipReturnsInitialValueForInteger(): void
    {
        self::assertSame(
            '',
            $this->subject->getZip()
        );
    }

    #[Test]
    public function setZipForIntegerSetsZip(): void
    {
        $this->subject->setZip('12');
        self::assertSame('12', $this->subject->getZip());
    }

    #[Test]
    public function getCityReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCity()
        );
    }

    #[Test]
    public function getCountryReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCountry()
        );
    }

    #[Test]
    public function setCityForStringSetsCity(): void
    {
        $this->subject->setCity('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getCity());
    }

    #[Test]
    public function setCountryForStringSetsCountry(): void
    {
        $this->subject->setCountry('A country');
        self::assertEquals('A country', $this->subject->getCountry());
    }

    /**
     * Test if description returns initial value
     */
    #[Test]
    public function getDescriptionReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * Test if description can be set
     */
    #[Test]
    public function setDescriptionForStringSetsDescription(): void
    {
        $this->subject->setDescription('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getDescription());
    }

    /**
     * Test if link returns initial value
     */
    #[Test]
    public function getLinkReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getLink()
        );
    }

    /**
     * Test if link can be set
     */
    #[Test]
    public function setLinkForStringSetsLink(): void
    {
        $this->subject->setLink('http://www.typo3.org');
        self::assertEquals('http://www.typo3.org', $this->subject->getLink());
    }

    /**
     * Test if initial value is returned
     */
    #[Test]
    public function getLongitudeReturnsInitialValueForFloat(): void
    {
        self::assertSame(0.0, $this->subject->getLongitude());
    }

    /**
     * Test if longitude can be set
     */
    #[Test]
    public function setLongitudeSetsLongitude(): void
    {
        $this->subject->setLongitude(12.345678);
        self::assertSame(12.345678, $this->subject->getLongitude());
    }

    /**
     * Test if initial value is returned
     */
    #[Test]
    public function getLatitudeReturnsInitialValueForFloat(): void
    {
        self::assertSame(0.0, $this->subject->getLatitude());
    }

    /**
     * Test if latitude can be set
     */
    #[Test]
    public function setLatitudeSetsLatitude(): void
    {
        $this->subject->setLatitude(12.345678);
        self::assertSame(12.345678, $this->subject->getLatitude());
    }

    public static function getFullAddressReturnsExpectedResultDataProvider(): array
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

    #[DataProvider('getFullAddressReturnsExpectedResultDataProvider')]
    #[Test]
    public function getFullAddressReturnsExpectedResult($location, $separator, $expected): void
    {
        /** @var Location $location */
        $result = $location->getFullAddress($separator);
        self::assertEquals($expected, $result);
    }
}
