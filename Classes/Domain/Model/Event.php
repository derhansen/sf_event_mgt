<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Utility\MiscUtility;
use DERHANSEN\SfEventMgt\Utility\ShowInPreviews;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Event
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * @var \DateTime
     */
    protected $starttime;

    /**
     * @var \DateTime
     */
    protected $endtime;

    /**
     * Title
     *
     * @var string
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
     * Custom Text
     *
     * @var string
     */
    protected $customText = '';

    /**
     * Startdate and time
     *
     * @var \DateTime
     */
    protected $startdate;

    /**
     * Enddate and time
     *
     * @var \DateTime
     */
    protected $enddate;

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
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Category>
     * @Extbase\ORM\Lazy
     */
    protected $category;

    /**
     * Related
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Event>
     * @Extbase\ORM\Lazy
     */
    protected $related;

    /**
     * Registration
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Registration>
     * @Extbase\ORM\Cascade("remove")
     * @Extbase\ORM\Lazy
     */
    protected $registration;

    /**
     * Registration waitlist
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Registration>
     * @Extbase\ORM\Lazy
     */
    protected $registrationWaitlist;

    /**
     * Registration fields
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Registration\Field>
     * @Extbase\ORM\Lazy
     */
    protected $registrationFields;

    /**
     * Registration start date
     *
     * @var \DateTime
     */
    protected $registrationStartdate;

    /**
     * Registration deadline date
     *
     * @var \DateTime
     */
    protected $registrationDeadline;

    /**
     * The image
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @Extbase\ORM\Lazy
     */
    protected $image;

    /**
     * Additional files
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @Extbase\ORM\Lazy
     */
    protected $files;

    /**
     * The Location
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Location
     */
    protected $location;

    /**
     * Room
     *
     * @var string
     */
    protected $room;

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
     * Enable waitlist
     *
     * @var bool
     */
    protected $enableWaitlistMoveup = false;

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
     * @Extbase\ORM\Lazy
     */
    protected $additionalImage;

    /**
     * The organisator
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Organisator
     */
    protected $organisator;

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
    protected $cancelDeadline;

    /**
     * Enable auto confirmation
     *
     * @var bool
     */
    protected $enableAutoconfirm = false;

    /**
     * Unique email check
     *
     * @var bool
     */
    protected $uniqueEmailCheck = false;

    /**
     * @var string
     */
    protected $metaKeywords = '';

    /**
     * @var string
     */
    protected $metaDescription = '';

    /**
     * @var string
     */
    protected $alternativeTitle = '';

    /**
     * Price options
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\PriceOption>
     * @Extbase\ORM\Cascade("remove")
     * @Extbase\ORM\Lazy
     */
    protected $priceOptions;

    /**
     * Speaker
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Speaker>
     * @Extbase\ORM\Lazy
     */
    protected $speaker;

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
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set time stamp
     *
     * @param \DateTime $tstamp time stamp
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Get hidden flag
     *
     * @return bool
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set hidden flag
     *
     * @param bool $hidden hidden flag
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Get start time
     *
     * @return \DateTime
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set start time
     *
     * @param \DateTime $starttime start time
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;
    }

    /**
     * Get endtime
     *
     * @return \DateTime
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set end time
     *
     * @param \DateTime $endtime end time
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;
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
     */
    public function setProgram($program)
    {
        $this->program = $program;
    }

    /**
     * @return string
     */
    public function getCustomText()
    {
        return $this->customText;
    }

    /**
     * @param string $customText
     */
    public function setCustomText($customText)
    {
        $this->customText = $customText;
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
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Returns if payment is enabled
     *
     * @return bool
     */
    public function getEnablePayment()
    {
        return $this->enablePayment;
    }

    /**
     * Sets enablePayment
     *
     * @param bool $enablePayment
     */
    public function setEnablePayment($enablePayment)
    {
        $this->enablePayment = $enablePayment;
    }

    /**
     * Returns if payment methods should be restricted
     *
     * @return bool
     */
    public function getRestrictPaymentMethods()
    {
        return $this->restrictPaymentMethods;
    }

    /**
     * Sets if payment methods should be restricted
     *
     * @param bool $restrictPaymentMethods
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
     */
    public function setSelectedPaymentMethods($selectedPaymentMethods)
    {
        $this->selectedPaymentMethods = $selectedPaymentMethods;
    }

    /**
     * Adds a Category
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Category $category Category
     */
    public function addCategory(\DERHANSEN\SfEventMgt\Domain\Model\Category $category)
    {
        $this->category->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Category $categoryToRemove The Category to be removed
     */
    public function removeCategory(\DERHANSEN\SfEventMgt\Domain\Model\Category $categoryToRemove)
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
     * Returns the category
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategories()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $category Category
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
     */
    public function setRelated($related)
    {
        $this->related = $related;
    }

    /**
     * Adds a related event
     *
     * @param Event $event
     */
    public function addRelated(\DERHANSEN\SfEventMgt\Domain\Model\Event $event)
    {
        $this->related->attach($event);
    }

    /**
     * Removes a related event
     *
     * @param Event $event
     */
    public function removeRelated(\DERHANSEN\SfEventMgt\Domain\Model\Event $event)
    {
        $this->related->detach($event);
    }

    /**
     * Adds a Registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     */
    public function addRegistration(\DERHANSEN\SfEventMgt\Domain\Model\Registration $registration)
    {
        $this->registration->attach($registration);
    }

    /**
     * Removes a Registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registrationToRemove Registration
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
     */
    public function setRegistration(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Adds an image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image Image
     */
    public function addImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image->attach($image);
    }

    /**
     * Removes an image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove Image
     */
    public function removeImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove)
    {
        $this->image->detach($imageToRemove);
    }

    /**
     * Returns all items of the field image
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Special getter to return images when accesses as {event.images}
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImages()
    {
        return $this->image;
    }

    /**
     * Returns all image items configured for list view
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getListViewImages()
    {
        return $this->getImagesByType(ShowInPreviews::LIST_VIEWS);
    }

    /**
     * Returns the first list view image as file reference object
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    public function getFirstListViewImage()
    {
        $images = $this->getImagesByType(ShowInPreviews::LIST_VIEWS);
        $image = $images->current();

        if (is_a($image, \TYPO3\CMS\Extbase\Domain\Model\FileReference::class)) {
            return $image;
        } else {
            return null;
        }
    }

    /**
     * Returns all image items configured for list view
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getDetailViewImages()
    {
        return $this->getImagesByType(ShowInPreviews::DETAIL_VIEWS);
    }

    /**
     * Returns the first detail view image as file reference object
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    public function getFirstDetailViewImage()
    {
        $images = $this->getImagesByType(ShowInPreviews::DETAIL_VIEWS);
        $image = $images->current();

        if (is_a($image, \TYPO3\CMS\Extbase\Domain\Model\FileReference::class)) {
            return $image;
        } else {
            return null;
        }
    }

    /**
     * Returns all image items by the given type
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected function getImagesByType(int $type)
    {
        $result = new ObjectStorage();

        foreach ($this->image as $image) {
            /** @var FileReference $fileReference */
            $fileReference = $image->getOriginalResource();
            if ($fileReference && $fileReference->hasProperty('show_in_views') &&
                in_array($fileReference->getProperty('show_in_views'), [$type, ShowInPreviews::ALL_VIEWS])
            ) {
                $result->attach($image);
            }
        }

        return $result;
    }

    /**
     * Sets the image
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $image Image
     */
    public function setImage(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $image)
    {
        $this->image = $image;
    }

    /**
     * Adds a file
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file File
     */
    public function addFiles(\TYPO3\CMS\Extbase\Domain\Model\FileReference $file)
    {
        $this->files->attach($file);
    }

    /**
     * Removes a file
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove File
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
        if ($this->getMaxParticipants() > 0 && $this->getRegistrations()->count() >= $this->maxParticipants) {
            $maxParticipantsNotReached = false;
        }
        $deadlineNotReached = true;
        if ($this->getRegistrationDeadline() != null && $this->getRegistrationDeadline() <= new \DateTime()) {
            $deadlineNotReached = false;
        }
        $registrationStartReached = true;
        if ($this->getRegistrationStartdate() != null && $this->getRegistrationStartdate() > new \DateTime()) {
            $registrationStartReached = false;
        }

        return ($this->getStartdate() > new \DateTime()) &&
        ($maxParticipantsNotReached || !$maxParticipantsNotReached && $this->enableWaitlist) &&
        $this->getEnableRegistration() && $deadlineNotReached && $registrationStartReached;
    }

    /**
     * Returns the amount of free places
     *
     * @return int
     */
    public function getFreePlaces()
    {
        return $this->maxParticipants - $this->getRegistrations()->count();
    }

    /**
     * Sets the location
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Location $location Location
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
     * Returns the room
     *
     * @return string
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Sets the room
     *
     * @param string $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * Sets enableRegistration
     *
     * @param bool $enableRegistration EnableRegistration
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
     * @return bool
     */
    public function getEnableWaitlist()
    {
        return $this->enableWaitlist;
    }

    /**
     * Sets enableWaitlist
     *
     * @param bool $enableWaitlist
     */
    public function setEnableWaitlist($enableWaitlist)
    {
        $this->enableWaitlist = $enableWaitlist;
    }

    /**
     * @return bool
     */
    public function getEnableWaitlistMoveup(): bool
    {
        return $this->enableWaitlistMoveup;
    }

    /**
     * @param bool $enableWaitlistMoveup
     */
    public function setEnableWaitlistMoveup($enableWaitlistMoveup): void
    {
        $this->enableWaitlistMoveup = $enableWaitlistMoveup;
    }

    /**
     * Sets the registration startdate
     *
     * @param \DateTime $registrationStartdate RegistrationStartdate
     */
    public function setRegistrationStartdate(\DateTime $registrationStartdate)
    {
        $this->registrationStartdate = $registrationStartdate;
    }

    /**
     * Returns the registration startdate
     *
     * @return \DateTime
     */
    public function getRegistrationStartdate()
    {
        return $this->registrationStartdate;
    }

    /**
     * Sets the registration deadline
     *
     * @param \DateTime $registrationDeadline RegistrationDeadline
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
     * Sets topEvent
     *
     * @param bool $topEvent TopEvent
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
     */
    public function setMaxRegistrationsPerUser($maxRegistrationsPerUser)
    {
        $this->maxRegistrationsPerUser = $maxRegistrationsPerUser;
    }

    /**
     * Adds an additionalImage
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $additionalImage The Image
     */
    public function addAdditionalImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $additionalImage)
    {
        $this->additionalImage->attach($additionalImage);
    }

    /**
     * Removes an additionalImage
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $additionalImageToRemove The Image
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
     */
    public function setNotifyOrganisator($notifyOrganisator)
    {
        $this->notifyOrganisator = $notifyOrganisator;
    }

    /**
     * Sets enableCancel
     *
     * @param bool $enableCancel EnableCancel
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
     */
    public function setEnableAutoconfirm($enableAutoconfirm)
    {
        $this->enableAutoconfirm = $enableAutoconfirm;
    }

    /**
     * Returns uniqueEmailCheck
     *
     * @return bool
     */
    public function getUniqueEmailCheck()
    {
        return $this->uniqueEmailCheck;
    }

    /**
     * Sets UniqueEmailCheck
     *
     * @param bool $uniqueEmailCheck
     */
    public function setUniqueEmailCheck($uniqueEmailCheck)
    {
        $this->uniqueEmailCheck = $uniqueEmailCheck;
    }

    /**
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords
     */
    public function setMetaKeywords(string $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription(string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string
     */
    public function getAlternativeTitle(): string
    {
        return $this->alternativeTitle;
    }

    /**
     * @param string $alternativeTitle
     */
    public function setAlternativeTitle(string $alternativeTitle): void
    {
        $this->alternativeTitle = $alternativeTitle;
    }

    /**
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->getAlternativeTitle() !== '' ? $this->getAlternativeTitle() : $this->getTitle();
    }


    /**
     * Returns price options
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\PriceOption>
     */
    public function getPriceOptions()
    {
        return $this->priceOptions;
    }

    /**
     * Sets price options
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $priceOptions
     */
    public function setPriceOptions($priceOptions)
    {
        $this->priceOptions = $priceOptions;
    }

    /**
     * Adds a price option
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\PriceOption $priceOption Price option
     */
    public function addPriceOptions(\DERHANSEN\SfEventMgt\Domain\Model\PriceOption $priceOption)
    {
        $this->priceOptions->attach($priceOption);
    }

    /**
     * Removes a Registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\PriceOption $priceOption Price option
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
        }
        // Just return the price field
        return $this->price;
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
     */
    public function setRegistrationWaitlist(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration)
    {
        $this->registrationWaitlist = $registration;
    }

    /**
     * Adds a Registration to the waitlist
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     */
    public function addRegistrationWaitlist(\DERHANSEN\SfEventMgt\Domain\Model\Registration $registration)
    {
        $this->registrationWaitlist->attach($registration);
    }

    /**
     * Removes a Registration from the waitlist
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registrationToRemove Registration
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
        $today = new \DateTime('today');

        return ($this->getEnableCancel() && $this->getCancelDeadline() > $today) ||
            ($this->getEnableCancel() && $this->getCancelDeadline() === null && $this->getStartdate() > $today);
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
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
    }

    /**
     * Adds a speaker
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Speaker $speaker
     */
    public function addSpeaker(\DERHANSEN\SfEventMgt\Domain\Model\Speaker $speaker)
    {
        $this->speaker->attach($speaker);
    }

    /**
     * Removes a speaker
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Speaker $speaker
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
     * Returns an array with registration field uids
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

    /**
     * Returns an array with registration field uids and titles
     * [uid => title]
     *
     * @return array
     */
    public function getRegistrationFieldUidsWithTitle()
    {
        $result = [];
        foreach ($this->registrationFields as $registrationField) {
            $result[$registrationField->getUid()] = $registrationField->getTitle();
        }

        return $result;
    }

    /**
     * Special getter to return the amount of registrations that are saved to default language
     * Required since TYPO3 9.5 (#82363)
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRegistrations()
    {
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        if ($languageAspect->getId() > 0) {
            return $this->getRegistrationsDefaultLanguage(false);
        }

        return $this->registration;
    }

    /**
     * Special getter to return the amount of waitlist registrations that are saved to default language
     * Required since TYPO3 9.5 (#82363)
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getRegistrationsWaitlist()
    {
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        if ($languageAspect->getId() > 0) {
            return $this->getRegistrationsDefaultLanguage(true);
        }

        return $this->registrationWaitlist;
    }

    /**
     * Returns an objectStorage object holding all registrations in the default language.
     * Ensures expected behavior of getRegistration() and getRegistrationWaitlist() since TYPO3 issue #82363
     *
     * @param bool $waitlist
     * @return ObjectStorage
     */
    protected function getRegistrationsDefaultLanguage(bool $waitlist = false)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $result = $objectManager->get(ObjectStorage::class);
        $registrationRepository = $objectManager->get(RegistrationRepository::class);
        $registrations = $registrationRepository->findByEventAndWaitlist($this, $waitlist);
        foreach ($registrations as $registration) {
            $result->attach($registration);
        }

        return $result;
    }

    /**
     * Returns if the event ends on the same day
     *
     * @return bool
     */
    public function getEndsSameDay(): bool
    {
        if ($this->enddate !== null) {
            return $this->startdate->format('d.m.Y') === $this->enddate->format('d.m.Y');
        }

        return true;
    }

    /**
     * Returns the challenge for the challenge/response spam check
     *
     * @return string
     */
    public function getSpamCheckChallenge(): string
    {
        return MiscUtility::getSpamCheckChallenge($this->getUid());
    }

    /**
     * Returns a string to be used as overlay value for the <core:icon> ViewHelper in the Backend Modules
     *
     * @return string
     */
    public function getBackendIconOverlay(): string
    {
        $date = new DateTime();
        $overlay = '';
        if ($this->getHidden()) {
            $overlay = 'overlay-hidden';
        } elseif ($this->getEndtime() && $this->getEndtime() < $date) {
            $overlay = 'overlay-endtime';
        } elseif (($this->getStarttime() && $this->getStarttime() > $date) ||
            ($this->getEndtime() && $this->getEndtime() > $date)
        ) {
            $overlay = 'overlay-scheduled';
        }

        return $overlay;
    }
}
