<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use PHPUnit\Framework\Attributes\Test;
use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class SearchDemandTest extends UnitTestCase
{
    protected SearchDemand $subject;

    protected function setUp(): void
    {
        $this->subject = new SearchDemand();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getStartDateReturnsNullIfNoValueSet(): void
    {
        self::assertNull(
            $this->subject->getStartDate()
        );
    }

    #[Test]
    public function getStartDateReturnsGivenValueIfValueSet(): void
    {
        $this->subject->setStartDate(new DateTime('01.01.2014 10:00:00'));
        self::assertEquals(
            new DateTime('01.01.2014 10:00:00'),
            $this->subject->getStartDate()
        );
    }

    #[Test]
    public function getEndDateReturnsNullIfNoValueSet(): void
    {
        self::assertNull(
            $this->subject->getEndDate()
        );
    }

    #[Test]
    public function getEndDateReturnsGivenValueIfValueSet(): void
    {
        $this->subject->setEndDate(new DateTime('01.01.2014 10:00:00'));
        self::assertEquals(
            new DateTime('01.01.2014 10:00:00'),
            $this->subject->getEndDate()
        );
    }

    #[Test]
    public function getSearchReturnsEmptyStringIfNotSet(): void
    {
        self::assertEquals('', $this->subject->getSearch());
    }

    #[Test]
    public function getSearchReturnsGivenValueIfSet(): void
    {
        $this->subject->setSearch('Test');
        self::assertEquals(
            'Test',
            $this->subject->getSearch()
        );
    }

    #[Test]
    public function getFieldsReturnsEmptyStringIfNotSet(): void
    {
        self::assertEmpty($this->subject->getFields());
    }

    #[Test]
    public function getFieldsReturnsGivenValueIfSet(): void
    {
        $this->subject->setFields('Field1,Field2');
        self::assertEquals(
            'Field1,Field2',
            $this->subject->getFields()
        );
    }

    #[Test]
    public function getHasQueryReturnsFalseIfNoQuerySet(): void
    {
        self::assertFalse($this->subject->getHasQuery());
    }

    #[Test]
    public function getHasQueryReturnsTrueIfSearchSet(): void
    {
        $this->subject->setSearch('Test');
        self::assertTrue($this->subject->getHasQuery());
    }

    #[Test]
    public function getHasQueryReturnsTrueIfStartDateSet(): void
    {
        $this->subject->setStartDate(new DateTime());
        self::assertTrue($this->subject->getHasQuery());
    }

    #[Test]
    public function getHasQueryReturnsTrueIfEndDateSet(): void
    {
        $this->subject->setEndDate(new DateTime());
        self::assertTrue($this->subject->getHasQuery());
    }

    #[Test]
    public function toArrayReturnsExpectedArray(): void
    {
        $startDate = new DateTime('01.01.2020 00:00');
        $endDate = new DateTime('01.01.2020 23:59:59');

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

    #[Test]
    public function fromArrayReturnsExpectedObjectForEmptyData(): void
    {
        $searchDemand = new SearchDemand();
        self::assertEquals($searchDemand, SearchDemand::fromArray([]));
    }

    #[Test]
    public function fromArrayReturnsExpectedObjectForGivenData(): void
    {
        $startDate = new DateTime('01.01.2020 00:00');
        $endDate = new DateTime('01.01.2020 23:59:59');

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
