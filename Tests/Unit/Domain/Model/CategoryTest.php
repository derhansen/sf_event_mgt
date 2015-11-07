<?php

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

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

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Category.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Category
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Category();
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
     * Test if initial value for title is returned
     *
     * @test
     * @return void
     */
    public function getTitleReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * Test if title can be set
     *
     * @test
     * @return void
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * Test if parent can be set
     *
     * @test
     * @return void
     */
    public function setParentForCategoryCanBeSet()
    {
        $parent = new \DERHANSEN\SfEventMgt\Domain\Model\Category();
        $this->subject->setParent($parent);
        $this->assertEquals($parent, $this->subject->getParent());
    }
}
