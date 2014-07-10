<?php
namespace SKYFILLERS\SfEventMgt\Tests\Unit\Service;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Thies Kracht <t.kracht@skyfillers.com>, Skyfillers GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use RuntimeException;
use \SKYFILLERS\SfEventMgt\Service\ExportService;

/**
 * Class ExportServiceTest
 */
class ExportServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var ExportService
	 */
	protected $subject = NULL;

	/** @var \SKYFILLERS\SfEventMgt\Domain\Repository\RegistrationRepository */
	protected $registrationRepository;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new ExportService();
	}

	/**
	 * Teardown
	 *
	 * @return void
	 */
	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * Data Provider for unit tests
	 *
	 * @return array
	 */
	public function wrongFieldValuesInTypoScriptDataProvider() {
		return array(
			'wrongFieldValuesInTypoScript' => array(
				1,
				'uid, firstname, wrongfield'
			),
		);
	}

	/**
	 * Data Provider for unit tests
	 *
	 * @return array
	 */
	public function fieldValuesInTypoScriptDataProvider() {
		return array(
			'fieldValuesWithWhitespacesInTypoScript' => array(
				1,
				array(
					'fields' => 'uid, firstname, lastname',
					'fieldDelimiter' => ',',
					'fieldQuoteCharacter' => '"'
				),
				'"uid","firstname","lastname"' . chr(10) . '"1","Max","Mustermann"' . chr(10)
			),
			'fieldValuesWithoutWhitespacesInTypoScript' => array(
				1,
				array(
					'fields' => 'uid, firstname, lastname',
					'fieldDelimiter' => ',',
					'fieldQuoteCharacter' => '"'
				),
				'"uid","firstname","lastname"' . chr(10) . '"1","Max","Mustermann"' . chr(10)
			),
			'fieldValuesWithDifferentDelimiter' => array(
				1,
				array(
					'fields' => 'uid, firstname, lastname',
					'fieldDelimiter' => ';',
					'fieldQuoteCharacter' => '"'
				),
				'"uid";"firstname";"lastname"' . chr(10) . '"1";"Max";"Mustermann"' . chr(10)
			),
			'fieldValuesWithDifferentQuoteCharacter' => array(
				1,
				array(
					'fields' => 'uid, firstname, lastname',
					'fieldDelimiter' => ',',
					'fieldQuoteCharacter' => '\''
				),
				'\'uid\',\'firstname\',\'lastname\'' . chr(10) . '\'1\',\'Max\',\'Mustermann\'' . chr(10)
			),
		);
	}

	/**
	 * @test
	 * @expectedException RuntimeException
	 */
	public function exportServiceThrowsExceptionWhenFieldIsNotValidForRegistrationModel() {
		$mockRegistration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration',
			array('_hasProperty'), array(), '', FALSE);
		$mockRegistration->expects($this->at(0))->method('_hasProperty')->with($this->equalTo('uid'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(1))->method('_hasProperty')->with($this->equalTo('firstname'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(2))->method('_hasProperty')->with($this->equalTo('wrongfield'))->will($this->returnValue(FALSE));

		$allRegistrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$allRegistrations->attach($mockRegistration);

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\Registration',
			array('findByEvent'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByEvent')->will($this->returnValue($allRegistrations));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->exportToCsvFile(1, array(
			'fields' => 'uid, firstname, wrongfield',
			'fieldDelimiter' => ',',
			'fieldQuoteCharacter' => '"'
			)
		);
	}

	/**
	 * @test
	 * @dataProvider fieldValuesInTypoScriptDataProvider
	 */
	public function exportServiceWorksWithDifferentFormattedTypoScriptValues($uid, $fields, $expected) {
		$mockRegistration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration',
			array('_hasProperty', '_getCleanProperty'), array(), '', FALSE);
		$mockRegistration->expects($this->at(0))->method('_hasProperty')->with($this->equalTo('uid'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(2))->method('_hasProperty')->with($this->equalTo('firstname'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(4))->method('_hasProperty')->with($this->equalTo('lastname'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(1))->method('_getCleanProperty')->with($this->equalTo('uid'))->will($this->returnValue(1));
		$mockRegistration->expects($this->at(3))->method('_getCleanProperty')->with($this->equalTo('firstname'))->will($this->returnValue('Max'));
		$mockRegistration->expects($this->at(5))->method('_getCleanProperty')->with($this->equalTo('lastname'))->will($this->returnValue('Mustermann'));

		$allRegistrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$allRegistrations->attach($mockRegistration);

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\Registration',
			array('findByEvent'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByEvent')->will($this->returnValue($allRegistrations));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$returnValue = $this->subject->exportToCsvFile($uid, $fields);
		$this->assertSame($expected, $returnValue);
	}
}
 