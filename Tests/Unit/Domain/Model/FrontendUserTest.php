<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class FrontendUserTest extends UnitTestCase
{
    protected FrontendUser $subject;

    protected function setUp(): void
    {
        $this->subject = new FrontendUser();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getUsernameReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getUsername());
    }

    #[Test]
    public function setUsernameSetsUsername(): void
    {
        $this->subject->setUsername('typo3');
        self::assertEquals('typo3', $this->subject->getUsername());
    }

    #[Test]
    public function getNameReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getName());
    }

    #[Test]
    public function setNameSetsName(): void
    {
        $this->subject->setName('Random Name');
        self::assertEquals('Random Name', $this->subject->getName());
    }

    #[Test]
    public function getFirstNameReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getFirstName());
    }

    #[Test]
    public function setFirstNameSetsFirstName(): void
    {
        $this->subject->setFirstName('Firstname');
        self::assertEquals('Firstname', $this->subject->getFirstName());
    }

    #[Test]
    public function getMiddleNameReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getMiddleName());
    }

    #[Test]
    public function setMiddleNameSetsMiddleName(): void
    {
        $this->subject->setMiddleName('Middlename');
        self::assertEquals('Middlename', $this->subject->getMiddleName());
    }

    #[Test]
    public function getLastNameReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getLastName());
    }

    #[Test]
    public function setLastNameSetsLastName(): void
    {
        $this->subject->setLastName('Lastname');
        self::assertEquals('Lastname', $this->subject->getLastName());
    }

    #[Test]
    public function getAddressReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getAddress());
    }

    #[Test]
    public function setAddressSetsAddress(): void
    {
        $this->subject->setAddress('Address');
        self::assertEquals('Address', $this->subject->getAddress());
    }

    #[Test]
    public function getTelephoneReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getTelephone());
    }

    #[Test]
    public function setTelephoneSetsTelephone(): void
    {
        $this->subject->setTelephone('Telephone');
        self::assertEquals('Telephone', $this->subject->getTelephone());
    }

    #[Test]
    public function getFaxReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getFax());
    }

    #[Test]
    public function setFaxSetsFax(): void
    {
        $this->subject->setFax('Fax');
        self::assertEquals('Fax', $this->subject->getFax());
    }

    #[Test]
    public function getEmailReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getEmail());
    }

    #[Test]
    public function setEmailSetsEmail(): void
    {
        $this->subject->setEmail('email');
        self::assertEquals('email', $this->subject->getEmail());
    }

    #[Test]
    public function getTitleReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getTitle());
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $this->subject->setTitle('title');
        self::assertEquals('title', $this->subject->getTitle());
    }

    #[Test]
    public function getZipReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getZip());
    }

    #[Test]
    public function setZipSetsZip(): void
    {
        $this->subject->setZip('12345');
        self::assertEquals('12345', $this->subject->getZip());
    }

    #[Test]
    public function getCityReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getCity());
    }

    #[Test]
    public function setCitySetsCity(): void
    {
        $this->subject->setCity('city');
        self::assertEquals('city', $this->subject->getCity());
    }

    #[Test]
    public function getCountryReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getCountry());
    }

    #[Test]
    public function setCountrySetsCountry(): void
    {
        $this->subject->setCountry('country');
        self::assertEquals('country', $this->subject->getCountry());
    }

    #[Test]
    public function getWwwReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getWww());
    }

    #[Test]
    public function setWwwSetsWww(): void
    {
        $this->subject->setWww('typo3.org');
        self::assertEquals('typo3.org', $this->subject->getWww());
    }

    #[Test]
    public function getCompanyReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getCompany());
    }

    #[Test]
    public function setCompanySetsCompany(): void
    {
        $this->subject->setCompany('company');
        self::assertEquals('company', $this->subject->getCompany());
    }

    #[Test]
    public function getImageReturnsInitialValue(): void
    {
        self::assertEquals(new ObjectStorage(), $this->subject->getImage());
    }

    #[Test]
    public function setImageSetsImage(): void
    {
        $image = new ObjectStorage();
        $this->subject->setImage($image);
        self::assertSame($image, $this->subject->getImage());
    }
}
