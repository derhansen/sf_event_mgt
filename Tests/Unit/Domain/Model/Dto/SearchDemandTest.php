<?php

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

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
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand();
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
     * @test
     * @return void
     */
    public function getStartDateReturnsNullIfNoValueSet()
    {
        $this->assertSame(
            null,
            $this->subject->getStartDate()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getStartDateReturnsGivenValueIfValueSet()
    {
        $this->subject->setStartDate(new \DateTime('01.01.2014 10:00:00'));
        $this->assertEquals(
            new \DateTime('01.01.2014 10:00:00'),
            $this->subject->getStartDate()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getEndDateReturnsNullIfNoValueSet()
    {
        $this->assertSame(
            null,
            $this->subject->getEndDate()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getEndDateReturnsGivenValueIfValueSet()
    {
        $this->subject->setEndDate(new \DateTime('01.01.2014 10:00:00'));
        $this->assertEquals(
            new \DateTime('01.01.2014 10:00:00'),
            $this->subject->getEndDate()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getSearchReturnsEmptyStringIfNotSet()
    {
        $this->assertEquals('', $this->subject->getSearch());
    }

    /**
     * @test
     * @return void
     */
    public function getSearchReturnsGivenValueIfSet()
    {
        $this->subject->setSearch('Test');
        $this->assertEquals(
            'Test',
            $this->subject->getSearch()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getFieldsReturnsEmptyStringIfNotSet()
    {
        $this->assertEmpty($this->subject->getFields());
    }

    /**
     * @test
     * @return void
     */
    public function getFieldsReturnsGivenValueIfSet()
    {
        $this->subject->setFields('Field1,Field2');
        $this->assertEquals(
            'Field1,Field2',
            $this->subject->getFields()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getHasQueryReturnsFalseIfNoQuerySet()
    {
        $this->assertEquals(false, $this->subject->getHasQuery());
    }

    /**
     * @test
     * @return void
     */
    public function getHasQueryReturnsTrueIfSearchSet()
    {
        $this->subject->setSearch('Test');
        $this->assertEquals(true, $this->subject->getHasQuery());
    }

    /**
     * @test
     * @return void
     */
    public function getHasQueryReturnsTrueIfStartDateSet()
    {
        $this->subject->setStartDate(new \DateTime());
        $this->assertEquals(true, $this->subject->getHasQuery());
    }

    /**
     * @test
     * @return void
     */
    public function getHasQueryReturnsTrueIfEndDateSet()
    {
        $this->subject->setEndDate(new \DateTime());
        $this->assertEquals(true, $this->subject->getHasQuery());
    }

}
