<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Registration.
 */
class RegistrationTest extends UnitTestCase
{
    /**
     * @var Registration
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new Registration();
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
    public function getFirstnameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFirstname()
        );
    }

    /**
     * @test
     */
    public function setFirstnameForStringSetsFirstname()
    {
        $this->subject->setFirstname('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getFirstname());
    }

    /**
     * @test
     */
    public function getLastnameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLastname()
        );
    }

    /**
     * @test
     */
    public function setLastnameForStringSetsLastname()
    {
        $this->subject->setLastname('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getLastname());
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function getCompanyReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCompany()
        );
    }

    /**
     * @test
     */
    public function setCompanyForStringSetsCompany()
    {
        $this->subject->setCompany('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getCompany());
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getAddress());
    }

    /**
     * @test
     */
    public function getZipReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getZip()
        );
    }

    /**
     * @test
     */
    public function setZipForIntegerSetsZip()
    {
        $this->subject->setZip('01234');
        self::assertEquals('01234', $this->subject->getZip());
    }

    /**
     * @test
     */
    public function getCityReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCity()
        );
    }

    /**
     * @test
     */
    public function getCountryReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     */
    public function setCityForStringSetsCity()
    {
        $this->subject->setCity('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getCity());
    }

    /**
     * @test
     */
    public function setCountryForStringSetsCountry()
    {
        $this->subject->setCountry('A country');
        self::assertEquals('A country', $this->subject->getCountry());
    }

    /**
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
     * @test
     */
    public function setPhoneForStringSetsPhone()
    {
        $this->subject->setPhone('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getPhone());
    }

    /**
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
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function getGenderReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function setGenderForStringSetsGender()
    {
        $this->subject->setGender('m');
        self::assertEquals('m', $this->subject->getGender());
    }

    /**
     * @test
     */
    public function getAccepttcReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getAccepttc()
        );
    }

    /**
     * @test
     */
    public function setAccepttcForBooleanSetsConfirmed()
    {
        $this->subject->setAccepttc(true);
        self::assertTrue($this->subject->getAccepttc());
    }

    /**
     * @test
     */
    public function getWaitlistReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getAccepttc()
        );
    }

    /**
     * @test
     */
    public function setWaitlistForBooleanSetsWaitlist()
    {
        $this->subject->setWaitlist(true);
        self::assertTrue($this->subject->getWaitlist());
    }

    /**
     * @test
     */
    public function getConfirmedReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getConfirmed()
        );
    }

    /**
     * @test
     */
    public function setConfirmedForBooleanSetsConfirmed()
    {
        $this->subject->setConfirmed(true);
        self::assertTrue($this->subject->getConfirmed());
    }

    /**
     * @test
     */
    public function isConfirmedReturnsConfirmedState()
    {
        $this->subject->setConfirmed(true);
        self::assertTrue($this->subject->isConfirmed());
    }

    /**
     * @test
     */
    public function getPaidReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getPaid()
        );
    }

    /**
     * @test
     */
    public function setPaidForBooleanSetsPaid()
    {
        $this->subject->setPaid(true);
        self::assertTrue($this->subject->getPaid());
    }

    /**
     * @test
     */
    public function isPaidReturnsPaidState()
    {
        $this->subject->setPaid(true);
        self::assertTrue($this->subject->isPaid());
    }

    /**
     * @test
     */
    public function getNotesReturnsInitialValueForString()
    {
        self::assertSame('', $this->subject->getNotes());
    }

    /**
     * @test
     */
    public function setNotesForStringSetsNotes()
    {
        $this->subject->setNotes('This is a longer text');
        self::assertEquals('This is a longer text', $this->subject->getNotes());
    }

    /**
     * @test
     */
    public function setEventForEventSetsEvent()
    {
        $event = new Event();
        $this->subject->setEvent($event);
        self::assertEquals($event, $this->subject->getEvent());
    }

    /**
     * @test
     */
    public function getMainRegistrationReturnsInitialValue()
    {
        self::assertNull($this->subject->getMainRegistration());
    }

    /**
     * @test
     */
    public function setMainRegistrationForRegistrationSetsRegistration()
    {
        $registration = new Registration();
        $this->subject->setMainRegistration($registration);
        self::assertEquals($registration, $this->subject->getMainRegistration());
    }

    /**
     * @test
     */
    public function getConfirmationUntilReturnsInitialValueForDateTime()
    {
        self::assertNull($this->subject->getConfirmationUntil());
    }

    /**
     * @test
     */
    public function setConfirmationUntilForDateTimeSetsConfirmationUntil()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setConfirmationUntil($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getConfirmationUntil());
    }

    /**
     * @test
     */
    public function getRegistrationDateReturnsInitialValueForDateTime()
    {
        self::assertNull($this->subject->getRegistrationDate());
    }

    /**
     * @test
     */
    public function setRegistrationDateForDateTimeSetsRegistrationDate()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setRegistrationDate($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getRegistrationDate());
    }

    /**
     * @test
     */
    public function getDateOfBirthReturnsInitialValueForDateTime()
    {
        self::assertNull($this->subject->getDateOfBirth());
    }

    /**
     * @test
     */
    public function setDateOfBirthForDateTimeSetsDateOfBirth()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setDateOfBirth($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getDateOfBirth());
    }

    /**
     * @test
     */
    public function getHiddenReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getHidden()
        );
    }

    /**
     * @test
     */
    public function setHiddenSetsHiddenFlag()
    {
        $this->subject->setHidden(true);
        self::assertTrue($this->subject->getHidden());
    }

    /**
     * @test
     */
    public function amountOfRegistrationReturnsInitialValue()
    {
        self::assertEquals(1, $this->subject->getAmountOfRegistrations());
    }

    /**
     * @test
     */
    public function amountOfRegistrationSetsAmountOfRegistrations()
    {
        $this->subject->setAmountOfRegistrations(2);
        self::assertEquals(2, $this->subject->getAmountOfRegistrations());
    }

    /**
     * @test
     */
    public function ignoreNotificationsReturnsInitialValue()
    {
        self::assertFalse($this->subject->getIgnoreNotifications());
    }

    /**
     * @test
     */
    public function ignoreNotificationsCanBeSet()
    {
        $this->subject->setIgnoreNotifications(true);
        self::assertTrue($this->subject->getIgnoreNotifications());
    }

    /**
     * @test
     */
    public function getLanguageReturnsDefaultForString()
    {
        self::assertEmpty($this->subject->getLanguage());
    }

    /**
     * @test
     */
    public function setLanguageSetsGivenLanguage()
    {
        $this->subject->setLanguage('de');
        self::assertEquals('de', $this->subject->getLanguage());
    }

    /**
     * @test
     */
    public function getCaptchaReturnsDefaultForString()
    {
        self::assertEmpty($this->subject->getCaptcha());
    }

    /**
     * @test
     */
    public function setCaptchaSetsGivenLanguage()
    {
        $this->subject->setCaptcha('1234567890');
        self::assertEquals('1234567890', $this->subject->getCaptcha());
    }

    /**
     * @test
     */
    public function getFeUserReturnsInitialValue()
    {
        self::assertNull($this->subject->getFeUser());
    }

    /**
     * @test
     */
    public function setFeUserSetsFeUser()
    {
        $user = new FrontendUser();
        $this->subject->setFeUser($user);
        self::assertSame($this->subject->getFeUser(), $user);
    }

    /**
     * @test
     */
    public function getPaymentmethodReturnsInitialValue()
    {
        self::assertEmpty($this->subject->getPaymentmethod());
    }

    /**
     * @test
     */
    public function setPaymentmethodSetsPaymentmethod()
    {
        $this->subject->setPaymentmethod('invoice');
        self::assertEquals('invoice', $this->subject->getPaymentmethod());
    }

    /**
     * @test
     */
    public function getPaymentReferenceReturnsInitialValue()
    {
        self::assertEmpty($this->subject->getPaymentReference());
    }

    /**
     * @test
     */
    public function setPaymentReferenceSetsPaymentmethod()
    {
        $this->subject->setPaymentReference('paid-1234567890');
        self::assertEquals('paid-1234567890', $this->subject->getPaymentReference());
    }

    /**
     * @test
     */
    public function getFullnameReturnsExpectedFullname()
    {
        $this->subject->setFirstname('Torben');
        $this->subject->setLastname('Hansen');
        self::assertEquals('Torben Hansen', $this->subject->getFullname());
    }
}
