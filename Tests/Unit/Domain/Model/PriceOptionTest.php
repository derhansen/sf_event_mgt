<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\PriceOption;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\PriceOption.
 */
class PriceOptionTest extends UnitTestCase
{
    /**
     * Location object
     *
     * @var PriceOption
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new PriceOption();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if initial value for price is returned
     *
     * @test
     */
    public function getPriceReturnsInitialValueForFloat()
    {
        self::assertSame(
            0.0,
            $this->subject->getPrice()
        );
    }

    /**
     * Test if price can be set
     *
     * @test
     */
    public function setPriceForFloatSetsPrice()
    {
        $this->subject->setPrice(12.99);
        self::assertSame(12.99, $this->subject->getPrice());
    }

    /**
     * Test if validUntil date returns intitial value
     *
     * @test
     */
    public function getValidUntilReturnsInitialValueForDate()
    {
        self::assertNull($this->subject->getValidUntil());
    }

    /**
     * Test if validUntil date can be set
     *
     * @test
     */
    public function setValidUntilForDateSetsValidUntil()
    {
        $date = new \DateTime('01.01.2016');
        $this->subject->setValidUntil($date);
        self::assertEquals($date, $this->subject->getValidUntil());
    }

    /**
     * @test
     */
    public function setEventForEventSetsEvent()
    {
        $event = new Event();
        $this->subject->setEvent($event);
        self::assertEquals($event, $this->subject->getEvent());
    }
}
