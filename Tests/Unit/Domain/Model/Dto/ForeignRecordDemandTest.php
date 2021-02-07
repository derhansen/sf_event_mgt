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
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ForeignRecordDemandTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand
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
}
