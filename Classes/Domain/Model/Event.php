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
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Utility\MiscUtility;
use DERHANSEN\SfEventMgt\Utility\ShowInPreviews;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Event
 */
class Event extends AbstractEntity
{
    protected ?DateTime $tstamp = null;
    protected bool $hidden = false;
    protected ?DateTime $starttime = null;
    protected ?DateTime $endtime = null;
    protected string $title = '';
    protected string $teaser = '';
    protected string $description = '';
    protected string $program = '';
    protected ?DateTime $startdate = null;
    protected ?DateTime $enddate = null;
    protected int $maxParticipants = 0;
    protected int $maxRegistrationsPerUser = 1;
    protected float $price = 0.0;
    protected string $currency = '';
    protected bool $enablePayment = false;
    protected bool $restrictPaymentMethods = false;
    protected string $selectedPaymentMethods = '';
    protected ?DateTime $registrationStartdate = null;
    protected ?DateTime $registrationDeadline = null;
    protected ?Location $location = null;
    protected string $room = '';
    protected bool $enableRegistration = false;
    protected bool $enableWaitlist = false;
    protected bool $enableWaitlistMoveup = false;
    protected string $link = '';
    protected bool $topEvent = false;
    protected ?Organisator $organisator = null;
    protected bool $notifyAdmin = true;
    protected bool $notifyOrganisator = false;
    protected bool $enableCancel = false;
    protected ?DateTime $cancelDeadline = null;
    protected bool $enableAutoconfirm = false;
    protected bool $uniqueEmailCheck = false;

    /**
     * @var null|ObjectStorage<Category>
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $category = null;

    /**
     * @var null|ObjectStorage<Event>
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $related = null;

    /**
     * @var null|ObjectStorage<Registration>
     * @Extbase\ORM\Cascade("remove")
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $registration = null;

    /**
     * @var null|ObjectStorage<Registration>
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $registrationWaitlist = null;

    /**
     * @var null|ObjectStorage<Field>
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $registrationFields = null;

    /**
     * @var null|ObjectStorage<FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $image = null;

    /**
     * @var null|ObjectStorage<FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $files = null;

    /**
     * @var null|ObjectStorage<FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $additionalImage = null;

    /**
     * @var null|ObjectStorage<PriceOption>
     * @Extbase\ORM\Cascade("remove")
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $priceOptions = null;

    /**
     * @var null|ObjectStorage<Speaker>
     * @Extbase\ORM\Lazy
     */
    protected ?ObjectStorage $speaker = null;

    public function __construct()
    {
        $this->category = new ObjectStorage();
        $this->related = new ObjectStorage();
        $this->registration = new ObjectStorage();
        $this->registrationWaitlist = new ObjectStorage();
        $this->registrationFields = new ObjectStorage();
        $this->image = new ObjectStorage();
        $this->files = new ObjectStorage();
        $this->additionalImage = new ObjectStorage();
        $this->priceOptions = new ObjectStorage();
        $this->speaker = new ObjectStorage();
    }

    public function getTstamp(): ?DateTime
    {
        return $this->tstamp;
    }

    public function setTstamp(?DateTime $tstamp)
    {
        $this->tstamp = $tstamp;
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
    }

    public function getStarttime(): ?DateTime
    {
        return $this->starttime;
    }

    public function setStarttime(?DateTime $starttime)
    {
        $this->starttime = $starttime;
    }

    public function getEndtime(): ?DateTime
    {
        return $this->endtime;
    }

