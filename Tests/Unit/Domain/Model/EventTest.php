<?php

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DERHANSEN\SfEventMgt\Domain\Model\Registration;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Event.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Event
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Event();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() {
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
	public function getDescriptionReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getDescription()
		);
	}

	/**
	 * @test
	 */
	public function setDescriptionForStringSetsDescription() {
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
	public function getStartdateReturnsInitialValueForDateTime() {
		$this->assertEquals(
			NULL,
			$this->subject->getStartdate()
		);
	}

	/**
	 * @test
	 */
	public function setStartdateForDateTimeSetsStartdate() {
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
	public function getEnddateReturnsInitialValueForDateTime() {
		$this->assertEquals(
			NULL,
			$this->subject->getEnddate()
		);
	}

	/**
	 * @test
	 */
	public function setEnddateForDateTimeSetsEnddate() {
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
	public function getParticipantsReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getMaxParticipants()
		);
	}

	/**
	 * @test
	 */
	public function setParticipantsForIntegerSetsParticipants() {
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
	public function getPriceReturnsInitialValueForFloat() {
		$this->assertSame(
			0.0,
			$this->subject->getPrice()
		);
	}

	/**
	 * @test
	 */
	public function setPriceForFloatSetsPrice() {
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
	public function getCurrencyReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getCurrency()
		);
	}

	/**
	 * @test
	 */
	public function setCurrencyForStringSetsCurrency() {
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
	public function getCategoryReturnsInitialValueForCategory() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getCategory()
		);
	}

	/**
	 * @test
	 */
	public function setCategoryForObjectStorageContainingCategorySetsCategory() {
		$category = new \DERHANSEN\SfEventMgt\Domain\Model\Category();
		$objectStorageHoldingExactlyOneCategory = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
	public function addCategoryToObjectStorageHoldingCategory() {
		$category = new \DERHANSEN\SfEventMgt\Domain\Model\Category();
		$categoryObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$categoryObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($category));
		$this->inject($this->subject, 'category', $categoryObjectStorageMock);

		$this->subject->addCategory($category);
	}

	/**
	 * @test
	 */
	public function removeCategoryFromObjectStorageHoldingCategory() {
		$category = new \DERHANSEN\SfEventMgt\Domain\Model\Category();
		$categoryObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$categoryObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($category));
		$this->inject($this->subject, 'category', $categoryObjectStorageMock);

		$this->subject->removeCategory($category);

	}

	/**
	 * @test
	 */
	public function getRegistrationReturnsInitialValueForRegistration() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getRegistration()
		);
	}

	/**
	 * @test
	 */
	public function setRegistrationForObjectStorageContainingRegistrationSetsRegistration() {
		$registration = new Registration();
		$objectStorageHoldingExactlyOneRegistration = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
	public function addRegistrationToObjectStorageHoldingRegistration() {
		$registration = new Registration();
		$registrationObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$registrationObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($registration));
		$this->inject($this->subject, 'registration', $registrationObjectStorageMock);

		$this->subject->addRegistration($registration);
	}

	/**
	 * @test
	 */
	public function removeRegistrationFromObjectStorageHoldingRegistration() {
		$registration = new Registration();
		$registrationObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$registrationObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($registration));
		$this->inject($this->subject, 'registration', $registrationObjectStorageMock);

		$this->subject->removeRegistration($registration);
	}

	/**
	 * @test
	 */
	public function getImageReturnsInitialValueForImage() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getImage()
		);
	}

	/**
	 * @test
	 */
	public function setImageForObjectStorageContainingImageSetsImage() {
		$image = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
		$objectStorageHoldingExactlyOneImage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
	public function addImageToObjectStorageHoldingImage() {
		$image = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
		$imageObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$imageObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($image));
		$this->inject($this->subject, 'image', $imageObjectStorageMock);

		$this->subject->addImage($image);
	}

	/**
	 * @test
	 */
	public function removeImageFromObjectStorageHoldingImage() {
		$image = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
		$imageObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$imageObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($image));
		$this->inject($this->subject, 'image', $imageObjectStorageMock);

		$this->subject->removeImage($image);
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsFalseIfEventHasTakenPlace() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('yesterday'));
		$this->subject->setStartdate($startdate);
		$this->subject->setEnableRegistration(TRUE);

		$this->assertFalse($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsFalseIfRegistrationNotEnabled() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$this->subject->setStartdate($startdate);

		$this->subject->setEnableRegistration(FALSE);
		$this->assertFalse($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsFalseIfRegistrationDeadlineReached() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$deadline = new \DateTime();
		$deadline->add(\DateInterval::createFromDateString('yesterday'));
		$this->subject->setStartdate($startdate);
		$this->subject->setMaxParticipants(1);
		$this->subject->setRegistrationDeadline($deadline);
		$this->subject->setEnableRegistration(TRUE);

		$this->assertFalse($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsFalseIfEventMaxParticipantsReached() {
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
	public function getRegistrationPossibleReturnsTrueIfMaxParticipantsNotSet() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$this->subject->setStartdate($startdate);
		$this->subject->setMaxParticipants(0);
		$this->subject->setEnableRegistration(TRUE);

		$this->assertTrue($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsTrueIfMaxParticipantsIsZero() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$this->subject->setStartdate($startdate);
		$this->subject->setEnableRegistration(TRUE);

		$this->assertTrue($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsTrueIfRegistrationDeadlineNotReached() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$deadline = new \DateTime();
		$deadline->add(\DateInterval::createFromDateString('tomorrow'));
		$this->subject->setStartdate($startdate);
		$this->subject->setMaxParticipants(1);
		$this->subject->setRegistrationDeadline($deadline);
		$this->subject->setEnableRegistration(TRUE);

		$this->assertTrue($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsTrueIfRegistrationIsLogicallyPossible() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$this->subject->setStartdate($startdate);
		$this->subject->setMaxParticipants(1);
		$this->subject->setEnableRegistration(TRUE);

		$this->assertTrue($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getLocationReturnsInitialValueForLocation() {
		$this->assertEquals(
			NULL,
			$this->subject->getLocation()
		);
	}

	/**
	 * @test
	 */
	public function setLocationSetsLocation() {
		$location = new \DERHANSEN\SfEventMgt\Domain\Model\Location();
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
	public function getEnableRegistrationReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getEnableRegistration()
		);
	}

	/**
	 * @test
	 */
	public function setEnableRegistrationForBooleanSetsEnableRegistration() {
		$this->subject->setEnableRegistration(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'enableRegistration',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLinkReturnsInitialValueForLink() {
		$this->assertSame(
			NULL,
			$this->subject->getLink()
		);
	}

	/**
	 * @test
	 */
	public function setLinkForStringSetsLink() {
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
	public function typolinkDataprovider() {
		return array(
			'emptyLink' => array(
				'',
				1,
				''
			),
			'singleDomainLink' => array(
				'www.domain.tld',
				0,
				'www.domain.tld'
			),
			'singlePageLink' => array(
				'1',
				0,
				'1'
			),
			'EmptyTarget' => array(
				'www.domain.tld',
				1,
				''
			),
			'TargetNotSet' => array(
				'www.domain.tld - Title',
				1,
				''
			),
			'DomainTarget' => array(
				'www.domain.tld _blank',
				1,
				'_blank'
			),
			'TitleWithoutQuotationMarks' => array(
				'www.domain.tld - - Title',
				3,
				'Title'
			),
			'TitleWithQuotationMarks' => array(
				'www.domain.tld - - "Title of link"',
				3,
				'Title of link'
			),
		);
	}

	/**
	 * @test
	 * @dataProvider typolinkDataprovider
	 */
	public function getTypolinkPartTest($link, $part, $expected) {
		$this->subject->setLink($link);

		$this->assertEquals(
			$expected,
			$this->subject->getLinkPart($part)
		);
	}

	/**
	 * @test
	 */
	public function getLinkUrlTest() {
		$this->subject->setLink('www.domain.tld _blank');

		$this->assertEquals(
			'www.domain.tld',
			$this->subject->getLinkUrl()
		);
	}

	/**
	 * @test
	 */
	public function getLinkTargetTest() {
		$this->subject->setLink('www.domain.tld _blank');

		$this->assertEquals(
			'_blank',
			$this->subject->getLinkTarget()
		);
	}

	/**
	 * @test
	 */
	public function getLinkTitleTest() {
		$this->subject->setLink('www.domain.tld _blank - "The title"');

		$this->assertEquals(
			'The title',
			$this->subject->getLinkTitle()
		);
	}

	/**
	 * @test
	 */
	public function getFreePlacesWithoutRegistrationsTest() {
		$this->subject->setMaxParticipants(10);

		$this->assertEquals(
			10,
			$this->subject->getFreePlaces()
		);
	}

	/**
	 * @test
	 */
	public function getFreePlacesWithRegistrationsTest() {
		$this->subject->setMaxParticipants(10);

		$registrationObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('count'), array(), '', FALSE);
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
	public function getRegistrationDeadlineReturnsInitialValueForDateTime() {
		$this->assertEquals(
			NULL,
			$this->subject->getRegistrationDeadline()
		);
	}

	/**
	 * @test
	 */
	public function setRegistrationDeadlineForDateTimeSetsStartdate() {
		$dateTimeFixture = new \DateTime();
		$this->subject->setRegistrationDeadline($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'registrationDeadline',
			$this->subject
		);
	}

}
