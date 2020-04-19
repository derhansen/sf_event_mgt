<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Registration.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Registration
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->subject = new Registration();
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'firstname',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'lastname',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'company',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );
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

        self::assertAttributeEquals(
            '01234',
            'zip',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'city',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function setCountryForStringSetsCountry()
    {
        $this->subject->setCountry('A country');

        self::assertAttributeEquals(
            'A country',
            'country',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'phone',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'email',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'm',
            'gender',
            $this->subject
        );
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

        self::assertAttributeEquals(
            true,
            'accepttc',
            $this->subject
        );
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

        self::assertAttributeEquals(
            true,
            'waitlist',
            $this->subject
        );
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

        self::assertAttributeEquals(
            true,
            'confirmed',
            $this->subject
        );
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

        self::assertAttributeEquals(
            true,
            'paid',
            $this->subject
        );
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

        self::assertAttributeEquals(
            'This is a longer text',
            'notes',
            $this->subject
        );
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

        self::assertAttributeEquals(
            $dateTimeFixture,
            'confirmationUntil',
            $this->subject
        );
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

        self::assertAttributeEquals(
            $dateTimeFixture,
            'dateOfBirth',
            $this->subject
        );
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
    public function getRecaptchaReturnsDefaultForString()
    {
        self::assertEmpty($this->subject->getRecaptcha());
    }

    /**
     * @test
     */
    public function setRecaptchaSetsGivenLanguage()
    {
        $this->subject->setRecaptcha('1234567890');
        self::assertEquals('1234567890', $this->subject->getRecaptcha());
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
