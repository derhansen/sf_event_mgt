<?php

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

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
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Location();
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
     * Test if initial value for title is returned
     *
     * @test
     * @return void
     */
    public function getTitleReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * Test if title can be set
     *
     * @test
     * @return void
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getAddressReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getZipReturnsInitialValueForInteger()
    {
        $this->assertSame(
            '',
            $this->subject->getZip()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setZipForIntegerSetsZip()
    {
        $this->subject->setZip('12');

        $this->assertAttributeSame(
            '12',
            'zip',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getCityReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getCity()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getCountryReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setCityForStringSetsCity()
    {
        $this->subject->setCity('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'city',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function setCountryForStringSetsCountry()
    {
        $this->subject->setCountry('A country');

        $this->assertAttributeEquals(
            'A country',
            'country',
            $this->subject
        );
    }

    /**
     * Test if description returns initial value
     *
     * @test
     * @return void
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * Test if description can be set
     *
     * @test
     * @return void
     */
    public function setDescriptionForStringSetsDescription()
    {
        $this->subject->setDescription('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'description',
            $this->subject
        );
    }

    /**
     * Test if link returns initial value
     *
     * @test
     * @return void
     */
    public function getLinkReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getLink()
        );
    }

    /**
     * Test if link can be set
     *
     * @test
     * @return void
     */
    public function setLinkForStringSetsLink()
    {
        $this->subject->setLink('http://www.typo3.org');

        $this->assertAttributeEquals(
            'http://www.typo3.org',
            'link',
            $this->subject
        );
    }

    /**
     * Test if initial value is returned
     *
     * @test
     * @return void
     */
    public function getLongitudeReturnsInitialValueForFloat()
    {
        $this->assertSame(0.0, $this->subject->getLongitude());
    }

    /**
     * Test if longitude can be set
     *
     * @test
     * @return void
     */
    public function setLongitudeSetsLongitude()
    {
        $this->subject->setLongitude(12.345678);
        $this->assertSame(12.345678, $this->subject->getLongitude());
    }

    /**
     * Test if initial value is returned
     *
     * @test
     * @return void
     */
    public function getLatitudeReturnsInitialValueForFloat()
    {
        $this->assertSame(0.0, $this->subject->getlatitude());
    }

    /**
     * Test if latitude can be set
     *
     * @test
     * @return void
     */
    public function setLatitudeSetsLatitude()
    {
        $this->subject->setlatitude(12.345678);
        $this->assertSame(12.345678, $this->subject->getlatitude());
    }
}