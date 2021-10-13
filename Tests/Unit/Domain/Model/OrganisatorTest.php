<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\Organisator;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Organisator.
 */
class OrganisatorTest extends UnitTestCase
{
    /**
     * Organisator object
     *
     * @var Organisator
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new Organisator();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if initial value for name is returned
     *
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
     * Test if name can be set
     *
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getName());
    }

    /**
     * Test if initial value for email is returned
     *
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * Test if email can be set
     *
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('mail@domain.tld');
        self::assertEquals('mail@domain.tld', $this->subject->getEmail());
    }

    /**
     * Test if initial value for phone is returned
     *
     * @test
     */
    public function getPhoneReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPhone()
        );
    }

    /**
     * Test if email can be set
     *
     * @test
     */
    public function setPhoneForStringSetsPhone()
    {
        $this->subject->setPhone('+49 123 4567890');
        self::assertEquals('+49 123 4567890', $this->subject->getPhone());
    }

    /**
     * Test if initial value for image is returned
     *
     * @test
     */
    public function getImageReturnsInitialValueForfiles()
    {
        self::assertNull($this->subject->getImage());
    }

    /**
     * Test if image can be set
     *
     * @test
     */
    public function setImageForObjectStorageContainingImageSetsImage()
    {
        $file = new FileReference();
        $this->subject->setImage($file);
        self::assertEquals($file, $this->subject->getImage());
    }
}
