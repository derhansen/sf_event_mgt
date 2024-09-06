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
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class OrganisatorTest extends UnitTestCase
{
    protected Organisator $subject;

    protected function setUp(): void
    {
        $this->subject = new Organisator();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if initial value for name is returned
     */
    #[Test]
    public function getNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * Test if name can be set
     */
    #[Test]
    public function setNameForStringSetsName(): void
    {
        $this->subject->setName('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getName());
    }

    /**
     * Test if initial value for email is returned
     */
    #[Test]
    public function getEmailReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * Test if email can be set
     */
    #[Test]
    public function setEmailForStringSetsEmail(): void
    {
        $this->subject->setEmail('mail@domain.tld');
        self::assertEquals('mail@domain.tld', $this->subject->getEmail());
    }

    /**
     * Test if initial value for phone is returned
     */
    #[Test]
    public function getPhoneReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getPhone()
        );
    }

    /**
     * Test if phone can be set
     */
    #[Test]
    public function setPhoneForStringSetsPhone(): void
    {
        $this->subject->setPhone('+49 123 4567890');
        self::assertEquals('+49 123 4567890', $this->subject->getPhone());
    }

    /**
     * Test if initial value for link is returned
     */
    #[Test]
    public function getLinkReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getLink()
        );
    }

    /**
     * Test if link can be set
     */
    #[Test]
    public function setLinkForStringSetsLink(): void
    {
        $this->subject->setLink('https://www.derhansen.com');
        self::assertEquals('https://www.derhansen.com', $this->subject->getLink());
    }

    /**
     * Test if initial value for image is returned
     */
    #[Test]
    public function getImageReturnsInitialValueForfiles(): void
    {
        self::assertNull($this->subject->getImage());
    }

    /**
     * Test if image can be set
     */
    #[Test]
    public function setImageForObjectStorageContainingImageSetsImage(): void
    {
        $file = new FileReference();
        $this->subject->setImage($file);
        self::assertEquals($file, $this->subject->getImage());
    }
}
