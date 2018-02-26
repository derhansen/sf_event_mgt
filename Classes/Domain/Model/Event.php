<?php
namespace DERHANSEN\SfEventMgt\Domain\Model;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;

/**
 * Event
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * Teaser
     *
     * @var string
     */
    protected $teaser = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Program/Schedule
     *
     * @var string
     */
    protected $program = '';

    /**
     * Startdate and time
     *
     * @var \DateTime
     */
    protected $startdate = null;

    /**
     * Enddate and time
     *
     * @var \DateTime
     */
    protected $enddate = null;

    /**
     * Max participants
     *
     * @var int
     */
    protected $maxParticipants = 0;

    /**
     * Max registrations per user
     *
     * @var int
     */
    protected $maxRegistrationsPerUser = 1;

    /**
     * Price
     *
     * @var float
     */
    protected $price = 0.0;

    /**
     * Currency
     *
     * @var string
     */
    protected $currency = '';

    /**
     * Enable payment
     *
     * @var bool
     */
    protected $enablePayment = false;

    /**
     * Restrict payment methods
     *
     * @var bool
     */
    protected $restrictPaymentMethods = false;

    /**
     * Selected payment methods
     *
     * @var string
     */
    protected $selectedPaymentMethods = '';

    /**
     * Category
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     * @lazy
     */
    protected $category = null;

    /**
     * Related
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Event>
     * @lazy
     */
    protected $related;

    /**
     * Registration
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Registration>
     * @cascade remove
     * @lazy
     */
    protected $registration = null;

    /**
     * Registration waitlist
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Registration>
     * @lazy
     */
    protected $registrationWaitlist;

    /**
     * Registration fields
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Registration\Field>
     * @lazy
     */
    protected $registrationFields;

    /**
     * Registration deadline date
     *
     * @var \DateTime
     */
    protected $registrationDeadline = null;

    /**
     * The image
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @lazy
     */
    protected $image = null;

    /**
     * Additional files
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @lazy
     */
    protected $files = null;

    /**
     * The Location
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Location
     */
    protected $location = null;

    /**
     * Enable registration
     *
     * @var bool
     */
    protected $enableRegistration = false;

    /**
     * Enable waitlist
     *
     * @var bool
     */
    protected $enableWaitlist = false;

    /**
     * Link
     *
     * @var string
     */
    protected $link;

    /**
     * Top event
     *
     * @var bool
     */
    protected $topEvent = false;

    /**
     * The additionalImage
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @lazy
     */
    protected $additionalImage = null;

    /**
     * The organisator
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Organisator
     */
    protected $organisator = null;

    /**
     * Notify admin
     *
     * @var bool
     */
    protected $notifyAdmin = true;

    /**
     * Notify organisator
     *
     * @var bool
     */
    protected $notifyOrganisator = false;

    /**
     * Enable cancel of registration
     *
     * @var bool
     */
    protected $enableCancel = false;

    /**
     * Deadline for cancel
     *
     * @var \DateTime
     */
    protected $cancelDeadline = null;

    /**
     * Enable auto confirmation
     *
     * @var bool
     */
    protected $enableAutoconfirm = false;

    /**
     * Unique e-mail check
     *
     * @var bool
     */
    protected $uniqueEmailCheck = false;

    /**
     * Price options
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\PriceOption>
     * @cascade remove
     * @lazy
     */
    protected $priceOptions = null;

    /**
     * Speaker
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Speaker>
     * @lazy
     */
    protected $speaker = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->related = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->registration = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->registrationWaitlist = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->registrationFields = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->image = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->files = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->additionalImage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->priceOptions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->speaker = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Returns the teaser
     *
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Sets the teaser
     *
     * @param string $teaser Teaser
     *
     * @return void
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description Description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the program
     *
     * @return string $program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Sets the program
     *
     * @param string $program The program
     *
     * @return void
     */
    public function setProgram($program)
    {
        $this->program = $program;
    }

    /**
     * Returns the startdate
     *
     * @return \DateTime $startdate
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Sets the startdate
     *
     * @param \DateTime $startdate Startdate
     *
     * @return void
     */
    public function setStartdate(\DateTime $startdate)
    {
        $this->startdate = $startdate;
    }

    /**
     * Returns the enddate
     *
     * @return \DateTime $enddate
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Sets the enddate
     *
     * @param \DateTime $enddate Enddate
     *
     * @return void
     */
    public function setEnddate(\DateTime $enddate)
    {
        $this->enddate = $enddate;
    }

    /**
     * Returns the participants
     *
     * @return int $participants
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
    }

    /**
     * Sets the participants
     *
     * @param int $participants Participants
     *
     * @return void
     */
    public function setMaxParticipants($participants)
    {
        $this->maxParticipants = $participants;
    }

    /**
     * Returns the price
     *
     * @return float $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the price
     *
     * @param float $price Price
     *
     * @return void
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Returns the currency
     *
     * @return string $currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets the currency
     *
     * @param string $currency Currency
     *
     * @return void
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Returns if payment is enabled
     *
     * @return boolean
     */
    public function getEnablePayment()
    {
        return $this->enablePayment;
    }

    /**
     * Sets enablePayment
     *
     * @param boolean $enablePayment
     * @return void
     */
    public function setEnablePayment($enablePayment)
    {
        $this->enablePayment = $enablePayment;
    }

    /**
     * Returns if payment methods should be restricted
     *
     * @return boolean
     */
    public function getRestrictPaymentMethods()
    {
        return $this->restrictPaymentMethods;
    }

    /**
     * Sets if payment methods should be restricted
     *
     * @param boolean $restrictPaymentMethods
     * @return void
     */
    public function setRestrictPaymentMethods($restrictPaymentMethods)
    {
        $this->restrictPaymentMethods = $restrictPaymentMethods;
    }

    /**
     * Returns selected payment methods
     *
     * @return string
     */
    public function getSelectedPaymentMethods()
    {
        return $this->selectedPaymentMethods;
    }

    /**
     * Sets selected payment methods
     *
     * @param string $selectedPaymentMethods
     * @return void
     */
    public function setSelectedPaymentMethods($selectedPaymentMethods)
    {
        $this->selectedPaymentMethods = $selectedPaymentMethods;
    }

    /**
     * Adds a Category
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\Category $category Category
     *
     * @return void
     */
    public function addCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $category)
    {
        $this->category->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove The Category to be removed
     *
     * @return void
     */
    public function removeCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove)
    {
        $this->category->detach($categoryToRemove);
    }

    /**
     * Returns the category
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $category Category
     *
     * @return void
     */
    public function setCategory(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $category)
    {
        $this->category = $category;
    }

    /**
     * Returns related events
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * Sets related events
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $related
     * @return void
     */
    public function setRelated($related)
    {
        $this->related = $related;
    }

    /**
     * Adds a related event
     *
     * @param Event $event
     * @return void
     */
    public function addRelated(\DERHANSEN\SfEventMgt\Domain\Model\Event $event)
    {
        $this->related->attach($event);
    }

    /**
     * Removes a related event
     *
     * @param Event $event
     * @return void
     */
    public function removeRelated(\DERHANSEN\SfEventMgt\Domain\Model\Event $event)
    {
        $this->related->detach($event);
    }

    /**
     * Adds a Registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     *
     * @return void
     */
    public function addRegistration(\DERHANSEN\SfEventMgt\Domain\Model\Registration $registration)
    {
        $this->registration->attach($registration);
    }

    /**
     * Removes a Registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registrationToRemove Registration
     *
     * @return void
     */
    public function removeRegistration(\DERHANSEN\SfEventMgt\Domain\Model\Registration $registrationToRemove)
    {
        $this->registration->detach($registrationToRemove);
    }

    /**
     * Returns the Registration
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * Sets the Registration
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration Registration
     *
     * @return void
     */
    public function setRegistration(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Adds an image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image Image
     *
     * @return void
     */
    public function addImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image->attach($image);
    }

    /**
     * Removes an image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove Image
     *
     * @return void
     */
    public function removeImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove)
    {
        $this->image->detach($imageToRemove);
    }

    /**
     * Returns the image
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $image Image
     *
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $image)
    {
        $this->image = $image;
    }

    /**
     * Adds a file
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file File
     *
     * @return void
     */
    public function addFiles(\TYPO3\CMS\Extbase\Domain\Model\FileReference $file)
    {
        $this->files->attach($file);
    }

    /**
     * Removes a file
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove File
     *
     * @return void
     */
    public function removeFiles(\TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove)
    {
        $this->files->detach($fileToRemove);
    }

    /**
     * Returns the files
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets the files
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files Files
     *
     * @return void
     */
    public function setFiles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $files)
    {
        $this->files = $files;
    }

    /**
     * Returns if the registration for this event is logically possible
     *
     * @return bool
     */
    public function getRegistrationPossible()
    {
        $maxParticipantsNotReached = true;
        if ($this->getMaxParticipants() > 0 && $this->getRegistration()->count() >= $this->maxParticipants) {
            $maxParticipantsNotReached = false;
        }
        $deadlineNotReached = true;
        if ($this->getRegistrationDeadline() != null && $this->getRegistrationDeadline() <= new \DateTime()) {
            $deadlineNotReached = false;
        }
        return ($this->getStartdate() > new \DateTime()) &&
        ($maxParticipantsNotReached || !$maxParticipantsNotReached && $this->enableWaitlist) &&
        $this->getEnableRegistration() && $deadlineNotReached;
    }

    /**
     * Returns the amount of free places
     *
     * @return int
     */
    public function getFreePlaces()
    {
        return $this->maxParticipants - $this->getRegistration()->count();
    }

    /**
     * Sets the location
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Location $location Location
     *
     * @return void
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Returns the location
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets enableRegistration
     *
     * @param bool $enableRegistration EnableRegistration
     *
     * @return void
     */
    public function setEnableRegistration($enableRegistration)
    {
        $this->enableRegistration = $enableRegistration;
    }

    /**
     * Returns if registration is enabled
     *
     * @return bool
     */
    public function getEnableRegistration()
    {
        return $this->enableRegistration;
    }

    /**
     * Returns enableWaitlist
     *
     * @return boolean
     */
    public function getEnableWaitlist()
    {
        return $this->enableWaitlist;
    }

    /**
     * Sets enableWaitlist
     *
     * @param boolean $enableWaitlist
     * @return void
     */
    public function setEnableWaitlist($enableWaitlist)
    {
        $this->enableWaitlist = $enableWaitlist;
    }

    /**
     * Sets the registration deadline
     *
     * @param \DateTime $registrationDeadline RegistrationDeadline
     *
     * @return void
     */
    public function setRegistrationDeadline(\DateTime $registrationDeadline)
    {
        $this->registrationDeadline = $registrationDeadline;
    }

    /**
     * Returns the registration deadline
     *
     * @return \DateTime
     */
    public function getRegistrationDeadline()
    {
        return $this->registrationDeadline;
    }

    /**
     * Sets the link
     *
     * @param string $link Link
     *
     * @return void
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Returns the link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Returns the uri of the link
     *
     * @return string
     */
    public function getLinkUrl()
    {
        return $this->getLinkPart(0);
    }

    /**
     * Returns the target of the link
     *
     * @return string
     */
    public function getLinkTarget()
    {
        return $this->getLinkPart(1);
    }

    /**
     * Returns the title of the link
     *
     * @return string
     */
    public function getLinkTitle()
    {
        return $this->getLinkPart(3);
    }

    /**
     * Splits link to an array respection that a title with more than one word is
     * surrounded by quotation marks. Returns part of the link for usage in fluid
     * viewhelpers.
     *
     * @param int $part The part
     *
     * @return string
     */
    public function getLinkPart($part)
    {
        $linkArray = str_getcsv($this->link, ' ', '"');
        $ret = '';
        if (count($linkArray) >= $part) {
            $ret = $linkArray[$part];
        }
        if ($ret === '-') {
            $ret = '';
        }
        return $ret;
    }

    /**
     * Sets topEvent
     *
     * @param bool $topEvent TopEvent
     *
     * @return void
     */
    public function setTopEvent($topEvent)
    {
        $this->topEvent = $topEvent;
    }

    /**
     * Returns if topEvent is checked
     *
     * @return bool
     */
    public function getTopEvent()
    {
        return $this->topEvent;
    }

    /**
     * Returns max regisrations per user
     *
     * @return int
     */
    public function getMaxRegistrationsPerUser()
    {
        return $this->maxRegistrationsPerUser;
    }

    /**
     * Sets max registrations per user
     *
     * @param int $maxRegistrationsPerUser MaxRegistrationsPerUser
     *
     * @return void
     */
    public function setMaxRegistrationsPerUser($maxRegistrationsPerUser)
    {
        $this->maxRegistrationsPerUser = $maxRegistrationsPerUser;
    }


    /**
     * Adds an additionalImage
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $additionalImage The Image
     *
     * @return void
     */
    public function addAdditionalImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $additionalImage)
    {
        $this->additionalImage->attach($additionalImage);
    }

    /**
     * Removes an additionalImage
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $additionalImageToRemove The Image
     *
     * @return void
     */
    public function removeAdditionalImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $additionalImageToRemove)
    {
        $this->additionalImage->detach($additionalImageToRemove);
    }

    /**
     * Returns the additionalImage
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $additionalImage
     */
    public function getAdditionalImage()
    {
        return $this->additionalImage;
    }

    /**
     * Sets the additionalImage
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $additionalImage The Image
     *
     * @return void
     */
    public function setAdditionalImage(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $additionalImage)
    {
        $this->additionalImage = $additionalImage;
    }

    /**
     * Returns the organisator
     *
     * @return Organisator
     */
    public function getOrganisator()
    {
        return $this->organisator;
    }

    /**
     * Sets the organisator
     *
     * @param Organisator $organisator The organisator
     *
     * @return void
     */
    public function setOrganisator($organisator)
    {
        $this->organisator = $organisator;
    }

    /**
     * Returns notifyAdmin
     *
     * @return bool
     */
    public function getNotifyAdmin()
    {
        return $this->notifyAdmin;
    }

    /**
     * Sets notifyAdmin
     *
     * @param bool $notifyAdmin NotifyAdmin
     *
     * @return void
     */
    public function setNotifyAdmin($notifyAdmin)
    {
        $this->notifyAdmin = $notifyAdmin;
    }

    /**
     * Returns if notifyAdmin is set
     *
     * @return bool
     */
    public function getNotifyOrganisator()
    {
        return $this->notifyOrganisator;
    }

    /**
     * Sets notifyOrganisator
     *
     * @param bool $notifyOrganisator NotifyOrganisator
     *
     * @return void
     */
    public function setNotifyOrganisator($notifyOrganisator)
    {
        $this->notifyOrganisator = $notifyOrganisator;
    }

    /**
     * Sets enableCancel
     *
     * @param bool $enableCancel EnableCancel
     *
     * @return void
     */
    public function setEnableCancel($enableCancel)
    {
        $this->enableCancel = $enableCancel;
    }

    /**
     * Returns if registration can be canceled
     *
     * @return bool
     */
    public function getEnableCancel()
    {
        return $this->enableCancel;
    }

    /**
     * Sets the cancel deadline
     *
     * @param \DateTime $cancelDeadline CancelDeadline
     *
     * @return void
     */
    public function setCancelDeadline(\DateTime $cancelDeadline)
    {
        $this->cancelDeadline = $cancelDeadline;
    }

    /**
     * Returns the cancel deadline
     *
     * @return \DateTime
     */
    public function getCancelDeadline()
    {
        return $this->cancelDeadline;
    }

    /**
     * Returns if autoconfirmation is enabled
     *
     * @return bool
     */
    public function getEnableAutoconfirm()
    {
        return $this->enableAutoconfirm;
    }

    /**
     * Sets enable autoconfirm
     *
     * @param bool $enableAutoconfirm
     * @return void
     */
    public function setEnableAutoconfirm($enableAutoconfirm)
    {
        $this->enableAutoconfirm = $enableAutoconfirm;
    }

    /**
     * Returns uniqueEmailCheck
     *
     * @return boolean
     */
    public function getUniqueEmailCheck()
    {
        return $this->uniqueEmailCheck;
    }

    /**
     * Sets UniqueEmailCheck
     *
     * @param boolean $uniqueEmailCheck
     * @return void
     */
    public function setUniqueEmailCheck($uniqueEmailCheck)
    {
        $this->uniqueEmailCheck = $uniqueEmailCheck;
    }

    /**
     * Returns price options
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getPriceOptions()
    {
        return $this->priceOptions;
    }

    /**
     * Sets price options
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $priceOptions
     * @return void
     */
    public function setPriceOptions($priceOptions)
    {
        $this->priceOptions = $priceOptions;
    }

    /**
     * Adds a price option
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\PriceOption $priceOption Price option
     *
     * @return void
     */
    public function addPriceOptions(\DERHANSEN\SfEventMgt\Domain\Model\PriceOption $priceOption)
    {
        $this->priceOptions->attach($priceOption);
    }

    /**
     * Removes a Registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\PriceOption $priceOption Price option
     *
     * @return void
     */
    public function removePriceOptions(\DERHANSEN\SfEventMgt\Domain\Model\PriceOption $priceOption)
    {
        $this->priceOptions->detach($priceOption);
    }

    /**
     * Returns all active price options sorted by date ASC
     *
     * @return array
     */
    public function getActivePriceOptions()
    {
        $activePriceOptions = [];
        if ($this->getPriceOptions()) {
            $compareDate = new \DateTime('today midnight');
            foreach ($this->getPriceOptions() as $priceOption) {
                if ($priceOption->getValidUntil() >= $compareDate) {
                    $activePriceOptions[$priceOption->getValidUntil()->getTimestamp()] = $priceOption;
                }
            }
        }
        ksort($activePriceOptions);
        return $activePriceOptions;
    }

    /**
     * Returns the current price of the event respecting possible price options
     *
     * @return float
     */
    public function getCurrentPrice()
    {
        $activePriceOptions = $this->getActivePriceOptions();
        if (count($activePriceOptions) >= 1) {
            // Sort active price options and return first element
            return reset($activePriceOptions)->getPrice();
        } else {
            // Just return the price field
            return $this->price;
        }
    }

    /**
     * Returns registrationWaitlist
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRegistrationWaitlist()
    {
        return $this->registrationWaitlist;
    }

    /**
     * Sets registrationWaitlist
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration Registration
     *
     * @return void
     */
    public function setRegistrationWaitlist(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration)
    {
        $this->registrationWaitlist = $registration;
    }

    /**
     * Adds a Registration to the waitlist
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     *
     * @return void
     */
    public function addRegistrationWaitlist(\DERHANSEN\SfEventMgt\Domain\Model\Registration $registration)
    {
        $this->registrationWaitlist->attach($registration);
    }

    /**
     * Removes a Registration from the waitlist
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registrationToRemove Registration
     *
     * @return void
     */
    public function removeRegistrationWaitlist(\DERHANSEN\SfEventMgt\Domain\Model\Registration $registrationToRemove)
    {
        $this->registrationWaitlist->detach($registrationToRemove);
    }

    /**
     * Returns, if cancellation for registrations of the event is possible
     *
     * @return bool
     */
    public function getCancellationPossible()
    {
        return $this->getEnableCancel() && $this->getCancelDeadline() > new \DateTime();
    }

    /**
     * Returns speaker
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * Sets speaker
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $speaker
     * @return void
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
    }

    /**
     * Adds a speaker
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Speaker $speaker
     *
     * @return void
     */
    public function addSpeaker(\DERHANSEN\SfEventMgt\Domain\Model\Speaker $speaker)
    {
        $this->speaker->attach($speaker);
    }

    /**
     * Removes a speaker
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Speaker $speaker
     *
     * @return void
     */
    public function removeSpeaker(\DERHANSEN\SfEventMgt\Domain\Model\Speaker $speaker)
    {
        $this->speaker->detach($speaker);
    }

    /**
     * Returns registrationFields
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRegistrationFields()
    {
        return $this->registrationFields;
    }

    /**
     * Sets registrationWaitlist
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrationFields
     *
     * @return void
     */
    public function setRegistrationFields(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrationFields)
    {
        $this->registrationFields = $registrationFields;
    }

    /**
     * Adds a registrationField
     *
     * @param Field $registrationField
     */
    public function addRegistrationFields(Field $registrationField)
    {
        $this->registrationFields->attach($registrationField);
    }

    /**
     * Removed a registrationField
     *
     * @param Field $registrationField
     */
    public function removeRegistrationFields(Field $registrationField)
    {
        $this->registrationFields->detach($registrationField);
    }

    /**
     * Returns an array with registration fields
     *
     * @return array
     */
    public function getRegistrationFieldsUids()
    {
        $result = [];
        foreach ($this->registrationFields as $registrationField) {
            $result[] = $registrationField->getUid();
        }
        return $result;
    }
}