    public function setEndtime(?DateTime $endtime)
    {
        $this->endtime = $endtime;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTeaser(): string
    {
        return $this->teaser;
    }

    public function setTeaser(string $teaser)
    {
        $this->teaser = $teaser;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getProgram(): string
    {
        return $this->program;
    }

    public function setProgram(string $program)
    {
        $this->program = $program;
    }

    public function getStartdate(): ?DateTime
    {
        return $this->startdate;
    }

    public function setStartdate(?DateTime $startdate)
    {
        $this->startdate = $startdate;
    }

    public function getEnddate(): ?DateTime
    {
        return $this->enddate;
    }

    public function setEnddate(?DateTime $enddate)
    {
        $this->enddate = $enddate;
    }

    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $participants)
    {
        $this->maxParticipants = $participants;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    public function getEnablePayment(): bool
    {
        return $this->enablePayment;
    }

    public function setEnablePayment(bool $enablePayment)
    {
        $this->enablePayment = $enablePayment;
    }

    public function getRestrictPaymentMethods(): bool
    {
        return $this->restrictPaymentMethods;
    }

    public function setRestrictPaymentMethods(bool $restrictPaymentMethods)
    {
        $this->restrictPaymentMethods = $restrictPaymentMethods;
    }

    public function getSelectedPaymentMethods(): string
    {
        return $this->selectedPaymentMethods;
    }

    public function setSelectedPaymentMethods(string $selectedPaymentMethods)
    {
        $this->selectedPaymentMethods = $selectedPaymentMethods;
    }

    public function addCategory(Category $category)
    {
        $this->category->attach($category);
    }

    public function removeCategory(Category $categoryToRemove)
    {
        $this->category->detach($categoryToRemove);
    }

    public function getCategory(): ?ObjectStorage
    {
        return $this->category;
    }

    public function setCategory(?ObjectStorage $category)
    {
        $this->category = $category;
    }

    public function getRelated(): ?ObjectStorage
    {
        return $this->related;
    }

    public function setRelated(?ObjectStorage $related)
    {
        $this->related = $related;
    }

    public function addRelated(Event $event)
    {
        $this->related->attach($event);
    }

    public function removeRelated(Event $event)
    {
        $this->related->detach($event);
    }

    public function addRegistration(Registration $registration)
    {
        $this->registration->attach($registration);
    }

    public function removeRegistration(Registration $registrationToRemove)
    {
        $this->registration->detach($registrationToRemove);
    }

    public function getRegistration(): ?ObjectStorage
    {
        return $this->registration;
    }

    public function setRegistration(?ObjectStorage $registration)
    {
        $this->registration = $registration;
    }

    public function addImage(FileReference $image)
    {
        $this->image->attach($image);
    }

    public function removeImage(FileReference $imageToRemove)
    {
        $this->image->detach($imageToRemove);
    }

    public function getImage(): ?ObjectStorage
    {
        return $this->image;
    }

    public function getImages(): ?ObjectStorage
    {
        return $this->image;
    }

    public function getListViewImages(): ?ObjectStorage
    {
        return $this->getImagesByType(ShowInPreviews::LIST_VIEWS);
    }

    public function getFirstListViewImage(): ?FileReference
    {
        $images = $this->getImagesByType(ShowInPreviews::LIST_VIEWS);
        $image = $images->current();

        if (is_a($image, FileReference::class)) {
            return $image;
        }
        return null;
    }

    public function getDetailViewImages(): ?ObjectStorage
    {
        return $this->getImagesByType(ShowInPreviews::DETAIL_VIEWS);
    }

    public function getFirstDetailViewImage(): ?FileReference
    {
        $images = $this->getImagesByType(ShowInPreviews::DETAIL_VIEWS);
        $image = $images->current();

        if (is_a($image, FileReference::class)) {
            return $image;
        }
        return null;
    }

    protected function getImagesByType(int $type): ?ObjectStorage
    {
        $result = new ObjectStorage();

        foreach ($this->image as $image) {
            /** @var \TYPO3\CMS\Core\Resource\FileReference $fileReference */
            $fileReference = $image->getOriginalResource();
            if ($fileReference !== null && $fileReference->hasProperty('show_in_views') &&
                in_array($fileReference->getProperty('show_in_views'), [$type, ShowInPreviews::ALL_VIEWS])
            ) {
                $result->attach($image);
            }
        }

        return $result;
    }

    public function setImage(?ObjectStorage $image)
    {
        $this->image = $image;
    }

    public function addFiles(FileReference $file)
    {
        $this->files->attach($file);
    }

    public function removeFiles(FileReference $fileToRemove)
    {
        $this->files->detach($fileToRemove);
    }

    public function getFiles(): ?ObjectStorage
    {
        return $this->files;
    }

    public function setFiles(?ObjectStorage $files)
    {
        $this->files = $files;
    }

    /**
     * Returns if the registration for this event is logically possible
     *
     * @return bool
     */
    public function getRegistrationPossible(): bool
    {
        $maxParticipantsNotReached = true;
        if ($this->getMaxParticipants() > 0 && $this->getRegistrations()->count() >= $this->maxParticipants) {
            $maxParticipantsNotReached = false;
        }
        $deadlineNotReached = true;
        if ($this->getRegistrationDeadline() != null && $this->getRegistrationDeadline() <= new DateTime()) {
            $deadlineNotReached = false;
        }
        $registrationStartReached = true;
        if ($this->getRegistrationStartdate() != null && $this->getRegistrationStartdate() > new DateTime()) {
            $registrationStartReached = false;
        }

        return ($this->getStartdate() > new DateTime()) &&
        ($maxParticipantsNotReached || $this->enableWaitlist) &&
        $this->getEnableRegistration() && $deadlineNotReached && $registrationStartReached;
    }

    /**
     * Returns the amount of free places
     *
     * @return int
     */
    public function getFreePlaces(): int
    {
        return $this->maxParticipants - $this->getRegistrations()->count();
    }

    public function setLocation(?Location $location)
    {
        $this->location = $location;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    public function setRoom(string $room)
    {
        $this->room = $room;
    }

    public function setEnableRegistration(bool $enableRegistration)
    {
        $this->enableRegistration = $enableRegistration;
    }

    public function getEnableRegistration(): bool
    {
        return $this->enableRegistration;
    }

    public function getEnableWaitlist(): bool
    {
        return $this->enableWaitlist;
    }

    public function setEnableWaitlist(bool $enableWaitlist)
    {
        $this->enableWaitlist = $enableWaitlist;
    }

    public function getEnableWaitlistMoveup(): bool
    {
        return $this->enableWaitlistMoveup;
    }

    public function setEnableWaitlistMoveup(bool $enableWaitlistMoveup): void
    {
        $this->enableWaitlistMoveup = $enableWaitlistMoveup;
    }

    public function setRegistrationStartdate(?DateTime $registrationStartdate)
    {
        $this->registrationStartdate = $registrationStartdate;
    }

    public function getRegistrationStartdate(): ?DateTime
    {
        return $this->registrationStartdate;
    }

    public function setRegistrationDeadline(?DateTime $registrationDeadline)
    {
        $this->registrationDeadline = $registrationDeadline;
    }

    public function getRegistrationDeadline(): ?DateTime
    {
        return $this->registrationDeadline;
    }

    public function setLink(string $link)
    {
        $this->link = $link;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setTopEvent(bool $topEvent)
    {
        $this->topEvent = $topEvent;
    }

    public function getTopEvent(): bool
    {
        return $this->topEvent;
    }

    public function getMaxRegistrationsPerUser(): int
    {
        return $this->maxRegistrationsPerUser;
    }

    public function setMaxRegistrationsPerUser(int $maxRegistrationsPerUser)
    {
        $this->maxRegistrationsPerUser = $maxRegistrationsPerUser;
    }

    public function addAdditionalImage(FileReference $additionalImage)
    {
        $this->additionalImage->attach($additionalImage);
    }

    public function removeAdditionalImage(FileReference $additionalImageToRemove)
    {
        $this->additionalImage->detach($additionalImageToRemove);
    }

    public function getAdditionalImage(): ?ObjectStorage
    {
        return $this->additionalImage;
    }

    public function setAdditionalImage(?ObjectStorage $additionalImage)
    {
        $this->additionalImage = $additionalImage;
    }

    public function getOrganisator(): ?Organisator
    {
        return $this->organisator;
    }

    public function setOrganisator(Organisator $organisator)
    {
        $this->organisator = $organisator;
    }

    public function getNotifyAdmin(): bool
    {
        return $this->notifyAdmin;
    }

    public function setNotifyAdmin(bool $notifyAdmin)
    {
        $this->notifyAdmin = $notifyAdmin;
    }

    public function getNotifyOrganisator(): bool
    {
        return $this->notifyOrganisator;
    }

    public function setNotifyOrganisator(bool $notifyOrganisator)
    {
        $this->notifyOrganisator = $notifyOrganisator;
    }

    public function setEnableCancel(bool $enableCancel)
    {
        $this->enableCancel = $enableCancel;
    }

    public function getEnableCancel(): bool
    {
        return $this->enableCancel;
    }

    public function setCancelDeadline(?DateTime $cancelDeadline)
    {
        $this->cancelDeadline = $cancelDeadline;
    }

    public function getCancelDeadline(): ?DateTime
    {
        return $this->cancelDeadline;
    }

    public function getEnableAutoconfirm(): bool
    {
        return $this->enableAutoconfirm;
    }

    public function setEnableAutoconfirm(bool $enableAutoconfirm)
    {
        $this->enableAutoconfirm = $enableAutoconfirm;
    }

    public function getUniqueEmailCheck(): bool
    {
        return $this->uniqueEmailCheck;
    }

    public function setUniqueEmailCheck(bool $uniqueEmailCheck)
    {
        $this->uniqueEmailCheck = $uniqueEmailCheck;
    }

    public function getPriceOptions(): ?ObjectStorage
    {
        return $this->priceOptions;
    }

    public function setPriceOptions(?ObjectStorage $priceOptions)
    {
        $this->priceOptions = $priceOptions;
    }

    public function addPriceOptions(PriceOption $priceOption)
    {
        $this->priceOptions->attach($priceOption);
    }

    public function removePriceOptions(PriceOption $priceOption)
    {
        $this->priceOptions->detach($priceOption);
    }

    /**
     * Returns all active price options sorted by date ASC
     *
     * @return array
     */
    public function getActivePriceOptions(): array
    {
        $activePriceOptions = [];
        if ($this->getPriceOptions()) {
            $compareDate = new DateTime('today midnight');
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
    public function getCurrentPrice(): float
    {
        $activePriceOptions = $this->getActivePriceOptions();
        if (count($activePriceOptions) >= 1) {
            // Sort active price options and return first element
            return reset($activePriceOptions)->getPrice();
        }
        // Just return the price field
        return $this->price;
    }

    public function getRegistrationWaitlist(): ?ObjectStorage
    {
        return $this->registrationWaitlist;
    }

    public function setRegistrationWaitlist(?ObjectStorage $registration)
    {
        $this->registrationWaitlist = $registration;
    }

    public function addRegistrationWaitlist(Registration $registration)
    {
        $this->registrationWaitlist->attach($registration);
    }

    public function removeRegistrationWaitlist(Registration $registrationToRemove)
    {
        $this->registrationWaitlist->detach($registrationToRemove);
    }

    /**
     * Returns, if cancellation for registrations of the event is possible
     *
     * @return bool
     */
    public function getCancellationPossible(): bool
    {
        $today = new DateTime('today');

        return ($this->getEnableCancel() && $this->getCancelDeadline() > $today) ||
            ($this->getEnableCancel() && $this->getCancelDeadline() === null && $this->getStartdate() > $today);
    }

    public function getSpeaker(): ?ObjectStorage
    {
        return $this->speaker;
    }

    public function setSpeaker(?ObjectStorage $speaker)
    {
        $this->speaker = $speaker;
    }

    public function addSpeaker(Speaker $speaker)
    {
        $this->speaker->attach($speaker);
    }

    public function removeSpeaker(Speaker $speaker)
    {
        $this->speaker->detach($speaker);
    }

    public function getRegistrationFields(): ?ObjectStorage
    {
        return $this->registrationFields;
    }

    public function setRegistrationFields(?ObjectStorage $registrationFields)
    {
        $this->registrationFields = $registrationFields;
    }

    public function addRegistrationFields(Field $registrationField)
    {
        $this->registrationFields->attach($registrationField);
    }

    public function removeRegistrationFields(Field $registrationField)
    {
        $this->registrationFields->detach($registrationField);
    }

    public function getRegistrationFieldsUids(): array
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
    public function getRegistrationFieldUidsWithTitle(): array
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
    public function getRegistrations(): ?ObjectStorage
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
    public function getRegistrationsWaitlist(): ?ObjectStorage
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
    protected function getRegistrationsDefaultLanguage(bool $waitlist = false): ObjectStorage
    {
        $result = GeneralUtility::makeInstance(ObjectStorage::class);
        $registrationRepository = GeneralUtility::makeInstance(RegistrationRepository::class);
        $registrations = $registrationRepository->findByEventAndWaitlist($this, $waitlist);
        foreach ($registrations as $registration) {
            $result->attach($registration);
        }

        return $result;
    }

    public function getEndsSameDay(): bool
    {
        if ($this->enddate !== null) {
            return $this->startdate->format('d.m.Y') === $this->enddate->format('d.m.Y');
        }

        return true;
    }

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
        $overlay = '';
        if ($this->getHidden()) {
            $overlay = 'overlay-hidden';
        }
        if (!$this->getHidden() && ($this->getStarttime() || $this->getEndtime())) {
            $overlay = 'overlay-endtime';
        }

        return $overlay;
    }
}
