<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\Speaker;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

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
    protected $subject;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->subject = new Speaker();
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
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Firstname Lastname');

        self::assertAttributeEquals(
            'Firstname Lastname',
            'name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getJobTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getJobTitle()
        );
    }

    /**
     * @test
     */
    public function setJobTitleForStringSetsJobTitle()
    {
        $this->subject->setJobTitle('Web-Developer');

        self::assertAttributeEquals(
            'Web-Developer',
            'jobTitle',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDescriptionTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionTitleForStringSetsDescription()
    {
        $this->subject->setDescription('A description');

        self::assertAttributeEquals(
            'A description',
            'description',
            $this->subject
        );
    }

    /**
     * Test if initial value for image is returned
     *
     * @test
     */
    public function getImageReturnsInitialValueForImage()
    {
        self::assertNull($this->subject->getImage());
    }

    /**
     * Test if image can be set
     *
     * @test
     */
    public function setImageForFileReferenceSetsImage()
    {
        $image = new FileReference();
        $this->subject->setImage($image);

        self::assertAttributeEquals(
            $image,
            'image',
            $this->subject
        );
    }
}
