<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Torben Hansen <derhansen@gmail.com>
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

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\ICalendarService.
 */
class ICalendarServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DERHANSEN\SfEventMgt\Service\ICalendarService
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new \DERHANSEN\SfEventMgt\Service\ICalendarService();
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
	 * @test
	 * @return void
	 */
	public function downloadiCalendarFileDumpsCsvFile() {
		$mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event',
			array(), array(), '', FALSE);

		$mockICalendarService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ICalendarService',
			array('getiCalendarContent'), array(), '', FALSE);
		$mockICalendarService->expects($this->once())->method('getiCalendarContent')->with($mockEvent)->will(
			$this->returnValue('iCalendar Data'));

		$mockFile = $this->getMock('TYPO3\\CMS\\Core\\Resource\\File', array(), array(), '', FALSE);
		$mockFile->expects($this->once())->method('setContents')->with('iCalendar Data');

		$mockStorageRepository = $this->getMock('TYPO3\CMS\Core\Resource\StorageRepository',
			array('getFolder', 'createFile', 'dumpFileContents'), array(), '', FALSE);
		$mockStorageRepository->expects($this->at(0))->method('getFolder')->with('_temp_');
		$mockStorageRepository->expects($this->at(1))->method('createFile')->will($this->returnValue($mockFile));
		$mockStorageRepository->expects($this->at(2))->method('dumpFileContents');

		$mockResourceFactory = $this->getMock('TYPO3\\CMS\\Core\\Resource\\ResourceFactory',
			array('getDefaultStorage'), array(), '', FALSE);
		$mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
			$this->returnValue($mockStorageRepository));
		$this->inject($mockICalendarService, 'resourceFactory', $mockResourceFactory);

		$mockICalendarService->downloadiCalendarFile($mockEvent);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getiCalendarContentAssignsVariablesToView() {
		$_SERVER['HTTP_HOST'] = 'myhostname.tld';

		$mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event',
			array(), array(), '', FALSE);

		// Inject configuration and configurationManager
		$configuration = array(
			'plugin.' => array(
				'tx_sfeventmgt.' => array(
					'view.' => array(
						'templateRootPath' => 'EXT:sf_event_mgt/Resources/Private/Templates/',
						'layoutRootPath' => 'EXT:sf_event_mgt/Resources/Private/Layouts/'
					)
				)
			)
		);

		$configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
			array('getConfiguration'), array(), '', FALSE);
		$configurationManager->expects($this->once())->method('getConfiguration')->will(
			$this->returnValue($configuration));
		$this->inject($this->subject, 'configurationManager', $configurationManager);

		$iCalendarView = $this->getMock('TYPO3\\CMS\\Fluid\\View\\StandaloneView', array(), array(), '', FALSE);
		$iCalendarView->expects($this->once())->method('setFormat')->with('txt');
		$iCalendarView->expects($this->once())->method('assignMultiple')->with(array(
			'event' => $mockEvent,
			'typo3Host' => 'myhostname.tld'
		));

		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array(), array(), '', FALSE);
		$objectManager->expects($this->once())->method('get')->will($this->returnValue($iCalendarView));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$this->subject->getiCalendarContent($mockEvent);
	}
}