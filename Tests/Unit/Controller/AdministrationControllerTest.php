<?php
namespace SKYFILLERS\SfEventMgt\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
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
 * Test case for class SKYFILLERS\SfEventMgt\Controller\AdministrationController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AdministrationControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \SKYFILLERS\SfEventMgt\Controller\AdministrationController | \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = $this->getAccessibleMock('SKYFILLERS\\SfEventMgt\\Controller\\AdministrationController', array('redirect', 'forward', 'addFlashMessage', 'redirectToUri', 'getCurrentPageUid'), array(), '', FALSE);
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
	 */
	public function listActionFetchesAllEventsFromRepositoryAndAssignsThemToView() {
		$demand = new \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand();
		$allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$eventRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\EventRepository',
			array('findDemanded'), array(), '', FALSE);
		$eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
		$this->inject($this->subject, 'eventRepository', $eventRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('events', $allEvents);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function newActionRedirectsToExpectedUrl() {
		$expected = 'alt_doc.php?edit[tx_sfeventmgt_domain_model_event][0]=new&returnUrl=mod.php' .
			'%3FM%3Dweb_SfEventMgtTxSfeventmgtM1%26id%3D0%26moduleToken%3DdummyToken';
		$this->subject->expects($this->once())->method('redirectToUri')->with($expected);
		$this->subject->expects($this->any())->method('getCurrentPageUid')->will($this->returnValue(0));
		$this->subject->newEventAction();
	}

	/**
	 * @test
	 */
	public function getCurrentPageUidReturnsExpectedUid() {
		$this->subject->expects($this->once())->method('getCurrentPageUid')->will($this->returnValue(11));
		$this->assertSame(11, $this->subject->_call('getCurrentPageUid'));
	}
}
