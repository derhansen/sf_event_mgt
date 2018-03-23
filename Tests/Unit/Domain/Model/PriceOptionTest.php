<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\PriceOption.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PriceOptionTest extends UnitTestCase
{
    /**
     * Location object
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\PriceOption
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\PriceOption();
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
     * Test if initial value for price is returned
     *
     * @test
     * @return void
     */
    public function getPriceReturnsInitialValueForFloat()
    {
        $this->assertSame(
            0.0,
            $this->subject->getPrice()
        );
    }

    /**
     * Test if price can be set
     *
     * @test
     * @return void
     */
    public function setPriceForFloatSetsPrice()
    {
        $this->subject->setPrice(12.99);

        $this->assertAttributeEquals(
            12.99,
            'price',
            $this->subject
        );
    }

    /**
     * Test if validUntil date returns intitial value
     *
     * @test
     * @return void
     */
    public function getValidUntilReturnsInitialValueForDate()
    {
        $this->assertNull($this->subject->getValidUntil());
    }

    /**
     * Test if validUntil date can be set
     *
     * @test
     * @return void
     */
    public function setValidUntilForDateSetsValidUntil()
    {
        $date = new \DateTime('01.01.2016');
        $this->subject->setValidUntil($date);
        $this->assertEquals($date, $this->subject->getValidUntil());
    }

    /**
     * Test if event returns intitial value
     *
     * @test
     * @return void
     */
    public function getEventReturnsInitialValue()
    {
        $this->assertNull($this->subject->getEvent());
    }

    /**
     * @test
     * @return void
     */
    public function setEventForEventSetsEvent()
    {
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();
        $this->subject->setEvent($event);
        $this->assertEquals($event, $this->subject->getEvent());
    }
}
