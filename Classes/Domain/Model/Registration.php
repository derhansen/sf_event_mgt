<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Registration
 */
class Registration extends AbstractEntity
{
    protected string $firstname = '';
    protected string $lastname = '';
    protected string $title = '';
    protected string $company = '';
    protected string $address = '';
    protected string $zip = '';
    protected string $city = '';
    protected string $country = '';
    protected string $phone = '';
    protected string $email = '';
    protected bool $ignoreNotifications = false;
    protected string $gender = '';
    protected ?DateTime $dateOfBirth = null;
    protected bool $accepttc = false;
    protected bool $confirmed = false;
    protected bool $paid = false;
    protected string $notes = '';
    protected ?Event $event = null;
    protected ?Registration $mainRegistration = null;
    protected ?DateTime $confirmationUntil = null;
    protected ?DateTime $registrationDate = null;
    protected bool $hidden = false;
    protected int $amountOfRegistrations = 1;
    protected string $language = '';
    protected string $captcha = '';
    protected ?FrontendUser $feUser = null;
    protected string $paymentmethod = '';
    protected string $paymentReference = '';
    protected bool $waitlist = false;

    /**
     * @var ObjectStorage<FieldValue>
     * @Extbase\ORM\Cascade("remove")
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $fieldValues;

    /**
     * Registration constructor.
     */
    public function __construct()
    {
        $this->initializeObject();
    }

    /**
     * Initialize all ObjectStorages as fetching an entity from the DB does not use the constructor
     */
    public function initializeObject()
    {
        $this->fieldValues = $this->fieldValues ?? new ObjectStorage();
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company)
    {
        $this->company = $company;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip)
    {
        $this->zip = $zip;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city)
    {
        $this->city = $city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = trim($email);
    }

    public function isIgnoreNotifications(): bool
    {
        return $this->ignoreNotifications;
    }

    public function getIgnoreNotifications(): bool
    {
        return $this->ignoreNotifications;
    }

    public function setIgnoreNotifications(bool $ignoreNotifications)
    {
        $this->ignoreNotifications = $ignoreNotifications;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    public function setDateOfBirth(?DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getDateOfBirth(): ?DateTime
    {
        return $this->dateOfBirth;
    }

    public function getAccepttc(): bool
    {
        return $this->accepttc;
    }

    public function setAccepttc(bool $accepttc)
    {
        $this->accepttc = $accepttc;
    }

    public function getConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed)
    {
        $this->confirmed = $confirmed;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function getPaid(): bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid)
    {
        $this->paid = $paid;
    }

    public function isPaid(): bool
    {
        return $this->paid;
    }

    public function setEvent(?Event $event)
    {
        $this->event = $event;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setMainRegistration(?Registration $registration)
    {
        $this->mainRegistration = $registration;
    }

    public function getMainRegistration(): ?Registration
    {
        return $this->mainRegistration;
    }

    public function setNotes(string $notes)
    {
        $this->notes = $notes;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setConfirmationUntil(?DateTime $confirmationUntil)
    {
        $this->confirmationUntil = $confirmationUntil;
    }

    public function getConfirmationUntil(): ?DateTime
    {
        return $this->confirmationUntil;
    }

    public function getRegistrationDate(): ?DateTime
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?DateTime $registrationDate)
    {
        $this->registrationDate = $registrationDate;
    }

    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

    public function getAmountOfRegistrations(): int
    {
        return $this->amountOfRegistrations;
    }

    public function setAmountOfRegistrations(int $amountOfRegistrations)
    {
        $this->amountOfRegistrations = $amountOfRegistrations;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language)
    {
        $this->language = $language;
    }

    public function getCaptcha(): string
    {
        return $this->captcha;
    }

    public function setCaptcha(string $captcha)
    {
        $this->captcha = $captcha;
    }

    public function getFeUser(): ?FrontendUser
    {
        return $this->feUser;
    }

    public function setFeUser(?FrontendUser $feUser)
    {
        $this->feUser = $feUser;
    }

    public function getPaymentmethod(): string
    {
        return $this->paymentmethod;
    }

    public function setPaymentmethod(string $paymentmethod)
    {
        $this->paymentmethod = $paymentmethod;
    }

    public function getPaymentReference(): string
    {
        return $this->paymentReference;
    }

    public function setPaymentReference(string $paymentReference)
    {
        $this->paymentReference = $paymentReference;
    }

    public function getWaitlist(): bool
    {
        return $this->waitlist;
    }

    public function setWaitlist(bool $waitlist)
    {
        $this->waitlist = $waitlist;
    }

    public function getFieldValues(): ?ObjectStorage
    {
        return $this->fieldValues;
    }

    public function setFieldValues(?ObjectStorage $fieldValues)
    {
        $this->fieldValues = $fieldValues;
    }

    public function getFullname(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
