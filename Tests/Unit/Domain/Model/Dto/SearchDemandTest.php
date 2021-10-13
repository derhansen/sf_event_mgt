<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand
 */
class SearchDemandTest extends UnitTestCase
{
    /**
     * @var SearchDemand
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new SearchDemand();
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

    /**
     * @test
     */
    public function toArrayReturnsExpectedArray()
    {
        $startDate = new \DateTime('01.01.2020 00:00');
        $endDate = new \DateTime('01.01.2020 23:59:59');

        $searchDemand = new SearchDemand();
        $searchDemand->setSearch('search');
        $searchDemand->setFields('fields');
        $searchDemand->setStartDate($startDate);
        $searchDemand->setEndDate($endDate);

        $expected = [
            'search' => 'search',
            'fields' => 'fields',
            'startDate' => $startDate->format(DateTime::RFC3339),
            'endDate' => $endDate->format(DateTime::RFC3339),
        ];

        self::assertEquals($expected, $searchDemand->toArray());
    }

    /**
     * @test
     */
    public function fromArrayReturnsExpectedObjectForEmptyData()
    {
        $searchDemand = new SearchDemand();
        self::assertEquals($searchDemand, SearchDemand::fromArray([]));
    }

    /**
     * @test
     */
    public function fromArrayReturnsExpectedObjectForGivenData()
    {
        $startDate = new \DateTime('01.01.2020 00:00');
        $endDate = new \DateTime('01.01.2020 23:59:59');

        $searchDemand = new SearchDemand();
        $searchDemand->setSearch('search');
        $searchDemand->setFields('fields');
        $searchDemand->setStartDate($startDate);
        $searchDemand->setEndDate($endDate);

        $data = [
            'search' => 'search',
            'fields' => 'fields',
            'startDate' => $startDate->format(DateTime::RFC3339),
            'endDate' => $endDate->format(DateTime::RFC3339),
        ];

        self::assertEquals($searchDemand, SearchDemand::fromArray($data));
    }
}
