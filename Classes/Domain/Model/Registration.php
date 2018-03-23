<?php
namespace DERHANSEN\SfEventMgt\Domain\Model;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Registration
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class Registration extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Firstname
     *
     * @var string
     * @validate NotEmpty
     */
    protected $firstname = '';

    /**
     * Lastname
     *
     * @var string
     * @validate NotEmpty
     */
    protected $lastname = '';

    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Company
     *
     * @var string
     */
    protected $company = '';

    /**
     * Address
     *
     * @var string
     */
    protected $address = '';

    /**
     * Zip
     *
     * @var string
     */
    protected $zip = '';

    /**
     * City
     *
     * @var string
     */
    protected $city = '';

    /**
     * Country
     *
     * @var string
     */
    protected $country = '';

    /**
     * Phone
     *
     * @var string
     */
    protected $phone = '';

    /**
     * E-Mail
     *
     * @var string
     * @validate NotEmpty, EmailAddress
     */
    protected $email = '';

    /**
     * Ignore notifications
     *
     * @var bool
     */
    protected $ignoreNotifications = false;

    /**
     * Gender
     *
     * @var string
     */
    protected $gender = '';

    /**
     * Date of birth
     *
     * @var \DateTime
     */
    protected $dateOfBirth = null;

    /**
     * Accept terms and conditions
     *
     * @var bool
     */
    protected $accepttc = false;

    /**
     * Confirmed
     *
     * @var bool
     */
    protected $confirmed = false;

    /**
     * Paid
     *
     * @var bool
     */
    protected $paid = false;

    /**
     * Notes
     *
     * @var string
     */
    protected $notes = '';

    /**
     * Event
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Event
     */
    protected $event = null;

    /**
     * Main registration (if available)
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Registration
     */
    protected $mainRegistration = null;

    /**
     * DateTime until the registration must be confirmed
     *
     * @var \DateTime
     */
    protected $confirmationUntil = null;

    /**
     * Indicates if record is hidden
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * Amount of registrations (if multiple registrations created by one user)
     *
     * @var int
     */
    protected $amountOfRegistrations = 1;

    /**
     * The language (e.g. de)
     *
     * @var string
     */
    protected $language = '';

    /**
     * reCaptcha
     *
     * @var string
     */
    protected $recaptcha = '';

    /**
     * FrontendUser if available
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $feUser = null;

    /**
     * Payment method
     *
     * @var string
     */
    protected $paymentmethod = '';

    /**
     * Payment reference (e.g. from Payment provider)
     *
     * @var string
     */
    protected $paymentReference = '';

    /**
     * Flags if this is a registration on the waitlist
     *
     * @var bool
     */
    protected $waitlist = false;

    /**
     * Registration fields
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue>
     * @cascade remove
     * @lazy
     */
    protected $fieldValues;

    /**
     * Registration constructor.
     */
    public function __construct()
    {
        $this->fieldValues = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the firstname
     *
     * @return string $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Sets the firstname
     *
     * @param string $firstname Firstname
     *
     * @return void
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Returns the lastname
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Sets the lastname
     *
     * @param string $lastname Lastname
     *
     * @return void
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title Title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the company
     *
     * @return string $company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the company
     *
     * @param string $company Company
     *
     * @return void
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address Address
     *
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the zip
     *
     * @return string $zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string $zip Zip
     *
     * @return void
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city City
     *
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Returns the country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     *
     * @param string $country Country
     *
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Returns the phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the phone
     *
     * @param string $phone Phone
     *
     * @return void
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email E-Mail
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = trim($email);
    }

    /**
     * Returns boolean state of ignoreNotifications
     *
     * @return bool
     */
    public function isIgnoreNotifications()
    {
        return $this->ignoreNotifications;
    }

    /**
     * Returns ignoreNotifications
     *
     * @return bool
     */
    public function getIgnoreNotifications()
    {
        return $this->ignoreNotifications;
    }

    /**
     * Sets ignoreNotifications
     *
     * @param bool $ignoreNotifications IgnoreNotifications
     *
     * @return void
     */
    public function setIgnoreNotifications($ignoreNotifications)
    {
        $this->ignoreNotifications = $ignoreNotifications;
    }

    /**
     * Returns the gender
     *
     * @return string $gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets the gender
     *
     * @param string $gender Gender
     *
     * @return void
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Sets the date of birth
     *
     * @param \DateTime $dateOfBirth DateOfBirth
     *
     * @return void
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Returns the date of birth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Returns accept terms and conditions
     *
     * @return bool $accepttc
     */
    public function getAccepttc()
    {
        return $this->accepttc;
    }

    /**
     * Sets accept terms and conditions
     *
     * @param bool $accepttc Accept terms and conditions
     *
     * @return void
     */
    public function setAccepttc($accepttc)
    {
        $this->accepttc = $accepttc;
    }

    /**
     * Returns the confirmed
     *
     * @return bool $confirmed Confirmed
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Sets the confirmed
     *
     * @param bool $confirmed Confirmed
     *
     * @return void
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * Returns the boolean state of confirmed
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Returns the paid
     *
     * @return bool $paid
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Sets the paid
     *
     * @param bool $paid Paid
     *
     * @return void
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }

    /**
     * Returns the boolean state of paid
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->paid;
    }

    /**
     * Sets the event
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     *
     * @return void
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * Returns the event
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets the mainRegistration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     *
     * @return void
     */
    public function setMainRegistration($registration)
    {
        $this->mainRegistration = $registration;
    }

    /**
     * Returns the event
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Registration
     */
    public function getMainRegistration()
    {
        return $this->mainRegistration;
    }

    /**
     * Setter for notes
     *
     * @param string $notes Notes
     *
     * @return void
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Getter for notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Sets confirmUntil
     *
     * @param \DateTime $confirmationUntil Confirmation Until
     *
     * @return void
     */
    public function setConfirmationUntil($confirmationUntil)
    {
        $this->confirmationUntil = $confirmationUntil;
    }

    /**
     * Returns confirmationUntil
     *
     * @return \DateTime
     */
    public function getConfirmationUntil()
    {
        return $this->confirmationUntil;
    }

    /**
     * Sets hidden
     *
     * @param bool $hidden Hidden
     *
     * @return void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Returns hidden
     *
     * @return bool
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Returns amountOfRegistrations
     *
     * @return int
     */
    public function getAmountOfRegistrations()
    {
        return $this->amountOfRegistrations;
    }

    /**
     * Sets amountOfRegistrations
     *
     * @param int $amountOfRegistrations AmountOfRegistrations
     *
     * @return void
     */
    public function setAmountOfRegistrations($amountOfRegistrations)
    {
        $this->amountOfRegistrations = $amountOfRegistrations;
    }

    /**
     * Returns the language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the language
     *
     * @param string $language
     * @return void
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Returns recaptcha
     *
     * @return string
     */
    public function getRecaptcha()
    {
        return $this->recaptcha;
    }

    /**
     * Sets recaptcha
     *
     * @param string $recaptcha
     * @return void
     */
    public function setRecaptcha($recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

    /**
     * Returns the frontenduser
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    public function getFeUser()
    {
        return $this->feUser;
    }

    /**
     * Sets the frontenduser
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser
     * @return void
     */
    public function setFeUser($feUser)
    {
        $this->feUser = $feUser;
    }

    /**
     * Returns the payment method
     *
     * @return string
     */
    public function getPaymentmethod()
    {
        return $this->paymentmethod;
    }

    /**
     * Sets the payment method
     *
     * @param string $paymentmethod
     * @return void
     */
    public function setPaymentmethod($paymentmethod)
    {
        $this->paymentmethod = $paymentmethod;
    }

    /**
     * Returns paymentReference
     *
     * @return string
     */
    public function getPaymentReference()
    {
        return $this->paymentReference;
    }

    /**
     * Sets paymentReference
     *
     * @param string $paymentReference
     * @return void
     */
    public function setPaymentReference($paymentReference)
    {
        $this->paymentReference = $paymentReference;
    }

    /**
     * Returns waitlist
     *
     * @return bool
     */
    public function getWaitlist()
    {
        return $this->waitlist;
    }

    /**
     * Sets waitlist
     *
     * @param bool $waitlist
     * @return void
     */
    public function setWaitlist($waitlist)
    {
        $this->waitlist = $waitlist;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getFieldValues()
    {
        return $this->fieldValues;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $fieldValues
     */
    public function setFieldValues($fieldValues)
    {
        $this->fieldValues = $fieldValues;
    }
}
