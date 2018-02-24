<?php

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Speaker.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SpeakerTest extends UnitTestCase
{
    /**
     * Speaker object
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Speaker
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Speaker();
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
    public function getNameReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Firstname Lastname');

        $this->assertAttributeEquals(
            'Firstname Lastname',
            'name',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getJobTitleReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getJobTitle()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setJobTitleForStringSetsJobTitle()
    {
        $this->subject->setJobTitle('Web-Developer');

        $this->assertAttributeEquals(
            'Web-Developer',
            'jobTitle',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getDescriptionTitleReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setDescriptionTitleForStringSetsDescription()
    {
        $this->subject->setDescription('A description');

        $this->assertAttributeEquals(
            'A description',
            'description',
            $this->subject
        );
    }

    /**
     * Test if initial value for image is returned
     *
     * @test
     * @return void
     */
    public function getImageReturnsInitialValueForImage()
    {
        $this->assertNull($this->subject->getImage());
    }

    /**
     * Test if image can be set
     *
     * @test
     * @return void
     */
    public function setImageForFileReferenceSetsImage()
    {
        $image = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setImage($image);

        $this->assertAttributeEquals(
            $image,
            'image',
            $this->subject
        );
    }
}
