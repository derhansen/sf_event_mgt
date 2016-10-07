<?php

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Registration.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Registration
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Registration();
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
    public function getFirstnameReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getFirstname()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setFirstnameForStringSetsFirstname()
    {
        $this->subject->setFirstname('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'firstname',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getLastnameReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getLastname()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setLastnameForStringSetsLastname()
    {
        $this->subject->setLastname('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'lastname',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getTitleReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getCompanyReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getCompany()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setCompanyForStringSetsCompany()
    {
        $this->subject->setCompany('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'company',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getAddressReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getZipReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getZip()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setZipForIntegerSetsZip()
    {
        $this->subject->setZip('01234');

        $this->assertAttributeEquals(
            '01234',
            'zip',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getCityReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getCity()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getCountryReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setCityForStringSetsCity()
    {
        $this->subject->setCity('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'city',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function setCountryForStringSetsCountry()
    {
        $this->subject->setCountry('A country');

        $this->assertAttributeEquals(
            'A country',
            'country',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getPhoneReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getPhone()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setPhoneForStringSetsPhone()
    {
        $this->subject->setPhone('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'phone',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getEmailReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'email',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getGenderReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getGender()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setGenderForStringSetsGender()
    {
        $this->subject->setGender('m');

        $this->assertAttributeEquals(
            'm',
            'gender',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getAccepttcReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            false,
            $this->subject->getAccepttc()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setAccepttcForBooleanSetsConfirmed()
    {
        $this->subject->setAccepttc(true);

        $this->assertAttributeEquals(
            true,
            'accepttc',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getWaitlistReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            false,
            $this->subject->getAccepttc()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setWaitlistForBooleanSetsWaitlist()
    {
        $this->subject->setWaitlist(true);

        $this->assertAttributeEquals(
            true,
            'waitlist',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getConfirmedReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            false,
            $this->subject->getConfirmed()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setConfirmedForBooleanSetsConfirmed()
    {
        $this->subject->setConfirmed(true);

        $this->assertAttributeEquals(
            true,
            'confirmed',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function isConfirmedReturnsConfirmedState()
    {
        $this->subject->setConfirmed(true);
        $this->assertTrue($this->subject->isConfirmed());
    }

    /**
     * @test
     * @return void
     */
    public function getPaidReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            false,
            $this->subject->getPaid()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setPaidForBooleanSetsPaid()
    {
        $this->subject->setPaid(true);

        $this->assertAttributeEquals(
            true,
            'paid',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function isPaidReturnsPaidState()
    {
        $this->subject->setPaid(true);
        $this->assertTrue($this->subject->isPaid());
    }

    /**
     * @test
     * @return void
     */
    public function getNotesReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getNotes());
    }

    /**
     * @test
     * @return void
     */
    public function setNotesForStringSetsNotes()
    {
        $this->subject->setNotes('This is a longer text');

        $this->assertAttributeEquals(
            'This is a longer text',
            'notes',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function setEventForEventSetsEvent()
    {
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();
        $this->subject->setEvent($event);
        $this->assertEquals($event, $this->subject->getEvent());
    }

    /**
     * @test
     * @return void
     */
    public function getMainRegistrationReturnsInitialValue()
    {
        $this->assertEquals(null, $this->subject->getMainRegistration());
    }

    /**
     * @test
     * @return void
     */
    public function setMainRegistrationForRegistrationSetsRegistration()
    {
        $registration = new \DERHANSEN\SfEventMgt\Domain\Model\Registration();
        $this->subject->setMainRegistration($registration);
        $this->assertEquals($registration, $this->subject->getMainRegistration());
    }

    /**
     * @test
     * @return void
     */
    public function getConfirmationUntilReturnsInitialValueForDateTime()
    {
        $this->assertSame(null, $this->subject->getConfirmationUntil());
    }

    /**
     * @test
     * @return void
     */
    public function setConfirmationUntilForDateTimeSetsConfirmationUntil()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setConfirmationUntil($dateTimeFixture);

        $this->assertAttributeEquals(
            $dateTimeFixture,
            'confirmationUntil',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getDateOfBirthReturnsInitialValueForDateTime()
    {
        $this->assertNull($this->subject->getDateOfBirth());
    }

    /**
     * @test
     * @return void
     */
    public function setDateOfBirthForDateTimeSetsDateOfBirth()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setDateOfBirth($dateTimeFixture);

        $this->assertAttributeEquals(
            $dateTimeFixture,
            'dateOfBirth',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function getHiddenReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            false,
            $this->subject->getHidden()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setHiddenSetsHiddenFlag()
    {
        $this->subject->setHidden(true);
        $this->assertTrue($this->subject->getHidden());
    }

    /**
     * @test
     * @return void
     */
    public function amountOfRegistrationReturnsInitialValue()
    {
        $this->assertEquals(1, $this->subject->getAmountOfRegistrations());
    }

    /**
     * @test
     * @return void
     */
    public function amountOfRegistrationSetsAmountOfRegistrations()
    {
        $this->subject->setAmountOfRegistrations(2);
        $this->assertEquals(2, $this->subject->getAmountOfRegistrations());
    }

    /**
     * @test
     * @return void
     */
    public function ignoreNotificationsReturnsInitialValue()
    {
        $this->assertFalse($this->subject->getIgnoreNotifications());
    }

    /**
     * @test
     * @return void
     */
    public function ignoreNotificationsCanBeSet()
    {
        $this->subject->setIgnoreNotifications(true);
        $this->assertTrue($this->subject->getIgnoreNotifications());
    }

    /**
     * @test
     * @return void
     */
    public function getLanguageReturnsDefaultForString()
    {
        $this->assertEmpty($this->subject->getLanguage());
    }

    /**
     * @test
     * @return void
     */
    public function setLanguageSetsGivenLanguage()
    {
        $this->subject->setLanguage('de');
        $this->assertEquals('de', $this->subject->getLanguage());
    }

    /**
     * @test
     * @return void
     */
    public function getRecaptchaReturnsDefaultForString()
    {
        $this->assertEmpty($this->subject->getRecaptcha());
    }

    /**
     * @test
     * @return void
     */
    public function setRecaptchaSetsGivenLanguage()
    {
        $this->subject->setRecaptcha('1234567890');
        $this->assertEquals('1234567890', $this->subject->getRecaptcha());
    }

    /**
     * @test
     * @return void
     */
    public function getFeUserReturnsInitialValue()
    {
        $this->assertNull($this->subject->getFeUser());
    }

    /**
     * @test
     * @return void
     */
    public function setFeUserSetsFeUser()
    {
        $user = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();
        $this->subject->setFeUser($user);
        $this->assertSame($this->subject->getFeUser(), $user);
    }

    /**
     * @test
     * @return void
     */
    public function getPaymentmethodReturnsInitialValue()
    {
        $this->assertEmpty($this->subject->getPaymentmethod());
    }

    /**
     * @test
     * @return void
     */
    public function setPaymentmethodSetsPaymentmethod()
    {
        $this->subject->setPaymentmethod('invoice');
        $this->assertEquals('invoice', $this->subject->getPaymentmethod());
    }

    /**
     * @test
     * @return void
     */
    public function getPaymentReferenceReturnsInitialValue()
    {
        $this->assertEmpty($this->subject->getPaymentReference());
    }

    /**
     * @test
     * @return void
     */
    public function setPaymentReferenceSetsPaymentmethod()
    {
        $this->subject->setPaymentReference('paid-1234567890');
        $this->assertEquals('paid-1234567890', $this->subject->getPaymentReference());
    }

}
