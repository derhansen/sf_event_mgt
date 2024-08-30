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
use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\PriceOption;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class PriceOptionTest extends UnitTestCase
{
    protected PriceOption $subject;

    protected function setUp(): void
    {
        $this->subject = new PriceOption();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if initial value for price is returned
     */
    #[Test]
    public function getPriceReturnsInitialValueForFloat(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getPrice()
        );
    }

    /**
     * Test if price can be set
     */
    #[Test]
    public function setPriceForFloatSetsPrice(): void
    {
        $this->subject->setPrice(12.99);
        self::assertSame(12.99, $this->subject->getPrice());
    }

    /**
     * Test if validUntil date returns intitial value
     */
    #[Test]
    public function getValidUntilReturnsInitialValueForDate(): void
    {
        self::assertNull($this->subject->getValidUntil());
    }

    /**
     * Test if validUntil date can be set
     */
    #[Test]
    public function setValidUntilForDateSetsValidUntil(): void
    {
        $date = new DateTime('01.01.2016');
        $this->subject->setValidUntil($date);
        self::assertEquals($date, $this->subject->getValidUntil());
    }

    #[Test]
    public function getEventReturnsInitialValue(): void
    {
        self::assertNull($this->subject->getEvent());
    }

    #[Test]
    public function setEventForEventSetsEvent(): void
    {
        $event = new Event();
        $this->subject->setEvent($event);
        self::assertEquals($event, $this->subject->getEvent());
    }
}
