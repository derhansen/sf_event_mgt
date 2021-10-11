<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand
 */
class ForeignRecordDemandTest extends UnitTestCase
{
    /**
     * @var ForeignRecordDemand
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new ForeignRecordDemand();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getStoragePageReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function setStoragePageSetsStoragePageForString()
    {
        $this->subject->setStoragePage('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function getrestrictForeignRecordsToStoragePageReturnsInitialValue()
    {
        self::assertFalse($this->subject->getRestrictForeignRecordsToStoragePage());
    }

    /**
     * @test
     */
    public function setRestrictForeignRecordsToStoragePageSetsValueForBoolean()
    {
        $this->subject->setRestrictForeignRecordsToStoragePage(true);
        self::assertTrue($this->subject->getRestrictForeignRecordsToStoragePage());
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectIfEmptySettings()
    {
        $expected = new ForeignRecordDemand();
        self::assertEquals($expected, ForeignRecordDemand::createFromSettings());
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectWithSettings()
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
