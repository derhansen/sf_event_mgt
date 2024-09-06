<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ForeignRecordDemandTest extends UnitTestCase
{
    protected ForeignRecordDemand $subject;

    protected function setUp(): void
    {
        $this->subject = new ForeignRecordDemand();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getStoragePageReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getStoragePage());
    }

    #[Test]
    public function setStoragePageSetsStoragePageForString(): void
    {
        $this->subject->setStoragePage('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    #[Test]
    public function getrestrictForeignRecordsToStoragePageReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getRestrictForeignRecordsToStoragePage());
    }

    #[Test]
    public function setRestrictForeignRecordsToStoragePageSetsValueForBoolean(): void
    {
        $this->subject->setRestrictForeignRecordsToStoragePage(true);
        self::assertTrue($this->subject->getRestrictForeignRecordsToStoragePage());
    }

    #[Test]
    public function createFromSettingsReturnsExpectedObjectIfEmptySettings(): void
    {
        $expected = new ForeignRecordDemand();
        self::assertEquals($expected, ForeignRecordDemand::createFromSettings());
    }

    #[Test]
    public function createFromSettingsReturnsExpectedObjectWithSettings(): void
    {
        $expected = new ForeignRecordDemand();
        $expected->setStoragePage('1,2,3');
        $expected->setRestrictForeignRecordsToStoragePage(true);

        $settings = [
            'storagePage' => '1,2,3',
            'restrictForeignRecordsToStoragePage' => true,
        ];

        $current = ForeignRecordDemand::createFromSettings($settings);

        self::assertEquals($expected, $current);
    }
}
