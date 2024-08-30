<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\Domain\Model\Speaker;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class SpeakerTest extends UnitTestCase
{
    protected Speaker $subject;

    protected function setUp(): void
    {
        $this->subject = new Speaker();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    #[Test]
    public function setNameForStringSetsName(): void
    {
        $this->subject->setName('Firstname Lastname');
        self::assertSame('Firstname Lastname', $this->subject->getName());
    }

    #[Test]
    public function getJobTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getJobTitle()
        );
    }

    #[Test]
    public function setJobTitleForStringSetsJobTitle(): void
    {
        $this->subject->setJobTitle('Web-Developer');
        self::assertSame('Web-Developer', $this->subject->getJobTitle());
    }

    #[Test]
    public function getDescriptionTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    #[Test]
    public function setDescriptionTitleForStringSetsDescription(): void
    {
        $this->subject->setDescription('A description');
        self::assertSame('A description', $this->subject->getDescription());
    }

    /**
     * Test if initial value for image is returned
     */
    #[Test]
    public function getImageReturnsInitialValueForImage(): void
    {
        self::assertNull($this->subject->getImage());
    }

    /**
     * Test if image can be set
     */
    #[Test]
    public function setImageForFileReferenceSetsImage(): void
    {
        $image = new FileReference();
        $this->subject->setImage($image);
        self::assertSame($image, $this->subject->getImage());
    }
}
