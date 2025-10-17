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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
    protected string $customText = '';
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
    protected bool $allowRegistrationUntilEnddate = false;
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
    protected string $metaKeywords = '';
    protected string $metaDescription = '';
    protected string $alternativeTitle = '';

    /**
     * @var ObjectStorage<Category>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $category;

    /**
     * @var ObjectStorage<Event>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $related;

    /**
     * @var ObjectStorage<Registration>
     * @Extbase\ORM\Cascade("remove")
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $registration;

    /**
     * @var ObjectStorage<Registration>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $registrationWaitlist;

    /**
     * @var ObjectStorage<Field>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $registrationFields;

    /**
     * @var ObjectStorage<FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $image;

    /**
     * @var ObjectStorage<FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $files;

    /**
     * @var ObjectStorage<FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $additionalImage;

    /**
     * @var ObjectStorage<PriceOption>
     * @Extbase\ORM\Cascade("remove")
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $priceOptions;

    /**
     * @var ObjectStorage<Speaker>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $speaker;

    public function __construct()
    {
        $this->initializeObject();
    }

    /**
     * Initialize all ObjectStorages as fetching an entity from the DB does not use the constructor
     */
    public function initializeObject(): void
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

    public function setTstamp(?DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    public function getStarttime(): ?DateTime
    {
        return $this->starttime;
    }

    public function setStarttime(?DateTime $starttime): void
    {
        $this->starttime = $starttime;
    }

    public function getEndtime(): ?DateTime
    {
        return $this->endtime;
    }

    public function setEndtime(?DateTime $endtime): void
    {
        $this->endtime = $endtime;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTeaser(): string
    {
        return $this->teaser;
    }

    public function setTeaser(string $teaser): void
    {
        $this->teaser = $teaser;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getProgram(): string
    {
        return $this->program;
    }

    public function setProgram(string $program): void
    {
        $this->program = $program;
    }

    public function getCustomText(): string
    {
        return $this->customText;
    }

    public function setCustomText(string $customText): void
    {
        $this->customText = $customText;
    }

    public function getStartdate(): ?DateTime
    {
        return $this->startdate;
    }

    public function setStartdate(?DateTime $startdate): void
    {
        $this->startdate = $startdate;
    }

    public function getEnddate(): ?DateTime
    {
        return $this->enddate;
    }

    public function setEnddate(?DateTime $enddate): void
    {
        $this->enddate = $enddate;
    }

    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $participants): void
    {
        $this->maxParticipants = $participants;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getEnablePayment(): bool
    {
        return $this->enablePayment;
    }

    public function setEnablePayment(bool $enablePayment): void
    {
        $this->enablePayment = $enablePayment;
    }

    public function getRestrictPaymentMethods(): bool
    {
        return $this->restrictPaymentMethods;
    }

    public function setRestrictPaymentMethods(bool $restrictPaymentMethods): void
    {
        $this->restrictPaymentMethods = $restrictPaymentMethods;
    }

    public function getSelectedPaymentMethods(): string
    {
        return $this->selectedPaymentMethods;
    }

    public function setSelectedPaymentMethods(string $selectedPaymentMethods): void
    {
        $this->selectedPaymentMethods = $selectedPaymentMethods;
    }

    public function addCategory(Category $category): void
    {
        $this->category->attach($category);
    }

    public function removeCategory(Category $categoryToRemove): void
    {
        $this->category->detach($categoryToRemove);
    }

    public function getCategory(): ?ObjectStorage
    {
        return $this->category;
    }

    public function getCategories(): ?ObjectStorage
    {
        return $this->category;
    }

    public function setCategory(?ObjectStorage $category): void
    {
        $this->category = $category;
    }

    public function getRelated(): ?ObjectStorage
    {
        return $this->related;
    }

    public function setRelated(?ObjectStorage $related): void
    {
        $this->related = $related;
    }

    public function addRelated(Event $event): void
    {
        $this->related->attach($event);
    }

    public function removeRelated(Event $event): void
    {
        $this->related->detach($event);
    }

    public function addRegistration(Registration $registration): void
    {
        $this->registration->attach($registration);
    }

    public function removeRegistration(Registration $registrationToRemove): void
    {
        $this->registration->detach($registrationToRemove);
    }

    public function getRegistration(): ?ObjectStorage
    {
        return $this->registration;
    }

    public function setRegistration(?ObjectStorage $registration): void
    {
        $this->registration = $registration;
    }

    public function addImage(FileReference $image): void
    {
        $this->image->attach($image);
    }

    public function removeImage(FileReference $imageToRemove): void
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

    public function setImage(?ObjectStorage $image): void
    {
        $this->image = $image;
    }

    public function addFiles(FileReference $file): void
    {
        $this->files->attach($file);
    }

    public function removeFiles(FileReference $fileToRemove): void
    {
        $this->files->detach($fileToRemove);
    }

    public function getFiles(): ?ObjectStorage
    {
        return $this->files;
    }

    public function setFiles(?ObjectStorage $files): void
    {
        $this->files = $files;
    }

    /**
     * Returns if the registration for this event is logically possible
     */
    public function getRegistrationPossible(): bool
    {
        $maxParticipantsNotReached = true;
        if ($this->getMaxParticipants() > 0 && $this->getRegistrations()->count() >= $this->maxParticipants) {
            $maxParticipantsNotReached = false;
        }
        $deadlineNotReached = true;
        if ($this->getRegistrationDeadline() !== null && $this->getRegistrationDeadline() <= new DateTime()) {
            $deadlineNotReached = false;
        }
        $registrationStartReached = true;
        if ($this->getRegistrationStartdate() !== null && $this->getRegistrationStartdate() > new DateTime()) {
            $registrationStartReached = false;
        }

        $allowedByEventDate = false;
        if ($this->getStartdate() > new DateTime()) {
            $allowedByEventDate = true;
        }

        if ($allowedByEventDate === false &&
            $this->getEnddate() &&
            $this->getAllowRegistrationUntilEnddate() &&
            $this->getEnddate() > new DateTime()
        ) {
            $allowedByEventDate = true;
        }

        return $allowedByEventDate &&
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

    public function setLocation(?Location $location): void
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

    public function setRoom(string $room): void
    {
        $this->room = $room;
    }

    public function setEnableRegistration(bool $enableRegistration): void
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

    public function setEnableWaitlist(bool $enableWaitlist): void
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

    public function setRegistrationStartdate(?DateTime $registrationStartdate): void
    {
        $this->registrationStartdate = $registrationStartdate;
    }

    public function getRegistrationStartdate(): ?DateTime
    {
        return $this->registrationStartdate;
    }

    public function setRegistrationDeadline(?DateTime $registrationDeadline): void
    {
        $this->registrationDeadline = $registrationDeadline;
    }

    public function getRegistrationDeadline(): ?DateTime
    {
        return $this->registrationDeadline;
    }

    public function getAllowRegistrationUntilEnddate(): bool
    {
        return $this->allowRegistrationUntilEnddate;
    }

    public function setAllowRegistrationUntilEnddate(bool $allowRegistrationUntilEnddate): void
    {
        $this->allowRegistrationUntilEnddate = $allowRegistrationUntilEnddate;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setTopEvent(bool $topEvent): void
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

    public function setMaxRegistrationsPerUser(int $maxRegistrationsPerUser): void
    {
        $this->maxRegistrationsPerUser = $maxRegistrationsPerUser;
    }

    public function addAdditionalImage(FileReference $additionalImage): void
    {
        $this->additionalImage->attach($additionalImage);
    }

    public function removeAdditionalImage(FileReference $additionalImageToRemove): void
    {
        $this->additionalImage->detach($additionalImageToRemove);
    }

    public function getAdditionalImage(): ?ObjectStorage
    {
        return $this->additionalImage;
    }

    public function setAdditionalImage(?ObjectStorage $additionalImage): void
    {
        $this->additionalImage = $additionalImage;
    }

    public function getOrganisator(): ?Organisator
    {
        return $this->organisator;
    }

    public function setOrganisator(Organisator $organisator): void
    {
        $this->organisator = $organisator;
    }

    public function getNotifyAdmin(): bool
    {
        return $this->notifyAdmin;
    }

    public function setNotifyAdmin(bool $notifyAdmin): void
    {
        $this->notifyAdmin = $notifyAdmin;
    }

    public function getNotifyOrganisator(): bool
    {
        return $this->notifyOrganisator;
    }

    public function setNotifyOrganisator(bool $notifyOrganisator): void
    {
        $this->notifyOrganisator = $notifyOrganisator;
    }

    public function setEnableCancel(bool $enableCancel): void
    {
        $this->enableCancel = $enableCancel;
    }

    public function getEnableCancel(): bool
    {
        return $this->enableCancel;
    }

    public function setCancelDeadline(?DateTime $cancelDeadline): void
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

    public function setEnableAutoconfirm(bool $enableAutoconfirm): void
    {
        $this->enableAutoconfirm = $enableAutoconfirm;
    }

    public function getUniqueEmailCheck(): bool
    {
        return $this->uniqueEmailCheck;
    }

    public function setUniqueEmailCheck(bool $uniqueEmailCheck): void
    {
        $this->uniqueEmailCheck = $uniqueEmailCheck;
    }

    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(string $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
    }

    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    public function getAlternativeTitle(): string
    {
        return $this->alternativeTitle;
    }

    public function setAlternativeTitle(string $alternativeTitle): void
    {
        $this->alternativeTitle = $alternativeTitle;
    }

    public function getMetaTitle(): string
    {
        return $this->getAlternativeTitle() !== '' ? $this->getAlternativeTitle() : $this->getTitle();
    }

    public function getPriceOptions(): ?ObjectStorage
    {
        return $this->priceOptions;
    }

    public function setPriceOptions(?ObjectStorage $priceOptions): void
    {
        $this->priceOptions = $priceOptions;
    }

    public function addPriceOptions(PriceOption $priceOption): void
    {
        $this->priceOptions->attach($priceOption);
    }

    public function removePriceOptions(PriceOption $priceOption): void
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

    public function setRegistrationWaitlist(?ObjectStorage $registration): void
    {
        $this->registrationWaitlist = $registration;
    }

    public function addRegistrationWaitlist(Registration $registration): void
    {
        $this->registrationWaitlist->attach($registration);
    }

    public function removeRegistrationWaitlist(Registration $registrationToRemove): void
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

    public function setSpeaker(?ObjectStorage $speaker): void
    {
        $this->speaker = $speaker;
    }

    public function addSpeaker(Speaker $speaker): void
    {
        $this->speaker->attach($speaker);
    }

    public function removeSpeaker(Speaker $speaker): void
    {
        $this->speaker->detach($speaker);
    }

    public function getRegistrationFields(): ?ObjectStorage
    {
        return $this->registrationFields;
    }

    public function setRegistrationFields(?ObjectStorage $registrationFields): void
    {
        $this->registrationFields = $registrationFields;
    }

    public function addRegistrationFields(Field $registrationField): void
    {
        $this->registrationFields->attach($registrationField);
    }

    public function removeRegistrationFields(Field $registrationField): void
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
     * @return ObjectStorage
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
     * @return ObjectStorage
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

    public function getIsStarted(): bool
    {
        return $this->startdate !== null && $this->startdate < new \DateTime();
    }

    public function getIsEnded(): bool
    {
        return $this->enddate !== null && $this->enddate < new \DateTime();
    }

    public function getIsInProgress(): bool
    {
        return $this->startdate !== null && $this->enddate !== null
            && $this->startdate < new \DateTime()
            && $this->enddate > new \DateTime();
    }
}
