<?php

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
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Event.
 */
class EventTest extends UnitTestCase
{
    /**
     * @var Event
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new Event();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
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
        self::assertEquals('Conceived at T3CON10', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription()
    {
        $this->subject->setDescription('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getDescription());
    }

    /**
     * @test
     */
    public function getProgramReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getProgram()
        );
    }

    /**
     * @test
     */
    public function setProgramForStringSetsProgram()
    {
        $this->subject->setProgram('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getProgram());
    }

    /**
     * @test
     */
    public function getTeaserReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTeaser()
        );
    }

    /**
     * @test
     */
    public function setTeaserForStringSetsTeaser()
    {
        $this->subject->setTeaser('This is a teaser');
        self::assertEquals('This is a teaser', $this->subject->getTeaser());
    }

    /**
     * @test
     */
    public function getStartdateReturnsInitialValueForDateTime()
    {
        self::assertNull(
            $this->subject->getStartdate()
        );
    }

    /**
     * @test
     */
    public function setStartdateForDateTimeSetsStartdate()
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setStartdate($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getStartdate());
    }

    /**
     * @test
     */
    public function getEnddateReturnsInitialValueForDateTime()
    {
        self::assertNull(
            $this->subject->getEnddate()
        );
    }

    /**
     * @test
     */
    public function setEnddateForDateTimeSetsEnddate()
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setEnddate($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getEnddate());
    }

    /**
     * @test
     */
    public function getParticipantsReturnsInitialValueForInteger()
    {
        self::assertSame(
            0,
            $this->subject->getMaxParticipants()
        );
    }

    /**
     * @test
     */
    public function getTopEventReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getTopEvent()
        );
    }

    /**
     * @test
     */
    public function setParticipantsForIntegerSetsParticipants()
    {
        $this->subject->setMaxParticipants(12);
        self::assertEquals(12, $this->subject->getMaxParticipants());
    }

    /**
     * @test
     */
    public function getPriceReturnsInitialValueForFloat()
    {
        self::assertSame(
            0.0,
            $this->subject->getPrice()
        );
    }

    /**
     * @test
     */
    public function setPriceForFloatSetsPrice()
    {
        $this->subject->setPrice(3.99);
        self::assertEquals(3.99, $this->subject->getPrice());
    }

    /**
     * @test
     */
    public function getCurrencyReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCurrency()
        );
    }

    /**
     * @test
     */
    public function setCurrencyForStringSetsCurrency()
    {
        $this->subject->setCurrency('Conceived at T3CON10');
        self::assertEquals('Conceived at T3CON10', $this->subject->getCurrency());
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValueForCategory()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setCategoryForObjectStorageContainingCategorySetsCategory()
    {
        $category = new Category();
        $objectStorageHoldingExactlyOneCategory = new ObjectStorage();
        $objectStorageHoldingExactlyOneCategory->attach($category);
        $this->subject->setCategory($objectStorageHoldingExactlyOneCategory);
        self::assertEquals($objectStorageHoldingExactlyOneCategory, $this->subject->getCategory());
    }

    /**
     * @test
     */
    public function addCategoryToObjectStorageHoldingCategory()
    {
        $category = new Category();
        $categoryObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($category));
        $this->inject($this->subject, 'category', $categoryObjectStorageMock);

        $this->subject->addCategory($category);
    }

    /**
     * @test
     */
    public function removeCategoryFromObjectStorageHoldingCategory()
    {
        $category = new Category();
        $categoryObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $categoryObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($category));
        $this->inject($this->subject, 'category', $categoryObjectStorageMock);

        $this->subject->removeCategory($category);
    }

    /**
     * @test
     */
    public function getRegistrationReturnsInitialValueForRegistration()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRegistration()
        );
    }

    /**
     * @test
     */
    public function setRegistrationForObjectStorageContainingRegistrationSetsRegistration()
    {
        $registration = new Registration();
        $objectStorageHoldingExactlyOneRegistration = new ObjectStorage();
        $objectStorageHoldingExactlyOneRegistration->attach($registration);
        $this->subject->setRegistration($objectStorageHoldingExactlyOneRegistration);
        self::assertEquals($objectStorageHoldingExactlyOneRegistration, $this->subject->getRegistration());
    }

    /**
     * @test
     */
    public function addRegistrationToObjectStorageHoldingRegistration()
    {
        $registration = new Registration();
        $registrationObjectStorageMock = $this->getMockBuilder(
            ObjectStorage::class
        )->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($registration));
        $this->inject($this->subject, 'registration', $registrationObjectStorageMock);

        $this->subject->addRegistration($registration);
    }

    /**
     * @test
     */
    public function removeRegistrationFromObjectStorageHoldingRegistration()
    {
        $registration = new Registration();
        $registrationObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($registration));
        $this->inject($this->subject, 'registration', $registrationObjectStorageMock);

        $this->subject->removeRegistration($registration);
    }

    /**
     * @test
     */
    public function getRegistrationWaitlistReturnsInitialValueForRegistrationWaitlist()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRegistrationWaitlist()
        );
    }

    /**
     * @test
     */
    public function setRegistrationWaitlistForObjectStorageContainingRegistrationSetsRegistrationWaitlist()
    {
        $registration = new Registration();
        $objectStorageHoldingExactlyOneRegistration = new ObjectStorage();
        $objectStorageHoldingExactlyOneRegistration->attach($registration);
        $this->subject->setRegistrationWaitlist($objectStorageHoldingExactlyOneRegistration);
        self::assertEquals($objectStorageHoldingExactlyOneRegistration, $this->subject->getRegistrationWaitlist());
    }

    /**
     * @test
     */
    public function addRegistrationWaitlistToObjectStorageHoldingRegistrationWaitlist()
    {
        $registration = new Registration();
        $registrationObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($registration));
        $this->inject($this->subject, 'registrationWaitlist', $registrationObjectStorageMock);

        $this->subject->addRegistrationWaitlist($registration);
    }

    /**
     * @test
     */
    public function removeRegistrationWaitlistFromObjectStorageHoldingRegistrationWaitlist()
    {
        $registration = new Registration();
        $registrationObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($registration));
        $this->inject($this->subject, 'registrationWaitlist', $registrationObjectStorageMock);

        $this->subject->removeRegistrationWaitlist($registration);
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValueForImage()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getImage()
        );
    }

    /**
     * @test
     */
    public function setImageForObjectStorageContainingImageSetsImage()
    {
        $image = new FileReference();
        $objectStorageHoldingExactlyOneImage = new ObjectStorage();
        $objectStorageHoldingExactlyOneImage->attach($image);
        $this->subject->setImage($objectStorageHoldingExactlyOneImage);
        self::assertEquals($objectStorageHoldingExactlyOneImage, $this->subject->getImage());
    }

    /**
     * @test
     */
    public function addImageToObjectStorageHoldingImage()
    {
        $image = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($image));
        $this->inject($this->subject, 'image', $imageObjectStorageMock);

        $this->subject->addImage($image);
    }

    /**
     * @test
     */
    public function removeImageFromObjectStorageHoldingImage()
    {
        $image = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($image));
        $this->inject($this->subject, 'image', $imageObjectStorageMock);

        $this->subject->removeImage($image);
    }

    /**
     * @test
     */
    public function getFilesReturnsInitialValueForfiles()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getFiles()
        );
    }

    /**
     * @test
     */
    public function setFilesForObjectStorageContainingFilesSetsFiles()
    {
        $file = new FileReference();
        $objectStorageHoldingExactlyOneFile = new ObjectStorage();
        $objectStorageHoldingExactlyOneFile->attach($file);
        $this->subject->setFiles($objectStorageHoldingExactlyOneFile);
        self::assertEquals($objectStorageHoldingExactlyOneFile, $this->subject->getFiles());
    }

    /**
     * @test
     */
    public function addFilesToObjectStorageHoldingFiles()
    {
        $files = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($files));
        $this->inject($this->subject, 'files', $imageObjectStorageMock);

        $this->subject->addFiles($files);
    }

    /**
     * @test
     */
    public function removeFilesFromObjectStorageHoldingFiles()
    {
        $files = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($files));
        $this->inject($this->subject, 'files', $imageObjectStorageMock);

        $this->subject->removeFiles($files);
    }

    /**
     * @test
     */
    public function getAdditionalImageReturnsInitialValueForfiles()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getAdditionalImage()
        );
    }

    /**
     * @test
     */
    public function setAdditionalImageForObjectStorageContainingFilesSetsFiles()
    {
        $file = new FileReference();
        $objectStorageHoldingExactlyOneFile = new ObjectStorage();
        $objectStorageHoldingExactlyOneFile->attach($file);
        $this->subject->setAdditionalImage($objectStorageHoldingExactlyOneFile);
        self::assertEquals($objectStorageHoldingExactlyOneFile, $this->subject->getAdditionalImage());
    }

    /**
     * @test
     */
    public function addAdditionalImageToObjectStorageHoldingFiles()
    {
        $files = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($files));
        $this->inject($this->subject, 'additionalImage', $imageObjectStorageMock);

        $this->subject->addAdditionalImage($files);
    }

    /**
     * @test
     */
    public function removeAdditionalImageFromObjectStorageHoldingFiles()
    {
        $files = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($files));
        $this->inject($this->subject, 'additionalImage', $imageObjectStorageMock);

        $this->subject->removeAdditionalImage($files);
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfEventHasTakenPlace()
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('yesterday'));
        $this->subject->setStartdate($startdate);
        $this->subject->setEnableRegistration(true);

        self::assertFalse($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfRegistrationNotEnabled()
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);

        $this->subject->setEnableRegistration(false);
        self::assertFalse($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfRegistrationDeadlineReached()
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

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfRegistrationStartdateReached()
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

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfRegistrationStartdateNotReached()
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

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfEventMaxParticipantsReached()
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

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsNotSet()
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(0);
        $this->subject->setEnableRegistration(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfMaxParticipantsSetAndEventFull()
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

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsSetAndWaitlistEnabled()
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

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsIsZero()
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setEnableRegistration(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfRegistrationDeadlineNotReached()
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

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfRegistrationIsLogicallyPossible()
    {
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setEnableRegistration(true);

        self::assertTrue($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getLocationReturnsInitialValueForLocation()
    {
        self::assertNull(
            $this->subject->getLocation()
        );
    }

    /**
     * @test
     */
    public function setLocationSetsLocation()
    {
        $location = new Location();
        $this->subject->setLocation($location);
        self::assertEquals($location, $this->subject->getLocation());
    }

    /**
     * @test
     */
    public function getEnableRegistrationReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getEnableRegistration()
        );
    }

    /**
     * @test
     */
    public function setEnableRegistrationForBooleanSetsEnableRegistration()
    {
        $this->subject->setEnableRegistration(true);
        self::assertTrue($this->subject->getEnableRegistration());
    }

    /**
     * @test
     */
    public function getEnableWaitlistReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getEnableWaitlist()
        );
    }

    /**
     * @test
     */
    public function setEnableWaitlistForBooleanSetsEnableWaitlist()
    {
        $this->subject->setEnableWaitlist(true);
        self::assertTrue($this->subject->getEnableWaitlist());
    }

    /**
     * @test
     */
    public function getEnableWaitlistMoveupReturnsInitialValueForBoolean()
    {
        self::assertFalse(
            $this->subject->getEnableWaitlistMoveup()
        );
    }

    /**
     * @test
     */
    public function setEnableWaitlistMoveupForBooleanSetsEnableWaitlistMoveup()
    {
        $this->subject->setEnableWaitlistMoveup(true);
        self::assertTrue($this->subject->getEnableWaitlistMoveup());
    }

    /**
     * @test
     */
    public function getLinkReturnsInitialValueForLink()
    {
        self::assertNull(
            $this->subject->getLink()
        );
    }

    /**
     * @test
     */
    public function setLinkForStringSetsLink()
    {
        $this->subject->setLink('www.domain.tld');
        self::assertEquals('www.domain.tld', $this->subject->getLink());
    }

    /**
     * @test
     */
    public function getFreePlacesWithoutRegistrationsTest()
    {
        $this->subject->setMaxParticipants(10);

        self::assertEquals(
            10,
            $this->subject->getFreePlaces()
        );
    }

    /**
     * @test
     */
    public function getFreePlacesWithRegistrationsTest()
    {
        $this->subject->setMaxParticipants(10);

        $registrationObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects(self::once())->method('count')->willReturn(5);
        $this->inject($this->subject, 'registration', $registrationObjectStorageMock);

        self::assertEquals(
            5,
            $this->subject->getFreePlaces()
        );
    }

    /**
     * @test
     */
    public function getRegistrationDeadlineReturnsInitialValueForDateTime()
    {
        self::assertNull(
            $this->subject->getRegistrationDeadline()
        );
    }

    /**
     * @test
     */
    public function setRegistrationDeadlineForDateTimeSetsStartdate()
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setRegistrationDeadline($dateTimeFixture);
        self::assertEquals($dateTimeFixture, $this->subject->getRegistrationDeadline());
    }

    /**
     * @test
     */
    public function setTopEventForBooleanSetsTopEvent()
    {
        $this->subject->setTopEvent(true);
        self::assertTrue($this->subject->getTopEvent());
    }

    /**
     * @test
     */
    public function maxRegistrationsPerUserReturnsInitialValue()
    {
        self::assertEquals(1, $this->subject->getMaxRegistrationsPerUser());
    }

    /**
     * @test
     */
    public function maxRegistrationsPerUserSetsMaxRegistrationsPerUser()
    {
        $this->subject->setMaxRegistrationsPerUser(2);
        self::assertEquals(2, $this->subject->getMaxRegistrationsPerUser());
    }

    /**
     * Test if initial value for organisator is returned
     *
     * @test
     */
    public function getOrganisatorReturnsInitialValueForOrganisator()
    {
        self::assertNull($this->subject->getOrganisator());
    }

    /**
     * Test if organisator can be set
     *
     * @test
     */
    public function setPhoneForStringSetsPhone()
    {
        $organisator = new Organisator();
        $this->subject->setOrganisator($organisator);
        self::assertEquals($organisator, $this->subject->getOrganisator());
    }

    /**
     * Test if initial value for notifyAdmin (TRUE) is returned
     *
     * @test
     */
    public function getNotityAdminReturnsInitialValue()
    {
        self::assertTrue($this->subject->getNotifyAdmin());
    }

    /**
     * Test if notifyAdmin can be set
     *
     * @test
     */
    public function setNotifyAdminSetsValueForNotifyAdmin()
    {
        $this->subject->setNotifyAdmin(false);
        self::assertFalse($this->subject->getNotifyAdmin());
    }

    /**
     * Test if initial value for notifyOrganisator (FALSE) is returned
     *
     * @test
     */
    public function getNotityOrganisatorReturnsInitialValue()
    {
        self::assertFalse($this->subject->getNotifyOrganisator());
    }

    /**
     * Test if notifyOrganisator can be set
     *
     * @test
     */
    public function setNotifyOrganisatorSetsValueForNotifyOrganisator()
    {
        $this->subject->setNotifyOrganisator(true);
        self::assertTrue($this->subject->getNotifyOrganisator());
    }

    /**
     * @test
     */
    public function uniqueEmailCheckReturnsInitialValue()
    {
        self::assertFalse($this->subject->getUniqueEmailCheck());
    }

    /**
     * @test
     */
    public function uniqueEmailCheckSetsValueForBoolen()
    {
        $this->subject->setUniqueEmailCheck(true);
        self::assertTrue($this->subject->getUniqueEmailCheck());
    }

    /**
     * @test
     */
    public function enableCancelReturnsDefaultValue()
    {
        self::assertFalse($this->subject->getEnableCancel());
    }

    /**
     * @test
     */
    public function setEnableCancelSetsEnableCancelForBoolean()
    {
        $this->subject->setEnableCancel(true);
        self::assertTrue($this->subject->getEnableCancel());
    }

    /**
     * @test
     */
    public function getCancelDeallineReturnsDefaultValue()
    {
        self::assertEquals(0, $this->subject->getCancelDeadline());
    }

    /**
     * @test
     */
    public function setCancelDeallineSetsCancelDeadlineForDate()
    {
        $date = new DateTime();
        $this->subject->setCancelDeadline($date);
        self::assertEquals($date, $this->subject->getCancelDeadline());
    }

    /**
     * @test
     */
    public function getEnablePaymentReturnsDefaultValue()
    {
        self::assertFalse($this->subject->getEnablePayment());
    }

    /**
     * @test
     */
    public function setEnablePaymentSetsEnablePaymentForBoolean()
    {
        $this->subject->setEnablePayment(true);
        self::assertTrue($this->subject->getEnablePayment());
    }

    /**
     * @test
     */
    public function getRestrictPaymentMethodsReturnsDefaultValue()
    {
        self::assertFalse($this->subject->getRestrictPaymentMethods());
    }

    /**
     * @test
     */
    public function setRestrictPaymentMethodsSetsRestrictPaymentMethodsForBoolean()
    {
        $this->subject->setRestrictPaymentMethods(true);
        self::assertTrue($this->subject->getRestrictPaymentMethods());
    }

    /**
     * @test
     */
    public function getSelectedPaymentMethodsReturnsDefaultValue()
    {
        self::assertEmpty($this->subject->getSelectedPaymentMethods());
    }

    /**
     * @test
     */
    public function setSelectedPaymentMethodsSetsSelectedPaymentMethodforString()
    {
        $this->subject->setSelectedPaymentMethods('invoice,transfer');
        self::assertEquals('invoice,transfer', $this->subject->getSelectedPaymentMethods());
    }

    /**
     * @test
     */
    public function getPriceOptionsReturnsInitialValueforObjectStorage()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getPriceOptions()
        );
    }

    /**
     * @test
     */
    public function setPriceOptionSetsPriceOptionForPriceOption()
    {
        $priceOption = new PriceOption();
        $this->subject->setPriceOptions($priceOption);
        self::assertEquals($priceOption, $this->subject->getPriceOptions());
    }

    /**
     * @test
     */
    public function addPriceOptionAddsPriceOptionForPriceOption()
    {
        $priceOption = new PriceOption();
        $priceOptionObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $priceOptionObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($priceOption));
        $this->inject($this->subject, 'priceOptions', $priceOptionObjectStorageMock);

        $this->subject->addPriceOptions($priceOption);
    }

    /**
     * @test
     */
    public function removePriceOptionRemovesPriceOptionForPriceOption()
    {
        $priceOption = new PriceOption();
        $priceOptionObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $priceOptionObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($priceOption));
        $this->inject($this->subject, 'priceOptions', $priceOptionObjectStorageMock);

        $this->subject->removePriceOptions($priceOption);
    }

    /**
     * @test
     */
    public function getActivePriceOptionsReturnsOnlyActivePriceOptions()
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
        $expected[$dateToday->getTimestamp()] = $priceOption2;
        $expected[$dateTomorrow->getTimestamp()] = $priceOption3;

        self::assertEquals($expected, $this->subject->getActivePriceOptions());
    }

    /**
     * @test
     */
    public function getCurrentPriceReturnsPriceIfNoPriceOptionsSet()
    {
        $this->subject->setPrice(12.99);
        self::assertEquals(12.99, $this->subject->getCurrentPrice());
    }

    /**
     * @test
     */
    public function getCurrentPriceReturnsPriceOptionIfSet()
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

    /**
     * @test
     */
    public function getRelatedReturnsInitialValueForObjectStorage()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRelated()
        );
    }

    /**
     * @test
     */
    public function setRelatedSetsRelatedForRelated()
    {
        $event = new Event();
        $this->subject->setRelated($event);
        self::assertEquals($event, $this->subject->getRelated());
    }

    /**
     * @test
     */
    public function addRelatedAddsRelatedForRelated()
    {
        $event = new Event();
        $relatedObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $relatedObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($event));
        $this->inject($this->subject, 'related', $relatedObjectStorageMock);

        $this->subject->addRelated($event);
    }

    /**
     * @test
     */
    public function removeRelatedRemovesRelatedForRelated()
    {
        $event = new Event();
        $relatedObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $relatedObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($event));
        $this->inject($this->subject, 'related', $relatedObjectStorageMock);

        $this->subject->removeRelated($event);
    }

    /**
     * DataProvider for cancellationPossible tests
     *
     * @return array
     */
    public function cancellationPossibleDataProvider()
    {
        return [
            'cancellationNotEnabled' => [
                false,
                new DateTime('tomorrow'),
                new DateTime('today'),
                false
            ],
            'cancellationEnabledButDeadlineReached' => [
                true,
                new DateTime('tomorrow'),
                new DateTime('yesterday'),
                false
            ],
            'cancellationEnabledDeadlineNotReached' => [
                true,
                (new DateTime('tomorrow'))->modify('+1 day'),
                new DateTime('tomorrow'),
                true
            ],
            'cancellationEnabledDeadlineNotReachedEventExpired' => [
                true,
                new DateTime('yesterday'),
                new DateTime('tomorrow'),
                true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider cancellationPossibleDataProvider
     * @param mixed $enabled
     * @param mixed $deadline
     * @param mixed $expected
     * @param mixed $eventDate
     */
    public function getCancellationPossibleReturnsExpectedValues($enabled, $eventDate, $deadline, $expected)
    {
        $this->subject->setStartdate($eventDate);
        $this->subject->setEnableCancel($enabled);
        $this->subject->setCancelDeadline($deadline);
        self::assertEquals($expected, $this->subject->getCancellationPossible());
    }

    /**
     * @test
     */
    public function getCancellationPossibleReturnsTrueIfNoDeadlineSet()
    {
        $this->subject->setStartdate(new DateTime('tomorrow'));
        $this->subject->setEnableCancel(true);
        self::assertTrue($this->subject->getCancellationPossible());
    }

    /**
     * @test
     */
    public function getCancellationPossibleReturnsFalseIfNoDeadlineSetButEventExpired()
    {
        $this->subject->setStartdate(new DateTime('yesterday'));
        $this->subject->setEnableCancel(true);
        self::assertFalse($this->subject->getCancellationPossible());
    }

    /**
     * @test
     */
    public function enableAutoconfirmReturnsDefaultValue()
    {
        self::assertFalse($this->subject->getEnableAutoconfirm());
    }

    /**
     * @test
     */
    public function setEnableAutoconfirmSetsAutoconfirmForBoolean()
    {
        $this->subject->setEnableAutoconfirm(true);
        self::assertTrue($this->subject->getEnableAutoconfirm());
    }

    /**
     * @test
     */
    public function getSpeakerReturnsInitialValueforObjectStorage()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getSpeaker()
        );
    }

    /**
     * @test
     */
    public function setSpeakerSetsSpeaker()
    {
        $speaker = new Speaker();
        $objectStorageWithSpeaker = new ObjectStorage();
        $objectStorageWithSpeaker->attach($speaker);
        $this->subject->setSpeaker($objectStorageWithSpeaker);
        self::assertEquals($objectStorageWithSpeaker, $this->subject->getSpeaker());
    }

    /**
     * @test
     */
    public function addSpeakerAddsSpeaker()
    {
        $speaker = new Speaker();
        $speakerObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $speakerObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($speaker));
        $this->inject($this->subject, 'speaker', $speakerObjectStorageMock);

        $this->subject->addSpeaker($speaker);
    }

    /**
     * @test
     */
    public function removeSpeakerRemovesSpeaker()
    {
        $speaker = new Speaker();
        $speakerObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $speakerObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($speaker));
        $this->inject($this->subject, 'speaker', $speakerObjectStorageMock);

        $this->subject->removeSpeaker($speaker);
    }

    /**
     * @test
     */
    public function getRegistrationFieldsReturnsInitialValueforObjectStorage()
    {
        $newObjectStorage = new ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRegistrationFields()
        );
    }

    /**
     * @test
     */
    public function setRegistrationFieldsSetsRegistrationFields()
    {
        $registrationField = new Field();
        $objectStorageWithRegistrationField = new ObjectStorage();
        $objectStorageWithRegistrationField->attach($registrationField);
        $this->subject->setRegistrationFields($objectStorageWithRegistrationField);
        self::assertEquals($objectStorageWithRegistrationField, $this->subject->getRegistrationFields());
    }

    /**
     * @test
     */
    public function addRegistrationFieldsAddsRegistrationField()
    {
        $registrationField = new Field();
        $registrationFieldStorage = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registrationFieldStorage->expects(self::once())->method('attach')->with(self::equalTo($registrationField));
        $this->inject($this->subject, 'registrationFields', $registrationFieldStorage);

        $this->subject->addRegistrationFields($registrationField);
    }

    /**
     * @test
     */
    public function removeRegistrationFieldsRemovesRegistrationField()
    {
        $registrationField = new Field();
        $registrationFieldStorage = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationFieldStorage->expects(self::once())->method('detach')->with(self::equalTo($registrationField));
        $this->inject($this->subject, 'registrationFields', $registrationFieldStorage);

        $this->subject->removeRegistrationFields($registrationField);
    }

    /**
     * @test
     */
    public function getRegistrationFieldsUidsReturnsEmptyArrayIfNotRegistrationFields()
    {
        self::assertEquals([], $this->subject->getRegistrationFieldsUids());
    }

    /**
     * @test
     */
    public function getRegistrationFieldsUidsReturnsExpectedArrayForEventWithRegistrationFields()
    {
        $mockRegField = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegField->expects(self::any())->method('getUid')->willReturn(1);
        $this->subject->addRegistrationFields($mockRegField);

        self::assertEquals([0 => 1], $this->subject->getRegistrationFieldsUids());
    }

    /**
     * @test
     */
    public function getRegistrationFieldUidsWithTitleReturnsEmptyArrayIfNotRegistrationFields()
    {
        self::assertEquals([], $this->subject->getRegistrationFieldUidsWithTitle());
    }

    /**
     * @test
     */
    public function getRegistrationFieldUidsWithTitleReturnsExpectedArrayForEventWithRegistrationFields()
    {
        $mockRegField1 = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegField1->expects(self::any())->method('getUid')->willReturn(1);
        $mockRegField1->expects(self::any())->method('getTitle')->willReturn('A Title');
        $mockRegField2 = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegField2->expects(self::any())->method('getUid')->willReturn(2);
        $mockRegField2->expects(self::any())->method('getTitle')->willReturn('Another Title');
        $this->subject->addRegistrationFields($mockRegField1);
        $this->subject->addRegistrationFields($mockRegField2);

        $expected = [
            1 => 'A Title',
            2 => 'Another Title'
        ];
        self::assertEquals($expected, $this->subject->getRegistrationFieldUidsWithTitle());
    }

    /**
     * DataProvider for cancellationPossible tests
     *
     * @return array
     */
    public function eventEndsSameDayDataProvider()
    {
        return [
            'no start- and enddate' => [
                null,
                null,
                true
            ],
            'no enddate' => [
                DateTime::createFromFormat('d.m.Y H:i:s', '01.01.2019 14:00:00'),
                null,
                true
            ],
            'start- and enddate same day' => [
                DateTime::createFromFormat('d.m.Y H:i:s', '01.01.2019 14:00:00'),
                DateTime::createFromFormat('d.m.Y H:i:s', '01.01.2019 18:00:00'),
                true
            ],
            'start- and enddate on different day' => [
                DateTime::createFromFormat('d.m.Y H:i:s', '01.01.2019 14:00:00'),
                DateTime::createFromFormat('d.m.Y H:i:s', '02.01.2019 10:00:00'),
                false
            ],
        ];
    }

    /**
     * @test
     * @dataProvider eventEndsSameDayDataProvider
     * @param mixed $startdate
     * @param mixed $enddate
     * @param mixed $expected
     */
    public function eventEndsSameDayReturnsExpectedValue($startdate, $enddate, $expected)
    {
        if ($startdate) {
            $this->subject->setStartdate($startdate);
        }
        if ($enddate) {
            $this->subject->setEnddate($enddate);
        }
        self::assertEquals($expected, $this->subject->getEndsSameDay());
    }

    /**
     * @test
     */
    public function getHiddenReturnsInitialValue()
    {
        self::assertFalse($this->subject->getHidden());
    }

    /**
     * @test
     */
    public function setHiddenSetsValueForBoolean()
    {
        $this->subject->setHidden(true);
        self::assertTrue($this->subject->getHidden());
    }

    /**
     * @test
     */
    public function getStarttimeReturnsInitialValue()
    {
        self::assertNull($this->subject->getStarttime());
    }

    /**
     * @test
     */
    public function setStarttimeSetsValueForDateTime()
    {
        $date = new DateTime('01.01.2020 18:00:00');
        $this->subject->setStarttime($date);

        self::assertEquals($date, $this->subject->getStarttime());
    }

    /**
     * @test
     */
    public function getEndtimeReturnsInitialValue()
    {
        self::assertNull($this->subject->getEndtime());
    }

    /**
     * @test
     */
    public function setEndtimeSetsValueForDateTime()
    {
        $date = new DateTime('01.01.2020 18:00:00');
        $this->subject->setEndtime($date);

        self::assertEquals($date, $this->subject->getEndtime());
    }

    /**
     * @test
     */
    public function getBackendIconOverlayReturnsEmptyStringIfNotHiddenAndNoStartEndTime()
    {
        self::assertEquals('', $this->subject->getBackendIconOverlay());
    }

    /**
     * @test
     */
    public function getBackendIconOverlayReturnsExpectedValueForHiddenEvent()
    {
        $this->subject->setHidden(true);
        self::assertEquals('overlay-hidden', $this->subject->getBackendIconOverlay());
    }

    /**
     * @test
     */
    public function getBackendIconOverlayReturnsExpectedValueForStarttimeEvent()
    {
        $this->subject->setStarttime(new DateTime());
        self::assertEquals('overlay-endtime', $this->subject->getBackendIconOverlay());
    }

    /**
     * @test
     */
    public function getBackendIconOverlayReturnsExpectedValueForEndtimeEvent()
    {
        $this->subject->setEndtime(new DateTime());
        self::assertEquals('overlay-endtime', $this->subject->getBackendIconOverlay());
    }

    /**
     * @test
     */
    public function getBackendIconOverlayReturnsExpectedValueForHiddenAndStarttimeEvent()
    {
        $this->subject->setHidden(true);
        $this->subject->setEndtime(new DateTime());
        self::assertEquals('overlay-hidden', $this->subject->getBackendIconOverlay());
    }
}
