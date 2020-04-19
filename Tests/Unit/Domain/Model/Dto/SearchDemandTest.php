<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SearchDemandTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->subject = new SearchDemand();
    }

    /**
     * Teardown
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getStartDateReturnsNullIfNoValueSet()
    {
        self::assertNull(
            $this->subject->getStartDate()
        );
    }

    /**
     * @test
     */
    public function getStartDateReturnsGivenValueIfValueSet()
    {
        $this->subject->setStartDate(new \DateTime('01.01.2014 10:00:00'));
        self::assertEquals(
            new \DateTime('01.01.2014 10:00:00'),
            $this->subject->getStartDate()
        );
    }

    /**
     * @test
     */
    public function getEndDateReturnsNullIfNoValueSet()
    {
        self::assertNull(
            $this->subject->getEndDate()
        );
    }

    /**
     * @test
     */
    public function getEndDateReturnsGivenValueIfValueSet()
    {
        $this->subject->setEndDate(new \DateTime('01.01.2014 10:00:00'));
        self::assertEquals(
            new \DateTime('01.01.2014 10:00:00'),
            $this->subject->getEndDate()
        );
    }

    /**
     * @test
     */
    public function getSearchReturnsEmptyStringIfNotSet()
    {
        self::assertEquals('', $this->subject->getSearch());
    }

    /**
     * @test
     */
    public function getSearchReturnsGivenValueIfSet()
    {
        $this->subject->setSearch('Test');
        self::assertEquals(
            'Test',
            $this->subject->getSearch()
        );
    }

    /**
     * @test
     */
    public function getFieldsReturnsEmptyStringIfNotSet()
    {
        self::assertEmpty($this->subject->getFields());
    }

    /**
     * @test
     */
    public function getFieldsReturnsGivenValueIfSet()
    {
        $this->subject->setFields('Field1,Field2');
        self::assertEquals(
            'Field1,Field2',
            $this->subject->getFields()
        );
    }

    /**
     * @test
     */
    public function getHasQueryReturnsFalseIfNoQuerySet()
    {
        self::assertFalse($this->subject->getHasQuery());
    }

    /**
     * @test
     */
    public function getHasQueryReturnsTrueIfSearchSet()
    {
        $this->subject->setSearch('Test');
        self::assertTrue($this->subject->getHasQuery());
    }

    /**
     * @test
     */
    public function getHasQueryReturnsTrueIfStartDateSet()
    {
        $this->subject->setStartDate(new \DateTime());
        self::assertTrue($this->subject->getHasQuery());
    }

    /**
     * @test
     */
    public function getHasQueryReturnsTrueIfEndDateSet()
    {
        $this->subject->setEndDate(new \DateTime());
        self::assertTrue($this->subject->getHasQuery());
    }
}
