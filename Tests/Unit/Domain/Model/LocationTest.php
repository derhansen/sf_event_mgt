<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\Location;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Location.
 *
 * @author Torben Hansen <derhansen@gmail.com>
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
    protected function setUp()
    {
        $this->subject = new Location();
    }

    /**
     * Teardown
     */
    protected function tearDown()
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );
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

        self::assertAttributeSame(
            '12',
            'zip',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'city',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function setCountryForStringSetsCountry()
    {
        $this->subject->setCountry('A country');

        self::assertAttributeEquals(
            'A country',
            'country',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'description',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'http://www.typo3.org',
            'link',
            $this->subject
        );
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
}
