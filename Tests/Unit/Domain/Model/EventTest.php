<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

use DateInterval;
use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Category;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Location;
use DERHANSEN\SfEventMgt\Domain\Model\Organisator;
use DERHANSEN\SfEventMgt\Domain\Model\PriceOption;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Model\Speaker;
use DERHANSEN\SfEventMgt\Utility\ShowInPreviews;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EventTest extends UnitTestCase
{
    protected Event $subject;

    protected function setUp(): void
    {
        $this->subject = new Event();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    #[Test]
    public function setTitleForStringSetsTitle(): void
    {
        $this->subject->setTitle('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getTitle());
    }

    #[Test]
    public function getDescriptionReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    #[Test]
    public function setDescriptionForStringSetsDescription(): void
    {
        $this->subject->setDescription('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getDescription());
    }

    #[Test]
    public function getProgramReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getProgram()
        );
    }

    #[Test]
    public function setProgramForStringSetsProgram(): void
    {
        $this->subject->setProgram('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getProgram());
    }

    #[Test]
    public function getCustomTextReturnsInitialValueForString(): void
    {
        self::assertSame('', $this->subject->getCustomText());
    }

    #[Test]
    public function setCustomTextForStringSetsCustomText(): void
    {
        $this->subject->setCustomText('A custom text');
        self::assertEquals('A custom text', $this->subject->getCustomText());
    }

    #[Test]
    public function getTeaserReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTeaser()
        );
    }

    #[Test]
    public function setTeaserForStringSetsTeaser(): void
    {
        $this->subject->setTeaser('This is a teaser');
        self::assertEquals('This is a teaser', $this->subject->getTeaser());
    }

    #[Test]
    public function getStartdateReturnsInitialValueForDateTime(): void
    {
        self::assertNull(
            $this->subject->getStartdate()
        );
    }

    #[Test]
    public function setStartdateForDateTimeSetsStartdate(): void
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setStartdate($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getStartdate());
    }

    #[Test]
    public function getEnddateReturnsInitialValueForDateTime(): void
    {
        self::assertNull(
            $this->subject->getEnddate()
        );
    }

    #[Test]
    public function setEnddateForDateTimeSetsEnddate(): void
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setEnddate($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getEnddate());
    }

    #[Test]
    public function getParticipantsReturnsInitialValueForInteger(): void
    {
        self::assertSame(
            0,
            $this->subject->getMaxParticipants()
        );
    }

    #[Test]
    public function getTopEventReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getTopEvent()
        );
    }

    #[Test]
    public function setParticipantsForIntegerSetsParticipants(): void
    {
        $this->subject->setMaxParticipants(12);
        self::assertEquals(12, $this->subject->getMaxParticipants());
    }

    #[Test]
    public function getPriceReturnsInitialValueForFloat(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getPrice()
        );
    }

    #[Test]
    public function setPriceForFloatSetsPrice(): void
    {
        $this->subject->setPrice(3.99);
        self::assertEquals(3.99, $this->subject->getPrice());
    }

    #[Test]
    public function getCurrencyReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCurrency()
        );
    }

    #[Test]
    public function setCurrencyForStringSetsCurrency(): void
    {
        $this->subject->setCurrency('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getCurrency());
    }

    #[Test]
    public function getCategoryReturnsInitialValueForCategory(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getCategory()
        );
    }

    #[Test]
    public function setCategoryForObjectStorageContainingCategorySetsCategory(): void
    {
        $category = new Category();
        $objectStorageHoldingExactlyOneCategory = new ObjectStorage();
        $objectStorageHoldingExactlyOneCategory->attach($category);
        $this->subject->setCategory($objectStorageHoldingExactlyOneCategory);
        self::assertEquals($objectStorageHoldingExactlyOneCategory, $this->subject->getCategory());
    }

    #[Test]
    public function addCategoryToObjectStorageHoldingCategory(): void
    {
        $category = new Category();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($category);

        $this->subject->setCategory(new ObjectStorage());
        $this->subject->addCategory($category);

        self::assertEquals($objectStorage, $this->subject->getCategory());
    }

    #[Test]
    public function removeCategoryFromObjectStorageHoldingCategory(): void
    {
        $category = new Category();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($category);

        $this->subject->setCategory($objectStorage);
        $this->subject->removeCategory($category);

        self::assertEmpty($this->subject->getCategory());
    }

    #[Test]
    public function getCategoriesReturnsTheSameAsGetCategory(): void
    {
        $category = new Category();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($category);

        $this->subject->setCategory(new ObjectStorage());
        $this->subject->addCategory($category);

        self::assertEquals($objectStorage, $this->subject->getCategory());
        self::assertEquals($objectStorage, $this->subject->getCategories());
    }

    #[Test]
    public function getRegistrationReturnsInitialValueForRegistration(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRegistration()
        );
    }

    #[Test]
    public function setRegistrationForObjectStorageContainingRegistrationSetsRegistration(): void
    {
        $registration = new Registration();
        $objectStorageHoldingExactlyOneRegistration = new ObjectStorage();
        $objectStorageHoldingExactlyOneRegistration->attach($registration);
        $this->subject->setRegistration($objectStorageHoldingExactlyOneRegistration);
        self::assertEquals($objectStorageHoldingExactlyOneRegistration, $this->subject->getRegistration());
    }

    #[Test]
    public function addRegistrationToObjectStorageHoldingRegistration(): void
    {
        $registration = new Registration();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($registration);

        $this->subject->setRegistration(new ObjectStorage());
        $this->subject->addRegistration($registration);

        self::assertEquals($objectStorage, $this->subject->getRegistration());
    }

    #[Test]
    public function removeRegistrationFromObjectStorageHoldingRegistration(): void
    {
        $registration = new Registration();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($registration);

        $this->subject->setRegistration($objectStorage);
        $this->subject->removeRegistration($registration);

        self::assertEmpty($this->subject->getRegistration());
    }

    #[Test]
    public function getRegistrationWaitlistReturnsInitialValueForRegistrationWaitlist(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRegistrationWaitlist()
        );
    }

    #[Test]
    public function setRegistrationWaitlistForObjectStorageContainingRegistrationSetsRegistrationWaitlist(): void
    {
        $registration = new Registration();
        $objectStorageHoldingExactlyOneRegistration = new ObjectStorage();
        $objectStorageHoldingExactlyOneRegistration->attach($registration);
        $this->subject->setRegistrationWaitlist($objectStorageHoldingExactlyOneRegistration);
        self::assertEquals($objectStorageHoldingExactlyOneRegistration, $this->subject->getRegistrationWaitlist());
    }

    #[Test]
    public function addRegistrationWaitlistToObjectStorageHoldingRegistrationWaitlist(): void
    {
        $registration = new Registration();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($registration);

        $this->subject->setRegistrationWaitlist(new ObjectStorage());
        $this->subject->addRegistrationWaitlist($registration);

        self::assertEquals($objectStorage, $this->subject->getRegistrationWaitlist());
    }

    #[Test]
    public function removeRegistrationWaitlistFromObjectStorageHoldingRegistrationWaitlist(): void
    {
        $registration = new Registration();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($registration);

        $this->subject->setRegistrationWaitlist($objectStorage);
        $this->subject->removeRegistrationWaitlist($registration);

        self::assertEmpty($this->subject->getRegistrationWaitlist());
    }

    #[Test]
    public function getImageReturnsInitialValueForImage(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getImage()
        );
    }

    #[Test]
    public function setImageForObjectStorageContainingImageSetsImage(): void
    {
        $image = new FileReference();
        $objectStorageHoldingExactlyOneImage = new ObjectStorage();
        $objectStorageHoldingExactlyOneImage->attach($image);
        $this->subject->setImage($objectStorageHoldingExactlyOneImage);
        self::assertEquals($objectStorageHoldingExactlyOneImage, $this->subject->getImage());
    }

    #[Test]
    public function addImageToObjectStorageHoldingImage(): void
    {
        $fileReference = new FileReference();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($fileReference);

        $this->subject->setImage(new ObjectStorage());
        $this->subject->addImage($fileReference);

        self::assertEquals($objectStorage, $this->subject->getImage());
    }

    #[Test]
    public function removeImageFromObjectStorageHoldingImage(): void
    {
        $fileReference = new FileReference();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($fileReference);

        $this->subject->setImage($objectStorage);
        $this->subject->removeImage($fileReference);

        self::assertEmpty($this->subject->getImage());
    }

    #[Test]
    public function getFilesReturnsInitialValueForfiles(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getFiles()
        );
    }

    #[Test]
    public function setFilesForObjectStorageContainingFilesSetsFiles(): void
    {
        $file = new FileReference();
        $objectStorageHoldingExactlyOneFile = new ObjectStorage();
        $objectStorageHoldingExactlyOneFile->attach($file);
        $this->subject->setFiles($objectStorageHoldingExactlyOneFile);
        self::assertEquals($objectStorageHoldingExactlyOneFile, $this->subject->getFiles());
    }

    #[Test]
    public function addFilesToObjectStorageHoldingFiles(): void
    {
        $fileReference = new FileReference();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($fileReference);

        $this->subject->setFiles(new ObjectStorage());
        $this->subject->addFiles($fileReference);

        self::assertEquals($objectStorage, $this->subject->getFiles());
    }

    #[Test]
    public function removeFilesFromObjectStorageHoldingFiles(): void
    {
        $fileReference = new FileReference();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($fileReference);

        $this->subject->setFiles($objectStorage);
        $this->subject->removeFiles($fileReference);

        self::assertEmpty($this->subject->getFiles());
    }

    #[Test]
    public function getAdditionalImageReturnsInitialValueForfiles(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getAdditionalImage()
        );
    }

    #[Test]
    public function setAdditionalImageForObjectStorageContainingFilesSetsFiles(): void
    {
        $file = new FileReference();
        $objectStorageHoldingExactlyOneFile = new ObjectStorage();
        $objectStorageHoldingExactlyOneFile->attach($file);
        $this->subject->setAdditionalImage($objectStorageHoldingExactlyOneFile);
        self::assertEquals($objectStorageHoldingExactlyOneFile, $this->subject->getAdditionalImage());
    }

    #[Test]
    public function addAdditionalImageToObjectStorageHoldingFiles(): void
    {
        $fileReference = new FileReference();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($fileReference);

        $this->subject->setAdditionalImage(new ObjectStorage());
        $this->subject->addAdditionalImage($fileReference);

        self::assertEquals($objectStorage, $this->subject->getAdditionalImage());
    }

    #[Test]
    public function removeAdditionalImageFromObjectStorageHoldingFiles(): void
    {
        $fileReference = new FileReference();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($fileReference);

        $this->subject->setAdditionalImage($objectStorage);
        $this->subject->removeAdditionalImage($fileReference);

        self::assertEmpty($this->subject->getAdditionalImage());
    }

    #[Test]
    public function getRegistrationPossibleReturnsFalseIfEventHasTakenPlace(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('yesterday'));
        $this->subject->setStartdate($startdate);
        $this->subject->setEnableRegistration(true);

        self::assertFalse($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsTrueIfEventHasStartedAndRegistrationUntilEnddateIsAllowed(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('yesterday'));
        $enddate = new DateTime();
        $enddate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setEnddate($enddate);
        $this->subject->setEnableRegistration(true);
        $this->subject->setAllowRegistrationUntilEnddate(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsFalseIfEventHasEndedAndRegistrationUntilEnddateIsAllowed(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('yesterday - 1 day'));
        $enddate = new DateTime();
        $enddate->add(DateInterval::createFromDateString('yesterday'));
        $this->subject->setStartdate($startdate);
        $this->subject->setEnddate($enddate);
        $this->subject->setEnableRegistration(true);
        $this->subject->setAllowRegistrationUntilEnddate(true);

        self::assertFalse($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsFalseIfRegistrationNotEnabled(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);

        $this->subject->setEnableRegistration(false);
        self::assertFalse($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsFalseIfRegistrationDeadlineReached(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $deadline = new DateTime();
        $deadline->add(DateInterval::createFromDateString('yesterday'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setRegistrationDeadline($deadline);
        $this->subject->setEnableRegistration(true);

        self::assertFalse($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsTrueIfRegistrationStartdateReached(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $registrationStartDate = new DateTime();
        $registrationStartDate->add(DateInterval::createFromDateString('yesterday'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setRegistrationStartdate($registrationStartDate);
        $this->subject->setEnableRegistration(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsFalseIfRegistrationStartdateNotReached(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $registrationStartDate = new DateTime();
        $registrationStartDate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setRegistrationStartdate($registrationStartDate);
        $this->subject->setEnableRegistration(true);

        self::assertFalse($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsFalseIfEventMaxParticipantsReached(): void
    {
        $registration = new Registration();
        $registration->setFirstname('John');
        $registration->setLastname('Doe');

        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->addRegistration($registration);

        self::assertFalse($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsNotSet(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(0);
        $this->subject->setEnableRegistration(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsFalseIfMaxParticipantsSetAndEventFull(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setEnableRegistration(true);

        $registration = new Registration();
        $objectStorageHoldingExactlyOneRegistration = new ObjectStorage();
        $objectStorageHoldingExactlyOneRegistration->attach($registration);
        $this->subject->setRegistration($objectStorageHoldingExactlyOneRegistration);

        self::assertFalse($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsSetAndWaitlistEnabled(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setEnableRegistration(true);
        $this->subject->setEnableWaitlist(true);

        $registration = new Registration();
        $objectStorageHoldingExactlyOneRegistration = new ObjectStorage();
        $objectStorageHoldingExactlyOneRegistration->attach($registration);
        $this->subject->setRegistration($objectStorageHoldingExactlyOneRegistration);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsIsZero(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setEnableRegistration(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsTrueIfRegistrationDeadlineNotReached(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $deadline = new DateTime();
        $deadline->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setRegistrationDeadline($deadline);
        $this->subject->setEnableRegistration(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getRegistrationPossibleReturnsTrueIfRegistrationIsLogicallyPossible(): void
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setEnableRegistration(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    #[Test]
    public function getLocationReturnsInitialValueForLocation(): void
    {
        self::assertNull(
            $this->subject->getLocation()
        );
    }

    #[Test]
    public function setLocationSetsLocation(): void
    {
        $location = new Location();
        $this->subject->setLocation($location);
        self::assertEquals($location, $this->subject->getLocation());
    }

    #[Test]
    public function getEnableRegistrationReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getEnableRegistration()
        );
    }

    #[Test]
    public function setEnableRegistrationForBooleanSetsEnableRegistration(): void
    {
        $this->subject->setEnableRegistration(true);
        self::assertTrue($this->subject->getEnableRegistration());
    }

    #[Test]
    public function getEnableWaitlistReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getEnableWaitlist()
        );
    }

    #[Test]
    public function setEnableWaitlistForBooleanSetsEnableWaitlist(): void
    {
        $this->subject->setEnableWaitlist(true);
        self::assertTrue($this->subject->getEnableWaitlist());
    }

    #[Test]
    public function getEnableWaitlistMoveupReturnsInitialValueForBoolean(): void
    {
        self::assertFalse(
            $this->subject->getEnableWaitlistMoveup()
        );
    }

    #[Test]
    public function setEnableWaitlistMoveupForBooleanSetsEnableWaitlistMoveup(): void
    {
        $this->subject->setEnableWaitlistMoveup(true);
        self::assertTrue($this->subject->getEnableWaitlistMoveup());
    }

    #[Test]
    public function getLinkReturnsInitialValueForLink(): void
    {
        self::assertEquals(
            '',
            $this->subject->getLink()
        );
    }

    #[Test]
    public function setLinkForStringSetsLink(): void
    {
        $this->subject->setLink('www.domain.tld');
        self::assertEquals('www.domain.tld', $this->subject->getLink());
    }

    #[Test]
    public function getFreePlacesWithoutRegistrationsTest(): void
    {
        $this->subject->setMaxParticipants(10);

        self::assertEquals(
            10,
            $this->subject->getFreePlaces()
        );
    }

    #[Test]
    public function getFreePlacesWithRegistrationsTest(): void
    {
        $this->subject->setMaxParticipants(10);

        $registrationObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects(self::once())->method('count')->willReturn(5);
        $this->subject->setRegistration($registrationObjectStorageMock);

        self::assertEquals(
            5,
            $this->subject->getFreePlaces()
        );
    }

    #[Test]
    public function getRegistrationDeadlineReturnsInitialValueForDateTime(): void
    {
        self::assertNull(
            $this->subject->getRegistrationDeadline()
        );
    }

    #[Test]
    public function setRegistrationDeadlineForDateTimeSetsStartdate(): void
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setRegistrationDeadline($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getRegistrationDeadline());
    }

    #[Test]
    public function setTopEventForBooleanSetsTopEvent(): void
    {
        $this->subject->setTopEvent(true);
        self::assertTrue($this->subject->getTopEvent());
    }

    #[Test]
    public function maxRegistrationsPerUserReturnsInitialValue(): void
    {
        self::assertEquals(1, $this->subject->getMaxRegistrationsPerUser());
    }

    #[Test]
    public function maxRegistrationsPerUserSetsMaxRegistrationsPerUser(): void
    {
        $this->subject->setMaxRegistrationsPerUser(2);
        self::assertEquals(2, $this->subject->getMaxRegistrationsPerUser());
    }

    /**
     * Test if initial value for organisator is returned
     */
    #[Test]
    public function getOrganisatorReturnsInitialValueForOrganisator(): void
    {
        self::assertNull($this->subject->getOrganisator());
    }

    #[Test]
    public function getRoomReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getRoom());
    }

    public function setRoomSetsRoomForString(): void
    {
        $this->subject->setRoom('a room');
        self::assertEquals('a room', $this->subject->getRoom());
    }

    /**
     * Test if organisator can be set
     */
    #[Test]
    public function setPhoneForStringSetsPhone(): void
    {
        $organisator = new Organisator();
        $this->subject->setOrganisator($organisator);
        self::assertEquals($organisator, $this->subject->getOrganisator());
    }

    /**
     * Test if initial value for notifyAdmin (TRUE) is returned
     */
    #[Test]
    public function getNotityAdminReturnsInitialValue(): void
    {
        self::assertTrue($this->subject->getNotifyAdmin());
    }

    /**
     * Test if notifyAdmin can be set
     */
    #[Test]
    public function setNotifyAdminSetsValueForNotifyAdmin(): void
    {
        $this->subject->setNotifyAdmin(false);
        self::assertFalse($this->subject->getNotifyAdmin());
    }

    /**
     * Test if initial value for notifyOrganisator (FALSE) is returned
     */
    #[Test]
    public function getNotityOrganisatorReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getNotifyOrganisator());
    }

    /**
     * Test if notifyOrganisator can be set
     */
    #[Test]
    public function setNotifyOrganisatorSetsValueForNotifyOrganisator(): void
    {
        $this->subject->setNotifyOrganisator(true);
        self::assertTrue($this->subject->getNotifyOrganisator());
    }

    #[Test]
    public function uniqueEmailCheckReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getUniqueEmailCheck());
    }

    #[Test]
    public function uniqueEmailCheckSetsValueForBoolen(): void
    {
        $this->subject->setUniqueEmailCheck(true);
        self::assertTrue($this->subject->getUniqueEmailCheck());
    }

    #[Test]
    public function enableCancelReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->getEnableCancel());
    }

    #[Test]
    public function setEnableCancelSetsEnableCancelForBoolean(): void
    {
        $this->subject->setEnableCancel(true);
        self::assertTrue($this->subject->getEnableCancel());
    }

    #[Test]
    public function getCancelDeallineReturnsDefaultValue(): void
    {
        self::assertEquals(0, $this->subject->getCancelDeadline());
    }

    #[Test]
    public function setCancelDeallineSetsCancelDeadlineForDate(): void
    {
        $date = new DateTime();
        $this->subject->setCancelDeadline($date);
        self::assertEquals($date, $this->subject->getCancelDeadline());
    }

    #[Test]
    public function getEnablePaymentReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->getEnablePayment());
    }

    #[Test]
    public function setEnablePaymentSetsEnablePaymentForBoolean(): void
    {
        $this->subject->setEnablePayment(true);
        self::assertTrue($this->subject->getEnablePayment());
    }

    #[Test]
    public function getRestrictPaymentMethodsReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->getRestrictPaymentMethods());
    }

    #[Test]
    public function setRestrictPaymentMethodsSetsRestrictPaymentMethodsForBoolean(): void
    {
        $this->subject->setRestrictPaymentMethods(true);
        self::assertTrue($this->subject->getRestrictPaymentMethods());
    }

    #[Test]
    public function getSelectedPaymentMethodsReturnsDefaultValue(): void
    {
        self::assertEmpty($this->subject->getSelectedPaymentMethods());
    }

    #[Test]
    public function setSelectedPaymentMethodsSetsSelectedPaymentMethodforString(): void
    {
        $this->subject->setSelectedPaymentMethods('invoice,transfer');
        self::assertEquals('invoice,transfer', $this->subject->getSelectedPaymentMethods());
    }

    #[Test]
    public function getPriceOptionsReturnsInitialValueforObjectStorage(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getPriceOptions()
        );
    }

    #[Test]
    public function setPriceOptionSetsPriceOptionForPriceOption(): void
    {
        $priceOption = new PriceOption();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($priceOption);

        $this->subject->setPriceOptions($objectStorage);
        self::assertEquals($objectStorage, $this->subject->getPriceOptions());
    }

    #[Test]
    public function addPriceOptionAddsPriceOptionForPriceOption(): void
    {
        $object = new PriceOption();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setPriceOptions(new ObjectStorage());
        $this->subject->addPriceOptions($object);

        self::assertEquals($objectStorage, $this->subject->getPriceOptions());
    }

    #[Test]
    public function removePriceOptionRemovesPriceOptionForPriceOption(): void
    {
        $object = new PriceOption();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setPriceOptions($objectStorage);
        $this->subject->removePriceOptions($object);

        self::assertEmpty($this->subject->getPriceOptions());
    }

    #[Test]
    public function getActivePriceOptionsReturnsOnlyActivePriceOptions(): void
    {
        $dateYesterday = new DateTime('yesterday');
        $dateToday = new DateTime('today');
        $dateTomorrow = new DateTime('tomorrow');

        $priceOption1 = new PriceOption();
        $priceOption1->setPrice(10.00);
        $priceOption1->setValidUntil($dateYesterday);

        $priceOption2 = new PriceOption();
        $priceOption2->setPrice(12.00);
        $priceOption2->setValidUntil($dateToday);

        $priceOption3 = new PriceOption();
        $priceOption3->setPrice(14.00);
        $priceOption3->setValidUntil($dateTomorrow);

        $this->subject->addPriceOptions($priceOption1);
        $this->subject->addPriceOptions($priceOption2);
        $this->subject->addPriceOptions($priceOption3);

        $expected = [];
        $expected[12] = $priceOption2;
        $expected[14] = $priceOption3;

        self::assertEquals($expected, $this->subject->getActivePriceOptions());
    }

    #[Test]
    public function getCurrentPriceReturnsPriceIfNoPriceOptionsSet(): void
    {
        $this->subject->setPrice(12.99);
        self::assertEquals(12.99, $this->subject->getCurrentPrice());
    }

    #[Test]
    public function getCurrentPriceReturnsPriceOptionIfSet(): void
    {
        $this->subject->setPrice(19.99);

        $priceOption1 = new PriceOption();
        $priceOption1->setPrice(14.99);
        $priceOption1->setValidUntil(new DateTime('today'));

        $priceOption2 = new PriceOption();
        $priceOption2->setPrice(16.99);
        $priceOption2->setValidUntil(new DateTime('tomorrow'));

        $this->subject->addPriceOptions($priceOption1);
        $this->subject->addPriceOptions($priceOption2);

        self::assertEquals(14.99, $this->subject->getCurrentPrice());
    }

    #[Test]
    public function getRelatedReturnsInitialValueForObjectStorage(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRelated()
        );
    }

    #[Test]
    public function setRelatedSetsRelatedForRelated(): void
    {
        $object = new Event();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setRelated($objectStorage);
        self::assertEquals($objectStorage, $this->subject->getRelated());
    }

    #[Test]
    public function addRelatedAddsRelatedForRelated(): void
    {
        $object = new Event();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setRelated(new ObjectStorage());
        $this->subject->addRelated($object);

        self::assertEquals($objectStorage, $this->subject->getRelated());
    }

    #[Test]
    public function removeRelatedRemovesRelatedForRelated(): void
    {
        $object = new Event();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setRelated($objectStorage);
        $this->subject->removeRelated($object);

        self::assertEmpty($this->subject->getRelated());
    }

    /**
     * DataProvider for cancellationPossible tests
     *
     * @return array
     */
    public static function cancellationPossibleDataProvider(): array
    {
        return [
            'cancellationNotEnabled' => [
                false,
                new DateTime('tomorrow'),
                new DateTime('today'),
                false,
            ],
            'cancellationEnabledButDeadlineReached' => [
                true,
                new DateTime('tomorrow'),
                new DateTime('yesterday'),
                false,
            ],
            'cancellationEnabledDeadlineNotReached' => [
                true,
                (new DateTime('tomorrow'))->modify('+1 day'),
                new DateTime('tomorrow'),
                true,
            ],
            'cancellationEnabledDeadlineNotReachedEventExpired' => [
                true,
                new DateTime('yesterday'),
                new DateTime('tomorrow'),
                true,
            ],
        ];
    }

    #[DataProvider('cancellationPossibleDataProvider')]
    #[Test]
    public function getCancellationPossibleReturnsExpectedValues(
        bool $enabled,
        DateTime $eventDate,
        DateTime $deadline,
        bool $expected
    ): void {
        $this->subject->setStartdate($eventDate);
        $this->subject->setEnableCancel($enabled);
        $this->subject->setCancelDeadline($deadline);
        self::assertEquals($expected, $this->subject->getCancellationPossible());
    }

    #[Test]
    public function getCancellationPossibleReturnsTrueIfNoDeadlineSet(): void
    {
        $this->subject->setStartdate(new DateTime('tomorrow'));
        $this->subject->setEnableCancel(true);
        self::assertTrue($this->subject->getCancellationPossible());
    }

    #[Test]
    public function getCancellationPossibleReturnsFalseIfNoDeadlineSetButEventExpired(): void
    {
        $this->subject->setStartdate(new DateTime('yesterday'));
        $this->subject->setEnableCancel(true);
        self::assertFalse($this->subject->getCancellationPossible());
    }

    #[Test]
    public function enableAutoconfirmReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->getEnableAutoconfirm());
    }

    #[Test]
    public function setEnableAutoconfirmSetsAutoconfirmForBoolean(): void
    {
        $this->subject->setEnableAutoconfirm(true);
        self::assertTrue($this->subject->getEnableAutoconfirm());
    }

    #[Test]
    public function getSpeakerReturnsInitialValueforObjectStorage(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getSpeaker()
        );
    }

    #[Test]
    public function setSpeakerSetsSpeaker(): void
    {
        $speaker = new Speaker();
        $objectStorageWithSpeaker = new ObjectStorage();
        $objectStorageWithSpeaker->attach($speaker);
        $this->subject->setSpeaker($objectStorageWithSpeaker);
        self::assertEquals($objectStorageWithSpeaker, $this->subject->getSpeaker());
    }

    #[Test]
    public function addSpeakerAddsSpeaker(): void
    {
        $object = new Speaker();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setSpeaker(new ObjectStorage());
        $this->subject->addSpeaker($object);

        self::assertEquals($objectStorage, $this->subject->getSpeaker());
    }

    #[Test]
    public function removeSpeakerRemovesSpeaker(): void
    {
        $object = new Speaker();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setSpeaker($objectStorage);
        $this->subject->removeSpeaker($object);

        $this->assertempty($this->subject->getSpeaker());
    }

    #[Test]
    public function getRegistrationFieldsReturnsInitialValueforObjectStorage(): void
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRegistrationFields()
        );
    }

    #[Test]
    public function setRegistrationFieldsSetsRegistrationFields(): void
    {
        $registrationField = new Field();
        $objectStorageWithRegistrationField = new ObjectStorage();
        $objectStorageWithRegistrationField->attach($registrationField);
        $this->subject->setRegistrationFields($objectStorageWithRegistrationField);
        self::assertEquals($objectStorageWithRegistrationField, $this->subject->getRegistrationFields());
    }

    #[Test]
    public function addRegistrationFieldsAddsRegistrationField(): void
    {
        $object = new Field();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setRegistrationFields(new ObjectStorage());
        $this->subject->addRegistrationFields($object);

        self::assertEquals($objectStorage, $this->subject->getRegistrationFields());
    }

    #[Test]
    public function removeRegistrationFieldsRemovesRegistrationField(): void
    {
        $object = new Field();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->setRegistrationFields($objectStorage);
        $this->subject->removeRegistrationFields($object);

        self::assertEmpty($this->subject->getRegistrationFields());
    }

    #[Test]
    public function getRegistrationFieldsUidsReturnsEmptyArrayIfNotRegistrationFields(): void
    {
        self::assertEquals([], $this->subject->getRegistrationFieldsUids());
    }

    #[Test]
    public function getRegistrationFieldsUidsReturnsExpectedArrayForEventWithRegistrationFields(): void
    {
        $mockRegField = $this->getMockBuilder(Field::class)->getMock();
        $mockRegField->expects(self::any())->method('getUid')->willReturn(1);
        $this->subject->addRegistrationFields($mockRegField);

        self::assertEquals([0 => 1], $this->subject->getRegistrationFieldsUids());
    }

    #[Test]
    public function getRegistrationFieldUidsWithTitleReturnsEmptyArrayIfNotRegistrationFields(): void
    {
        self::assertEquals([], $this->subject->getRegistrationFieldUidsWithTitle());
    }

    #[Test]
    public function getRegistrationFieldUidsWithTitleReturnsExpectedArrayForEventWithRegistrationFields(): void
    {
        $mockRegField1 = $this->getMockBuilder(Field::class)->getMock();
        $mockRegField1->expects(self::any())->method('getUid')->willReturn(1);
        $mockRegField1->expects(self::any())->method('getTitle')->willReturn('A Title');
        $mockRegField2 = $this->getMockBuilder(Field::class)->getMock();
        $mockRegField2->expects(self::any())->method('getUid')->willReturn(2);
        $mockRegField2->expects(self::any())->method('getTitle')->willReturn('Another Title');
        $this->subject->addRegistrationFields($mockRegField1);
        $this->subject->addRegistrationFields($mockRegField2);

        $expected = [
            1 => 'A Title',
            2 => 'Another Title',
        ];
        self::assertEquals($expected, $this->subject->getRegistrationFieldUidsWithTitle());
    }

    /**
     * DataProvider for cancellationPossible tests
     *
     * @return array
     */
    public static function eventEndsSameDayDataProvider(): array
    {
        return [
            'no start- and enddate' => [
                null,
                null,
                true,
            ],
            'no enddate' => [
                DateTime::createFromFormat('d.m.Y H:i:s', '01.01.2019 14:00:00'),
                null,
                true,
            ],
            'start- and enddate same day' => [
                DateTime::createFromFormat('d.m.Y H:i:s', '01.01.2019 14:00:00'),
                DateTime::createFromFormat('d.m.Y H:i:s', '01.01.2019 18:00:00'),
                true,
            ],
            'start- and enddate on different day' => [
                DateTime::createFromFormat('d.m.Y H:i:s', '01.01.2019 14:00:00'),
                DateTime::createFromFormat('d.m.Y H:i:s', '02.01.2019 10:00:00'),
                false,
            ],
        ];
    }

    #[DataProvider('eventEndsSameDayDataProvider')]
    #[Test]
    public function eventEndsSameDayReturnsExpectedValue(?DateTime $startdate, ?DateTime $enddate, bool $expected): void
    {
        if ($startdate) {
            $this->subject->setStartdate($startdate);
        }
        if ($enddate) {
            $this->subject->setEnddate($enddate);
        }
        self::assertEquals($expected, $this->subject->getEndsSameDay());
    }

    #[Test]
    public function getHiddenReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getHidden());
    }

    #[Test]
    public function setHiddenSetsValueForBoolean(): void
    {
        $this->subject->setHidden(true);
        self::assertTrue($this->subject->getHidden());
    }

    #[Test]
    public function getStarttimeReturnsInitialValue(): void
    {
        self::assertNull($this->subject->getStarttime());
    }

    #[Test]
    public function setStarttimeSetsValueForDateTime(): void
    {
        $date = new DateTime('01.01.2020 18:00:00');
        $this->subject->setStarttime($date);

        self::assertEquals($date, $this->subject->getStarttime());
    }

    #[Test]
    public function getEndtimeReturnsInitialValue(): void
    {
        self::assertNull($this->subject->getEndtime());
    }

    #[Test]
    public function setEndtimeSetsValueForDateTime(): void
    {
        $date = new DateTime('01.01.2020 18:00:00');
        $this->subject->setEndtime($date);

        self::assertEquals($date, $this->subject->getEndtime());
    }

    #[Test]
    public function getBackendIconOverlayReturnsEmptyStringIfNotHiddenAndNoStartEndTime(): void
    {
        self::assertEquals('', $this->subject->getBackendIconOverlay());
    }

    #[Test]
    public function getBackendIconOverlayReturnsExpectedValueForHiddenEvent(): void
    {
        $this->subject->setHidden(true);
        self::assertEquals('overlay-hidden', $this->subject->getBackendIconOverlay());
    }

    #[Test]
    public function getBackendIconOverlayReturnsExpectedValueForNotStartedStarttimeEvent(): void
    {
        $this->subject->setStarttime((new DateTime())->modify('+1 day'));
        self::assertEquals('overlay-scheduled', $this->subject->getBackendIconOverlay());
    }

    #[Test]
    public function getBackendIconOverlayReturnsExpectedValueForStartedStarttimeEvent(): void
    {
        $this->subject->setStarttime((new DateTime())->modify('-1 day'));
        self::assertEquals('', $this->subject->getBackendIconOverlay());
    }

    #[Test]
    public function getBackendIconOverlayReturnsExpectedValueForEndedEndtimeEvent(): void
    {
        $this->subject->setEndtime((new DateTime())->modify('-1 day'));
        self::assertEquals('overlay-endtime', $this->subject->getBackendIconOverlay());
    }

    #[Test]
    public function getBackendIconOverlayReturnsExpectedValueForNotEndedEndtimeEvent(): void
    {
        $this->subject->setEndtime((new DateTime())->modify('+1 day'));
        self::assertEquals('overlay-scheduled', $this->subject->getBackendIconOverlay());
    }

    #[Test]
    public function getBackendIconOverlayReturnsExpectedValueForHiddenAndStarttimeEvent(): void
    {
        $this->subject->setHidden(true);
        $this->subject->setEndtime(new DateTime());
        self::assertEquals('overlay-hidden', $this->subject->getBackendIconOverlay());
    }

    #[Test]
    public function getImagesReturnsValueOfImage(): void
    {
        $image = new FileReference();
        $objectStorageHoldingExactlyOneImage = new ObjectStorage();
        $objectStorageHoldingExactlyOneImage->attach($image);
        $this->subject->setImage($objectStorageHoldingExactlyOneImage);
        self::assertEquals($objectStorageHoldingExactlyOneImage, $this->subject->getImages());
    }

    #[Test]
    public function getMetaKeywordsReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getMetaKeywords());
    }

    #[Test]
    public function setMetaKeywordsSetsKeywords(): void
    {
        $this->subject->setMetaKeywords('keyword1, keyword2');
        self::assertEquals('keyword1, keyword2', $this->subject->getMetaKeywords());
    }

    #[Test]
    public function getMetaDescriptionReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getMetaDescription());
    }

    #[Test]
    public function setMetaDescriptionSetsDescription(): void
    {
        $this->subject->setMetaDescription('the description');
        self::assertEquals('the description', $this->subject->getMetaDescription());
    }

    #[Test]
    public function getAlternativeTitleReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getAlternativeTitle());
    }

    #[Test]
    public function setAlternativetitleSetsAlternativeTitle(): void
    {
        $this->subject->setAlternativeTitle('the alternative title');
        self::assertEquals('the alternative title', $this->subject->getAlternativeTitle());
    }

    #[Test]
    public function getMetaTitleReturnsTitleWhenNoAlternativeTitle(): void
    {
        $this->subject->setTitle('the title');

        self::assertEquals('the title', $this->subject->getMetaTitle());
    }

    #[Test]
    public function getMetaTitleReturnsAlternativeTitleWhenNoAlternativeTitle(): void
    {
        $this->subject->setTitle('the title');
        $this->subject->setAlternativeTitle('the alternative title');

        self::assertEquals('the alternative title', $this->subject->getMetaTitle());
    }

    #[Test]
    public function specialGettersForImagesReturnsExpectedResults(): void
    {
        $file1 = $this->getMockBuilder(File::class)->disableOriginalConstructor()->getMock();

        $fileReference1 = $this->getMockBuilder(CoreFileReference::class)->disableOriginalConstructor()->getMock();
        $fileReference1->expects(self::any())->method('getOriginalFile')->willReturn($file1);
        $fileReference1->expects(self::any())->method('hasProperty')->with('show_in_views')->willReturn(true);
        $fileReference1->expects(self::any())->method('getProperty')->with('show_in_views')->willReturn(ShowInPreviews::LIST_VIEWS);

        $extbaseFileReference1 = $this->getMockBuilder(FileReference::class)->disableOriginalConstructor()->getMock();
        $extbaseFileReference1->expects(self::any())->method('getOriginalResource')->willReturn($fileReference1);

        $file2 = $this->getMockBuilder(File::class)->disableOriginalConstructor()->getMock();

        $fileReference2 = $this->getMockBuilder(CoreFileReference::class)->disableOriginalConstructor()->getMock();
        $fileReference2->expects(self::any())->method('getOriginalFile')->willReturn($file2);
        $fileReference2->expects(self::any())->method('hasProperty')->with('show_in_views')->willReturn(true);
        $fileReference2->expects(self::any())->method('getProperty')->with('show_in_views')->willReturn(ShowInPreviews::DETAIL_VIEWS);

        $extbaseFileReference2 = $this->getMockBuilder(FileReference::class)->disableOriginalConstructor()->getMock();
        $extbaseFileReference2->expects(self::any())->method('getOriginalResource')->willReturn($fileReference2);

        $objectStorage = new ObjectStorage();
        $objectStorage->attach($extbaseFileReference1);
        $objectStorage->attach($extbaseFileReference2);

        $this->subject->setImage($objectStorage);

        self::assertEquals(1, $this->subject->getListViewImages()->count());
        self::assertEquals($extbaseFileReference1, $this->subject->getListViewImages()->current());

        self::assertEquals(1, $this->subject->getDetailViewImages()->count());
        self::assertEquals($extbaseFileReference2, $this->subject->getListViewImages()->current());

        self::assertEquals($extbaseFileReference1, $this->subject->getFirstListViewImage());
        self::assertEquals($extbaseFileReference2, $this->subject->getFirstDetailViewImage());
    }

    #[Test]
    public function getCacheTagLifetimeRespectsRegistrationStartdateIfNotReached(): void
    {
        $dateNow = new DateTime('01.10.2024 10:00');

        $eventStartdate = new DateTime('10.10.2024 10:00');
        $registrationStartdate = new DateTime('01.10.2024 12:00');

        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate($eventStartdate);
        $event->setRegistrationStartdate($registrationStartdate);

        self::assertEquals(7201, $event->getCacheTagLifetime($dateNow));
    }

    #[Test]
    public function getCacheTagLifetimeRespectsRegistrationDeadlineIfNotReached(): void
    {
        $dateNow = new DateTime('01.10.2024 10:00');

        $eventStartdate = new DateTime('10.10.2024 10:00');
        $registrationStartdate = new DateTime('01.10.2024 08:00');
        $registrationDeadline = new DateTime('01.10.2024 16:00');

        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate($eventStartdate);
        $event->setRegistrationStartdate($registrationStartdate);
        $event->setRegistrationDeadline($registrationDeadline);

        self::assertEquals(21601, $event->getCacheTagLifetime($dateNow));
    }

    #[Test]
    public function getCacheTagLifetimeRespectsEventStartdateIfNotReached(): void
    {
        $dateNow = new DateTime('01.10.2024 10:00');
        $eventStartdate = new DateTime('02.10.2024 10:00');

        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate($eventStartdate);

        self::assertEquals(86401, $event->getCacheTagLifetime($dateNow));
    }

    #[Test]
    public function getCacheTagLifetimeReturnsMaxLifetimeIfEventStarted(): void
    {
        $dateNow = new DateTime('01.10.2024 10:00');
        $eventStartdate = new DateTime('01.10.2024 08:00');

        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate($eventStartdate);

        self::assertEquals(PHP_INT_MAX, $event->getCacheTagLifetime($dateNow));
    }

    public static function eventGetIsStartedDataProvider(): array
    {
        return [
            'no startdate set' => [
                null,
                false,
            ],
            'startdate in future' => [
                (new DateTime('now'))->modify('+1 day'),
                false,
            ],
            'startdate in past' => [
                (new DateTime('now'))->modify('-1 day'),
                true,
            ],
        ];
    }

    #[DataProvider('eventGetIsStartedDataProvider')]
    #[Test]
    public function getIsStartedReturnsExpectedValue(?DateTime $startdate, bool $expected): void
    {
        $this->subject->setStartdate($startdate);
        self::assertEquals($expected, $this->subject->getIsStarted());
    }

    public static function eventGetIsEndedDataProvider(): array
    {
        return [
            'no enddate set' => [
                null,
                false,
            ],
            'enddate in future' => [
                (new DateTime('now'))->modify('+1 day'),
                false,
            ],
            'enddate in past' => [
                (new DateTime('now'))->modify('-1 day'),
                true,
            ],
        ];
    }

    #[DataProvider('eventGetIsEndedDataProvider')]
    #[Test]
    public function getIsEndedReturnsExpectedValue(?DateTime $enddate, bool $expected): void
    {
        $this->subject->setEnddate($enddate);
        self::assertEquals($expected, $this->subject->getIsEnded());
    }

    public static function eventGetIsInProgressDataProvider(): array
    {
        return [
            'no dates set' => [
                null,
                null,
                false,
            ],
            'startdate in past and no enddate' => [
                (new DateTime('now'))->modify('-1 day'),
                null,
                false,
            ],
            'startdate in past and enddate in past' => [
                (new DateTime('now'))->modify('-2 day'),
                (new DateTime('now'))->modify('-1 day'),
                false,
            ],
            'startdate in past and enddate in future' => [
                (new DateTime('now'))->modify('-1 day'),
                (new DateTime('now'))->modify('+1 day'),
                true,
            ],
        ];
    }

    #[DataProvider('eventGetIsInProgressDataProvider')]
    #[Test]
    public function getIsInProgressReturnsExpectedValue(?DateTime $startdate, ?DateTime $enddate, bool $expected): void
    {
        $this->subject->setStartdate($startdate);
        $this->subject->setEnddate($enddate);
        self::assertEquals($expected, $this->subject->getIsInProgress());
    }
}
