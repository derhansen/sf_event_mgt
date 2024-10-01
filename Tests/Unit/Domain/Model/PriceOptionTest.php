<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\PriceOption;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
    public function getTitleReturnsInitialValue(): void
    {
        self::assertSame('', $this->subject->getTitle());
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $this->subject->setTitle('Title');
        self::assertSame('Title', $this->subject->getTitle());
    }

    #[Test]
    public function getDescriptionReturnsInitialValue(): void
    {
        self::assertSame('', $this->subject->getDescription());
    }

    #[Test]
    public function setDescriptionSetsDescription(): void
    {
        $this->subject->setDescription('Description');
        self::assertSame('Description', $this->subject->getDescription());
    }

    #[Test]
    public function getPriceReturnsInitialValueForFloat(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getPrice()
        );
    }

    #[Test]
    public function setPriceForFloatSetsPrice(): void
    {
        $this->subject->setPrice(12.99);
        self::assertSame(12.99, $this->subject->getPrice());
    }

    #[Test]
    public function getValidUntilReturnsInitialValueForDate(): void
    {
        self::assertNull($this->subject->getValidUntil());
    }

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
