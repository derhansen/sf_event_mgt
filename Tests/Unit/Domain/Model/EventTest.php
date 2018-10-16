<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Category;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Location;
use DERHANSEN\SfEventMgt\Domain\Model\Organisator;
use DERHANSEN\SfEventMgt\Domain\Model\PriceOption;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Model\Speaker;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Event.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Event
     */
    protected $subject = null;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->subject = new Event();
    }

    /**
     * Teardown
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
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
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        $this->assertSame(
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

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'description',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getProgramReturnsInitialValueForString()
    {
        $this->assertSame(
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

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'program',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTeaserReturnsInitialValueForString()
    {
        $this->assertSame(
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

        $this->assertAttributeEquals(
            'This is a teaser',
            'teaser',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStartdateReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
            null,
            $this->subject->getStartdate()
        );
    }

    /**
     * @test
     */
    public function setStartdateForDateTimeSetsStartdate()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setStartdate($dateTimeFixture);

        $this->assertAttributeEquals(
            $dateTimeFixture,
            'startdate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEnddateReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
            null,
            $this->subject->getEnddate()
        );
    }

    /**
     * @test
     */
    public function setEnddateForDateTimeSetsEnddate()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setEnddate($dateTimeFixture);

        $this->assertAttributeEquals(
            $dateTimeFixture,
            'enddate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getParticipantsReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getMaxParticipants()
        );
    }

    /**
     * @test
     */
    public function getTopEventReturnsInitialValueForBoolean()
    {
        $this->assertFalse(
            $this->subject->getTopEvent()
        );
    }

    /**
     * @test
     */
    public function setParticipantsForIntegerSetsParticipants()
    {
        $this->subject->setMaxParticipants(12);

        $this->assertAttributeEquals(
            12,
            'maxParticipants',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPriceReturnsInitialValueForFloat()
    {
        $this->assertSame(
            0.0,
            $this->subject->getPrice()
        );
    }

    /**
     * @test
     */
    public function setPriceForFloatSetsPrice()
    {
        $this->subject->setPrice(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'price',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getCurrencyReturnsInitialValueForString()
    {
        $this->assertSame(
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

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'currency',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValueForCategory()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
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

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneCategory,
            'category',
            $this->subject
        );
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
        $categoryObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($category));
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
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($category));
        $this->inject($this->subject, 'category', $categoryObjectStorageMock);

        $this->subject->removeCategory($category);
    }

    /**
     * @test
     */
    public function getRegistrationReturnsInitialValueForRegistration()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
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

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneRegistration,
            'registration',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addRegistrationToObjectStorageHoldingRegistration()
    {
        $registration = new Registration();
        $registrationObjectStorageMock = $this->getMockBuilder(
            ObjectStorage::class
        )->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($registration));
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
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($registration));
        $this->inject($this->subject, 'registration', $registrationObjectStorageMock);

        $this->subject->removeRegistration($registration);
    }

    /**
     * @test
     */
    public function getRegistrationWaitlistReturnsInitialValueForRegistrationWaitlist()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
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

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneRegistration,
            'registrationWaitlist',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addRegistrationWaitlistToObjectStorageHoldingRegistrationWaitlist()
    {
        $registration = new Registration();
        $registrationObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($registration));
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
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($registration));
        $this->inject($this->subject, 'registrationWaitlist', $registrationObjectStorageMock);

        $this->subject->removeRegistrationWaitlist($registration);
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValueForImage()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
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

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneImage,
            'image',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addImageToObjectStorageHoldingImage()
    {
        $image = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($image));
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
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($image));
        $this->inject($this->subject, 'image', $imageObjectStorageMock);

        $this->subject->removeImage($image);
    }

    /**
     * @test
     */
    public function getFilesReturnsInitialValueForfiles()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
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

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneFile,
            'files',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addFilesToObjectStorageHoldingFiles()
    {
        $files = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($files));
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
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($files));
        $this->inject($this->subject, 'files', $imageObjectStorageMock);

        $this->subject->removeFiles($files);
    }

    /**
     * @test
     */
    public function getAdditionalImageReturnsInitialValueForfiles()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
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

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneFile,
            'additionalImage',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addAdditionalImageToObjectStorageHoldingFiles()
    {
        $files = new FileReference();
        $imageObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($files));
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
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $imageObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($files));
        $this->inject($this->subject, 'additionalImage', $imageObjectStorageMock);

        $this->subject->removeAdditionalImage($files);
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfEventHasTakenPlace()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('yesterday'));
        $this->subject->setStartdate($startdate);
        $this->subject->setEnableRegistration(true);

        $this->assertFalse($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfRegistrationNotEnabled()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);

        $this->subject->setEnableRegistration(false);
        $this->assertFalse($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfRegistrationDeadlineReached()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('yesterday'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setRegistrationDeadline($deadline);
        $this->subject->setEnableRegistration(true);

        $this->assertFalse($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfEventMaxParticipantsReached()
    {
        $registration = new Registration();
        $registration->setFirstname('John');
        $registration->setLastname('Doe');

        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->addRegistration($registration);

        $this->assertFalse($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsNotSet()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(0);
        $this->subject->setEnableRegistration(true);

        $this->assertTrue($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsFalseIfMaxParticipantsSetAndEventFull()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setEnableRegistration(true);

        $registration = new Registration();
        $objectStorageHoldingExactlyOneRegistration = new ObjectStorage();
        $objectStorageHoldingExactlyOneRegistration->attach($registration);
        $this->subject->setRegistration($objectStorageHoldingExactlyOneRegistration);

        $this->assertFalse($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsSetAndWaitlistEnabled()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setEnableRegistration(true);
        $this->subject->setEnableWaitlist(true);

        $registration = new Registration();
        $objectStorageHoldingExactlyOneRegistration = new ObjectStorage();
        $objectStorageHoldingExactlyOneRegistration->attach($registration);
        $this->subject->setRegistration($objectStorageHoldingExactlyOneRegistration);

        $this->assertTrue($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfMaxParticipantsIsZero()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setEnableRegistration(true);

        $this->assertTrue($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfRegistrationDeadlineNotReached()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setRegistrationDeadline($deadline);
        $this->subject->setEnableRegistration(true);

        $this->assertTrue($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getRegistrationPossibleReturnsTrueIfRegistrationIsLogicallyPossible()
    {
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $this->subject->setStartdate($startdate);
        $this->subject->setMaxParticipants(1);
        $this->subject->setEnableRegistration(true);

        $this->assertTrue($this->subject->getRegistrationPossible());
    }

    /**
     * @test
     */
    public function getLocationReturnsInitialValueForLocation()
    {
        $this->assertEquals(
            null,
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

        $this->assertAttributeEquals(
            $location,
            'location',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEnableRegistrationReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            false,
            $this->subject->getEnableRegistration()
        );
    }

    /**
     * @test
     */
    public function setEnableRegistrationForBooleanSetsEnableRegistration()
    {
        $this->subject->setEnableRegistration(true);

        $this->assertAttributeEquals(
            true,
            'enableRegistration',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEnableWaitlistReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            false,
            $this->subject->getEnableWaitlist()
        );
    }

    /**
     * @test
     */
    public function setEnableWaitlistForBooleanSetsEnableWaitlist()
    {
        $this->subject->setEnableWaitlist(true);

        $this->assertAttributeEquals(
            true,
            'enableWaitlist',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLinkReturnsInitialValueForLink()
    {
        $this->assertSame(
            null,
            $this->subject->getLink()
        );
    }

    /**
     * @test
     */
    public function setLinkForStringSetsLink()
    {
        $this->subject->setLink('www.domain.tld');

        $this->assertAttributeEquals(
            'www.domain.tld',
            'link',
            $this->subject
        );
    }

    /**
     * Data provider for settings
     *
     * @return array
     */
    public function typolinkDataprovider()
    {
        return [
            'emptyLink' => [
                '',
                1,
                ''
            ],
            'singleDomainLink' => [
                'www.domain.tld',
                0,
                'www.domain.tld'
            ],
            'singlePageLink' => [
                '1',
                0,
                '1'
            ],
            'EmptyTarget' => [
                'www.domain.tld',
                1,
                ''
            ],
            'TargetNotSet' => [
                'www.domain.tld - Title',
                1,
                ''
            ],
            'DomainTarget' => [
                'www.domain.tld _blank',
                1,
                '_blank'
            ],
            'TitleWithoutQuotationMarks' => [
                'www.domain.tld - - Title',
                3,
                'Title'
            ],
            'TitleWithQuotationMarks' => [
                'www.domain.tld - - "Title of link"',
                3,
                'Title of link'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider typolinkDataprovider
     * @param mixed $link
     * @param mixed $part
     * @param mixed $expected
     */
    public function getTypolinkPartTest($link, $part, $expected)
    {
        $this->subject->setLink($link);

        $this->assertEquals(
            $expected,
            $this->subject->getLinkPart($part)
        );
    }

    /**
     * @test
     */
    public function getLinkUrlTest()
    {
        $this->subject->setLink('www.domain.tld _blank');

        $this->assertEquals(
            'www.domain.tld',
            $this->subject->getLinkUrl()
        );
    }

    /**
     * @test
     */
    public function getLinkTargetTest()
    {
        $this->subject->setLink('www.domain.tld _blank');

        $this->assertEquals(
            '_blank',
            $this->subject->getLinkTarget()
        );
    }

    /**
     * @test
     */
    public function getLinkTitleTest()
    {
        $this->subject->setLink('www.domain.tld _blank - "The title"');

        $this->assertEquals(
            'The title',
            $this->subject->getLinkTitle()
        );
    }

    /**
     * @test
     */
    public function getFreePlacesWithoutRegistrationsTest()
    {
        $this->subject->setMaxParticipants(10);

        $this->assertEquals(
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
        $registrationObjectStorageMock->expects($this->once())->method('count')->will($this->returnValue(5));
        $this->inject($this->subject, 'registration', $registrationObjectStorageMock);

        $this->assertEquals(
            5,
            $this->subject->getFreePlaces()
        );
    }

    /**
     * @test
     */
    public function getRegistrationDeadlineReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
            null,
            $this->subject->getRegistrationDeadline()
        );
    }

    /**
     * @test
     */
    public function setRegistrationDeadlineForDateTimeSetsStartdate()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setRegistrationDeadline($dateTimeFixture);

        $this->assertAttributeEquals(
            $dateTimeFixture,
            'registrationDeadline',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function setTopEventForBooleanSetsTopEvent()
    {
        $this->subject->setTopEvent(true);

        $this->assertAttributeEquals(
            true,
            'topEvent',
            $this->subject
        );
    }

    /**
     * @test
     * @return void
     */
    public function maxRegistrationsPerUserReturnsInitialValue()
    {
        $this->assertEquals(1, $this->subject->getMaxRegistrationsPerUser());
    }

    /**
     * @test
     * @return void
     */
    public function maxRegistrationsPerUserSetsMaxRegistrationsPerUser()
    {
        $this->subject->setMaxRegistrationsPerUser(2);
        $this->assertEquals(2, $this->subject->getMaxRegistrationsPerUser());
    }

    /**
     * Test if initial value for organisator is returned
     *
     * @test
     * @return void
     */
    public function getOrganisatorReturnsInitialValueForOrganisator()
    {
        $this->assertNull($this->subject->getOrganisator());
    }

    /**
     * Test if organisator can be set
     *
     * @test
     * @return void
     */
    public function setPhoneForStringSetsPhone()
    {
        $organisator = new Organisator();
        $this->subject->setOrganisator($organisator);

        $this->assertAttributeEquals(
            $organisator,
            'organisator',
            $this->subject
        );
    }

    /**
     * Test if initial value for notifyAdmin (TRUE) is returned
     *
     * @test
     * @return void
     */
    public function getNotityAdminReturnsInitialValue()
    {
        $this->assertTrue($this->subject->getNotifyAdmin());
    }

    /**
     * Test if notifyAdmin can be set
     *
     * @test
     * @return void
     */
    public function setNotifyAdminSetsValueForNotifyAdmin()
    {
        $this->subject->setNotifyAdmin(false);
        $this->assertFalse($this->subject->getNotifyAdmin());
    }

    /**
     * Test if initial value for notifyOrganisator (FALSE) is returned
     *
     * @test
     * @return void
     */
    public function getNotityOrganisatorReturnsInitialValue()
    {
        $this->assertFalse($this->subject->getNotifyOrganisator());
    }

    /**
     * Test if notifyOrganisator can be set
     *
     * @test
     * @return void
     */
    public function setNotifyOrganisatorSetsValueForNotifyOrganisator()
    {
        $this->subject->setNotifyOrganisator(true);
        $this->assertTrue($this->subject->getNotifyOrganisator());
    }

    /**
     * @test
     * @return void
     */
    public function uniqueEmailCheckReturnsInitialValue()
    {
        $this->assertFalse($this->subject->getUniqueEmailCheck());
    }

    /**
     * @test
     * @return void
     */
    public function uniqueEmailCheckSetsValueForBoolen()
    {
        $this->subject->setUniqueEmailCheck(true);
        $this->assertTrue($this->subject->getUniqueEmailCheck());
    }

    /**
     * @test
     * @return void
     */
    public function enableCancelReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->getEnableCancel());
    }

    /**
     * @test
     * @return void
     */
    public function setEnableCancelSetsEnableCancelForBoolean()
    {
        $this->subject->setEnableCancel(true);
        $this->assertTrue($this->subject->getEnableCancel());
    }

    /**
     * @test
     * @return void
     */
    public function getCancelDeallineReturnsDefaultValue()
    {
        $this->assertEquals(0, $this->subject->getCancelDeadline());
    }

    /**
     * @test
     * @return void
     */
    public function setCancelDeallineSetsCancelDeadlineForDate()
    {
        $date = new \DateTime();
        $this->subject->setCancelDeadline($date);
        $this->assertEquals($date, $this->subject->getCancelDeadline());
    }

    /**
     * @test
     * @return void
     */
    public function getEnablePaymentReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->getEnablePayment());
    }

    /**
     * @test
     * @return void
     */
    public function setEnablePaymentSetsEnablePaymentForBoolean()
    {
        $this->subject->setEnablePayment(true);
        $this->assertTrue($this->subject->getEnablePayment());
    }

    /**
     * @test
     * @return void
     */
    public function getRestrictPaymentMethodsReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->getRestrictPaymentMethods());
    }

    /**
     * @test
     * @return void
     */
    public function setRestrictPaymentMethodsSetsRestrictPaymentMethodsForBoolean()
    {
        $this->subject->setRestrictPaymentMethods(true);
        $this->assertTrue($this->subject->getRestrictPaymentMethods());
    }

    /**
     * @test
     * @return void
     */
    public function getSelectedPaymentMethodsReturnsDefaultValue()
    {
        $this->assertEmpty($this->subject->getSelectedPaymentMethods());
    }

    /**
     * @test
     * @return void
     */
    public function setSelectedPaymentMethodsSetsSelectedPaymentMethodforString()
    {
        $this->subject->setSelectedPaymentMethods('invoice,transfer');
        $this->assertEquals('invoice,transfer', $this->subject->getSelectedPaymentMethods());
    }

    /**
     * @test
     * @return void
     */
    public function getPriceOptionsReturnsInitialValueforObjectStorage()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getPriceOptions()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setPriceOptionSetsPriceOptionForPriceOption()
    {
        $priceOption = new PriceOption();
        $this->subject->setPriceOptions($priceOption);
        $this->assertEquals($priceOption, $this->subject->getPriceOptions());
    }

    /**
     * @test
     * @return void
     */
    public function addPriceOptionAddsPriceOptionForPriceOption()
    {
        $priceOption = new PriceOption();
        $priceOptionObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $priceOptionObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($priceOption));
        $this->inject($this->subject, 'priceOptions', $priceOptionObjectStorageMock);

        $this->subject->addPriceOptions($priceOption);
    }

    /**
     * @test
     * @return void
     */
    public function removePriceOptionRemovesPriceOptionForPriceOption()
    {
        $priceOption = new PriceOption();
        $priceOptionObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $priceOptionObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($priceOption));
        $this->inject($this->subject, 'priceOptions', $priceOptionObjectStorageMock);

        $this->subject->removePriceOptions($priceOption);
    }

    /**
     * @test
     * @return void
     */
    public function getActivePriceOptionsReturnsOnlyActivePriceOptions()
    {
        $dateYesterday = new \DateTime('yesterday');
        $dateToday = new \DateTime('today');
        $dateTomorrow = new \DateTime('tomorrow');

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

        $this->assertEquals($expected, $this->subject->getActivePriceOptions());
    }

    /**
     * @test
     * @return void
     */
    public function getCurrentPriceReturnsPriceIfNoPriceOptionsSet()
    {
        $this->subject->setPrice(12.99);
        $this->assertEquals(12.99, $this->subject->getCurrentPrice());
    }

    /**
     * @test
     * @return void
     */
    public function getCurrentPriceReturnsPriceOptionIfSet()
    {
        $this->subject->setPrice(19.99);

        $priceOption1 = new PriceOption();
        $priceOption1->setPrice(14.99);
        $priceOption1->setValidUntil(new \DateTime('today'));

        $priceOption2 = new PriceOption();
        $priceOption2->setPrice(16.99);
        $priceOption2->setValidUntil(new \DateTime('tomorrow'));

        $this->subject->addPriceOptions($priceOption1);
        $this->subject->addPriceOptions($priceOption2);

        $this->assertEquals(14.99, $this->subject->getCurrentPrice());
    }

    /**
     * @test
     * @return void
     */
    public function getRelatedReturnsInitialValueForObjectStorage()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getRelated()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setRelatedSetsRelatedForRelated()
    {
        $event = new Event();
        $this->subject->setRelated($event);
        $this->assertEquals($event, $this->subject->getRelated());
    }

    /**
     * @test
     * @return void
     */
    public function addRelatedAddsRelatedForRelated()
    {
        $event = new Event();
        $relatedObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $relatedObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($event));
        $this->inject($this->subject, 'related', $relatedObjectStorageMock);

        $this->subject->addRelated($event);
    }

    /**
     * @test
     * @return void
     */
    public function removeRelatedRemovesRelatedForRelated()
    {
        $event = new Event();
        $relatedObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $relatedObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($event));
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
                new \DateTime('today'),
                false
            ],
            'cancellationEnabledButDeadlineReached' => [
                true,
                new \DateTime('yesterday'),
                false
            ],
            'cancellationEnabledDeadlineNotReached' => [
                true,
                new \DateTime('tomorrow'),
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
     */
    public function getCancellationPossibleReturnsExpectedValues($enabled, $deadline, $expected)
    {
        $this->subject->setEnableCancel($enabled);
        $this->subject->setCancelDeadline($deadline);
        $this->assertEquals($expected, $this->subject->getCancellationPossible());
    }

    /**
     * @test
     */
    public function getCancellationPossibleReturnsTrueIfNoDeadlineSet()
    {
        $this->subject->setEnableCancel(true);
        $this->assertEquals(true, $this->subject->getCancellationPossible());
    }

    /**
     * @test
     * @return void
     */
    public function enableAutoconfirmReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->getEnableAutoconfirm());
    }

    /**
     * @test
     * @return void
     */
    public function setEnableAutoconfirmSetsAutoconfirmForBoolean()
    {
        $this->subject->setEnableAutoconfirm(true);
        $this->assertTrue($this->subject->getEnableAutoconfirm());
    }

    /**
     * @test
     * @return void
     */
    public function getSpeakerReturnsInitialValueforObjectStorage()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getSpeaker()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setSpeakerSetsSpeaker()
    {
        $speaker = new Speaker();
        $objectStorageWithSpeaker = new ObjectStorage();
        $objectStorageWithSpeaker->attach($speaker);
        $this->subject->setSpeaker($objectStorageWithSpeaker);
        $this->assertEquals($objectStorageWithSpeaker, $this->subject->getSpeaker());
    }

    /**
     * @test
     * @return void
     */
    public function addSpeakerAddsSpeaker()
    {
        $speaker = new Speaker();
        $speakerObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($speaker));
        $this->inject($this->subject, 'speaker', $speakerObjectStorageMock);

        $this->subject->addSpeaker($speaker);
    }

    /**
     * @test
     * @return void
     */
    public function removeSpeakerRemovesSpeaker()
    {
        $speaker = new Speaker();
        $speakerObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($speaker));
        $this->inject($this->subject, 'speaker', $speakerObjectStorageMock);

        $this->subject->removeSpeaker($speaker);
    }

    /**
     * @test
     * @return void
     */
    public function getRegistrationFieldsReturnsInitialValueforObjectStorage()
    {
        $newObjectStorage = new ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getRegistrationFields()
        );
    }

    /**
     * @test
     * @return void
     */
    public function setRegistrationFieldsSetsRegistrationFields()
    {
        $registrationField = new Field();
        $objectStorageWithRegistrationField = new ObjectStorage();
        $objectStorageWithRegistrationField->attach($registrationField);
        $this->subject->setRegistrationFields($objectStorageWithRegistrationField);
        $this->assertEquals($objectStorageWithRegistrationField, $this->subject->getRegistrationFields());
    }

    /**
     * @test
     * @return void
     */
    public function addRegistrationFieldsAddsRegistrationField()
    {
        $registrationField = new Field();
        $registrationFieldStorage = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationFieldStorage->expects($this->once())->method('attach')->with($this->equalTo($registrationField));
        $this->inject($this->subject, 'registrationFields', $registrationFieldStorage);

        $this->subject->addRegistrationFields($registrationField);
    }

    /**
     * @test
     * @return void
     */
    public function removeRegistrationFieldsRemovesRegistrationField()
    {
        $registrationField = new Field();
        $registrationFieldStorage = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationFieldStorage->expects($this->once())->method('detach')->with($this->equalTo($registrationField));
        $this->inject($this->subject, 'registrationFields', $registrationFieldStorage);

        $this->subject->removeRegistrationFields($registrationField);
    }

    /**
     * @test
     */
    public function getRegistrationFieldsUidsReturnsEmptyArrayIfNotRegistrationFields()
    {
        $this->assertEquals([], $this->subject->getRegistrationFieldsUids());
    }

    /**
     * @test
     */
    public function getRegistrationFieldsUidsReturnsExpectedArrayForEventWithRegistrationFields()
    {
        $mockRegField = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegField->expects($this->any())->method('getUid')->will($this->returnValue(1));
        $this->subject->addRegistrationFields($mockRegField);

        $this->assertEquals([0 => 1], $this->subject->getRegistrationFieldsUids());
    }

    /**
     * @test
     */
    public function getRegistrationFieldUidsWithTitleReturnsEmptyArrayIfNotRegistrationFields()
    {
        $this->assertEquals([], $this->subject->getRegistrationFieldUidsWithTitle());
    }

    /**
     * @test
     */
    public function getRegistrationFieldUidsWithTitleReturnsExpectedArrayForEventWithRegistrationFields()
    {
        $mockRegField1 = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegField1->expects($this->any())->method('getUid')->will($this->returnValue(1));
        $mockRegField1->expects($this->any())->method('getTitle')->will($this->returnValue('A Title'));
        $mockRegField2 = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegField2->expects($this->any())->method('getUid')->will($this->returnValue(2));
        $mockRegField2->expects($this->any())->method('getTitle')->will($this->returnValue('Another Title'));
        $this->subject->addRegistrationFields($mockRegField1);
        $this->subject->addRegistrationFields($mockRegField2);

        $expected = [
            1 => 'A Title',
            2 => 'Another Title'
        ];
        $this->assertEquals($expected, $this->subject->getRegistrationFieldUidsWithTitle());
    }
}
