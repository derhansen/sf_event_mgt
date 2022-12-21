<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class RegistrationTest extends UnitTestCase
{
    protected Registration $subject;

    protected function setUp(): void
    {
        $this->subject = new Registration();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getFirstnameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getFirstname()
        );
    }

    /**
     * @test
     */
    public function setFirstnameForStringSetsFirstname(): void
    {
        $this->subject->setFirstname('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getFirstname());
    }

    /**
     * @test
     */
    public function getLastnameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getLastname()
        );
    }

    /**
     * @test
     */
    public function setLastnameForStringSetsLastname(): void
    {
        $this->subject->setLastname('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getLastname());
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle(): void
    {
        $this->subject->setTitle('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function getCompanyReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCompany()
        );
    }

    /**
     * @test
     */
    public function setCompanyForStringSetsCompany(): void
    {
        $this->subject->setCompany('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getCompany());
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress(): void
    {
        $this->subject->setAddress('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getAddress());
    }

    /**
     * @test
     */
    public function getZipReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getZip()
        );
    }

    /**
     * @test
     */
    public function setZipForIntegerSetsZip(): void
    {
        $this->subject->setZip('01234');
        self::assertEquals('01234', $this->subject->getZip());
    }

    /**
     * @test
     */
    public function getCityReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCity()
        );
    }

    /**
     * @test
     */
    public function getCountryReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     */
    public function setCityForStringSetsCity(): void
    {
        $this->subject->setCity('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getCity());
    }

    /**
     * @test
     */
    public function setCountryForStringSetsCountry(): void
    {
        $this->subject->setCountry('A country');
        self::assertEquals('A country', $this->subject->getCountry());
    }

    /**
     * @test
     */
    public function getPhoneReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getPhone()
        );
    }

    /**
     * @test
     */
    public function setPhoneForStringSetsPhone(): void
    {
        $this->subject->setPhone('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getPhone());
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail(): void
    {
        $this->subject->setEmail('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function getGenderReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function setGenderForStringSetsGender(): void
    {
        $this->subject->setGender('m');
        self::assertEquals('m', $this->subject->getGender());
    }

    /**
     * @test
     */
    public function getAccepttcReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getAccepttc()
        );
    }

    /**
     * @test
     */
    public function setAccepttcForBooleanSetsConfirmed(): void
    {
        $this->subject->setAccepttc(true);
        self::assertTrue($this->subject->getAccepttc());
    }

    /**
     * @test
     */
    public function getWaitlistReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getAccepttc()
        );
    }

    /**
     * @test
     */
    public function setWaitlistForBooleanSetsWaitlist(): void
    {
        $this->subject->setWaitlist(true);
        self::assertTrue($this->subject->getWaitlist());
    }

    /**
     * @test
     */
    public function getConfirmedReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getConfirmed()
        );
    }

    /**
     * @test
     */
    public function setConfirmedForBooleanSetsConfirmed(): void
    {
        $this->subject->setConfirmed(true);
        self::assertTrue($this->subject->getConfirmed());
    }

    /**
     * @test
     */
    public function isConfirmedReturnsConfirmedState(): void
    {
        $this->subject->setConfirmed(true);
        self::assertTrue($this->subject->isConfirmed());
    }

    /**
     * @test
     */
    public function getPaidReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getPaid()
        );
    }

    /**
     * @test
     */
    public function setPaidForBooleanSetsPaid(): void
    {
        $this->subject->setPaid(true);
        self::assertTrue($this->subject->getPaid());
    }

    /**
     * @test
     */
    public function isPaidReturnsPaidState(): void
    {
        $this->subject->setPaid(true);
        self::assertTrue($this->subject->isPaid());
    }

    /**
     * @test
     */
    public function getNotesReturnsInitialValueForString(): void
    {
        self::assertSame('', $this->subject->getNotes());
    }

    /**
     * @test
     */
    public function setNotesForStringSetsNotes(): void
    {
        $this->subject->setNotes('This is a longer text');
        self::assertEquals('This is a longer text', $this->subject->getNotes());
    }

    /**
     * @test
     */
    public function setEventForEventSetsEvent(): void
    {
        $event = new Event();
        $this->subject->setEvent($event);
        self::assertEquals($event, $this->subject->getEvent());
    }

    /**
     * @test
     */
    public function getMainRegistrationReturnsInitialValue(): void
    {
        self::assertNull($this->subject->getMainRegistration());
    }

    /**
     * @test
     */
    public function setMainRegistrationForRegistrationSetsRegistration(): void
    {
        $registration = new Registration();
        $this->subject->setMainRegistration($registration);
        self::assertEquals($registration, $this->subject->getMainRegistration());
    }

    /**
     * @test
     */
    public function getConfirmationUntilReturnsInitialValueForDateTime(): void
    {
        self::assertNull($this->subject->getConfirmationUntil());
    }

    /**
     * @test
     */
    public function setConfirmationUntilForDateTimeSetsConfirmationUntil(): void
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setConfirmationUntil($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getConfirmationUntil());
    }

    /**
     * @test
     */
    public function getRegistrationDateReturnsInitialValueForDateTime(): void
    {
        self::assertNull($this->subject->getRegistrationDate());
    }

    /**
     * @test
     */
    public function setRegistrationDateForDateTimeSetsRegistrationDate(): void
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setRegistrationDate($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getRegistrationDate());
    }

    /**
     * @test
     */
    public function getDateOfBirthReturnsInitialValueForDateTime(): void
    {
        self::assertNull($this->subject->getDateOfBirth());
    }

    /**
     * @test
     */
    public function setDateOfBirthForDateTimeSetsDateOfBirth(): void
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setDateOfBirth($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getDateOfBirth());
    }

    /**
     * @test
     */
    public function getHiddenReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getHidden()
        );
    }

    /**
     * @test
     */
    public function setHiddenSetsHiddenFlag(): void
    {
        $this->subject->setHidden(true);
        self::assertTrue($this->subject->getHidden());
    }

    /**
     * @test
     */
    public function amountOfRegistrationReturnsInitialValue(): void
    {
        self::assertEquals(1, $this->subject->getAmountOfRegistrations());
    }

    /**
     * @test
     */
    public function amountOfRegistrationSetsAmountOfRegistrations(): void
    {
        $this->subject->setAmountOfRegistrations(2);
        self::assertEquals(2, $this->subject->getAmountOfRegistrations());
    }

    /**
     * @test
     */
    public function ignoreNotificationsReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getIgnoreNotifications());
    }

    /**
     * @test
     */
    public function ignoreNotificationsCanBeSet(): void
    {
        $this->subject->setIgnoreNotifications(true);
        self::assertTrue($this->subject->getIgnoreNotifications());
    }

    /**
     * @test
     */
    public function getLanguageReturnsDefaultForString(): void
    {
        self::assertEmpty($this->subject->getLanguage());
    }

    /**
     * @test
     */
    public function setLanguageSetsGivenLanguage(): void
    {
        $this->subject->setLanguage('de');
        self::assertEquals('de', $this->subject->getLanguage());
    }

    /**
     * @test
     */
    public function getCaptchaReturnsDefaultForString(): void
    {
        self::assertEmpty($this->subject->getCaptcha());
    }

    /**
     * @test
     */
    public function setCaptchaSetsGivenLanguage(): void
    {
        $this->subject->setCaptcha('1234567890');
        self::assertEquals('1234567890', $this->subject->getCaptcha());
    }

    /**
     * @test
     */
    public function getFeUserReturnsInitialValue(): void
    {
        self::assertNull($this->subject->getFeUser());
    }

    /**
     * @test
     */
    public function setFeUserSetsFeUser(): void
    {
        $user = new FrontendUser();
        $this->subject->setFeUser($user);
        self::assertSame($this->subject->getFeUser(), $user);
    }

    /**
     * @test
     */
    public function getPaymentmethodReturnsInitialValue(): void
    {
        self::assertEmpty($this->subject->getPaymentmethod());
    }

    /**
     * @test
     */
    public function setPaymentmethodSetsPaymentmethod(): void
    {
        $this->subject->setPaymentmethod('invoice');
        self::assertEquals('invoice', $this->subject->getPaymentmethod());
    }

    /**
     * @test
     */
    public function getPaymentReferenceReturnsInitialValue(): void
    {
        self::assertEmpty($this->subject->getPaymentReference());
    }

    /**
     * @test
     */
    public function setPaymentReferenceSetsPaymentmethod(): void
    {
        $this->subject->setPaymentReference('paid-1234567890');
        self::assertEquals('paid-1234567890', $this->subject->getPaymentReference());
    }

    /**
     * @test
     */
    public function getFullnameReturnsExpectedFullname(): void
    {
        $this->subject->setFirstname('Torben');
        $this->subject->setLastname('Hansen');
        self::assertEquals('Torben Hansen', $this->subject->getFullname());
    }
}
