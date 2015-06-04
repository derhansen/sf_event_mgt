<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use RuntimeException;
use \DERHANSEN\SfEventMgt\Service\ExportService;

/**
 * Class ExportServiceTest
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ExportServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var ExportService
	 */
	protected $subject = NULL;

	/** @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository */
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
	 * @return void
	 */
	public function exportServiceThrowsExceptionWhenFieldIsNotValidForRegistrationModel() {
		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array('_hasProperty'), array(), '', FALSE);
		$mockRegistration->expects($this->at(0))->method('_hasProperty')->with(
			$this->equalTo('uid'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(1))->method('_hasProperty')->with(
			$this->equalTo('firstname'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(2))->method('_hasProperty')->with(
			$this->equalTo('wrongfield'))->will($this->returnValue(FALSE));

		$allRegistrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$allRegistrations->attach($mockRegistration);

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\Registration',
			array('findByEvent'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByEvent')->will($this->returnValue($allRegistrations));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->exportRegistrationsCsv(1, array(
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
		$mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
			array('_hasProperty', '_getCleanProperty'), array(), '', FALSE);
		$mockRegistration->expects($this->at(0))->method('_hasProperty')->with(
			$this->equalTo('uid'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(2))->method('_hasProperty')->with(
			$this->equalTo('firstname'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(4))->method('_hasProperty')->with(
			$this->equalTo('lastname'))->will($this->returnValue(TRUE));
		$mockRegistration->expects($this->at(1))->method('_getCleanProperty')->with(
			$this->equalTo('uid'))->will($this->returnValue(1));
		$mockRegistration->expects($this->at(3))->method('_getCleanProperty')->with(
			$this->equalTo('firstname'))->will($this->returnValue('Max'));
		$mockRegistration->expects($this->at(5))->method('_getCleanProperty')->with(
			$this->equalTo('lastname'))->will($this->returnValue('Mustermann'));

		$allRegistrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$allRegistrations->attach($mockRegistration);

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\Registration',
			array('findByEvent'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByEvent')->will(
			$this->returnValue($allRegistrations));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$returnValue = $this->subject->exportRegistrationsCsv($uid, $fields);
		$this->assertSame($expected, $returnValue);
	}

	/**
	 * @test
	 * @expectedException RuntimeException
	 * @return void
	 */
	public function downloadRegistrationsCsvThrowsExceptionIfDefaultStorageNotFound() {
		$mockResourceFactory = $this->getMock('TYPO3\\CMS\\Core\\Resource\\ResourceFactory',
			array(), array(), '', FALSE);
		$mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will($this->returnValue(NULL));
		$this->inject($this->subject, 'resourceFactory', $mockResourceFactory);
		$this->subject->downloadRegistrationsCsv(1, array('settings'));
	}

	/**
	 * @test
	 * @return void
	 */
	public function downloadRegistrationsCsvDumpsRegistrationsContent() {
		$mockExportService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ExportService',
			array('exportRegistrationsCsv'), array(), '', FALSE);
		$mockExportService->expects($this->once())->method('exportRegistrationsCsv')->will(
			$this->returnValue('CSV-DATA'));

		$mockFile = $this->getMock('TYPO3\\CMS\\Core\\Resource\\File', array(), array(), '', FALSE);
		$mockFile->expects($this->once())->method('setContents')->with('CSV-DATA');

		$mockStorageRepository = $this->getMock('TYPO3\CMS\Core\Resource\StorageRepository',
			array('getFolder', 'createFile', 'dumpFileContents'), array(), '', FALSE);
		$mockStorageRepository->expects($this->at(0))->method('getFolder')->with('_temp_');
		$mockStorageRepository->expects($this->at(1))->method('createFile')->will($this->returnValue($mockFile));
		$mockStorageRepository->expects($this->at(2))->method('dumpFileContents');

		$mockResourceFactory = $this->getMock('TYPO3\\CMS\\Core\\Resource\\ResourceFactory',
			array('getDefaultStorage'), array(), '', FALSE);
		$mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
			$this->returnValue($mockStorageRepository));
		$this->inject($mockExportService, 'resourceFactory', $mockResourceFactory);

		$mockExportService->downloadRegistrationsCsv(1, array('settings'));
	}
}