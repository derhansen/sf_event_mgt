<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\FrontendUser
 */
class FrontendUserTest extends UnitTestCase
{
    /**
     * @var FrontendUser
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new FrontendUser();
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
    public function getUsernameReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getUsername());
    }

    /**
     * @test
     */
    public function setUsernameSetsUsername()
    {
        $this->subject->setUsername('typo3');
        self::assertEquals('typo3', $this->subject->getUsername());
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getName());
    }

    /**
     * @test
     */
    public function setNameSetsName()
    {
        $this->subject->setName('Random Name');
        self::assertEquals('Random Name', $this->subject->getName());
    }

    /**
     * @test
     */
    public function getFirstNameReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getFirstName());
    }

    /**
     * @test
     */
    public function setFirstNameSetsFirstName()
    {
        $this->subject->setFirstName('Firstname');
        self::assertEquals('Firstname', $this->subject->getFirstName());
    }

    /**
     * @test
     */
    public function getMiddleNameReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getMiddleName());
    }

    /**
     * @test
     */
    public function setMiddleNameSetsMiddleName()
    {
        $this->subject->setMiddleName('Middlename');
        self::assertEquals('Middlename', $this->subject->getMiddleName());
    }

    /**
     * @test
     */
    public function getLastNameReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getLastName());
    }

    /**
     * @test
     */
    public function setLastNameSetsLastName()
    {
        $this->subject->setLastName('Lastname');
        self::assertEquals('Lastname', $this->subject->getLastName());
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getAddress());
    }

    /**
     * @test
     */
    public function setAddressSetsAddress()
    {
        $this->subject->setAddress('Address');
        self::assertEquals('Address', $this->subject->getAddress());
    }

    /**
     * @test
     */
    public function getTelephoneReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getTelephone());
    }

    /**
     * @test
     */
    public function setTelephoneSetsTelephone()
    {
        $this->subject->setTelephone('Telephone');
        self::assertEquals('Telephone', $this->subject->getTelephone());
    }

    /**
     * @test
     */
    public function getFaxReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getFax());
    }

    /**
     * @test
     */
    public function setFaxSetsFax()
    {
        $this->subject->setFax('Fax');
        self::assertEquals('Fax', $this->subject->getFax());
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function setEmailSetsEmail()
    {
        $this->subject->setEmail('email');
        self::assertEquals('email', $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function setTitleSetsTitle()
    {
        $this->subject->setTitle('title');
        self::assertEquals('title', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function getZipReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getZip());
    }

    /**
     * @test
     */
    public function setZipSetsZip()
    {
        $this->subject->setZip('12345');
        self::assertEquals('12345', $this->subject->getZip());
    }

    /**
     * @test
     */
    public function getCityReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getCity());
    }

    /**
     * @test
     */
    public function setCitySetsCity()
    {
        $this->subject->setCity('city');
        self::assertEquals('city', $this->subject->getCity());
    }

    /**
     * @test
     */
    public function getCountryReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getCountry());
    }

    /**
     * @test
     */
    public function setCountrySetsCountry()
    {
        $this->subject->setCountry('country');
        self::assertEquals('country', $this->subject->getCountry());
    }

    /**
     * @test
     */
    public function getWwwReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getWww());
    }

    /**
     * @test
     */
    public function setWwwSetsWww()
    {
        $this->subject->setWww('typo3.org');
        self::assertEquals('typo3.org', $this->subject->getWww());
    }

    /**
     * @test
     */
    public function getCompanyReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getCompany());
    }

    /**
     * @test
     */
    public function setCompanySetsCompany()
    {
        $this->subject->setCompany('company');
        self::assertEquals('company', $this->subject->getCompany());
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValue()
    {
        self::assertEquals(new ObjectStorage(), $this->subject->getImage());
    }

    /**
     * @test
     */
    public function setImageSetsImage()
    {
        $image = new ObjectStorage();
        $this->subject->setImage($image);
        self::assertSame($image, $this->subject->getImage());
    }
}
