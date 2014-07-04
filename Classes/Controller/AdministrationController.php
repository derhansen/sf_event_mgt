<?php
namespace SKYFILLERS\SfEventMgt\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
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

/**
 * AdministrationController
 */
class AdministrationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * eventRepository
	 *
	 * @var \SKYFILLERS\SfEventMgt\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository = NULL;

	/**
	 * Page uid
	 *
	 * @var integer
	 */
	protected $pageUid = 0;

	/**
	 * Function will be called before every other action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->pageUid = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id');
		parent::initializeAction();
	}

	/**
	 * List action for backend module
	 *
	 * @todo Fill demand with demand from backend module
	 * @return void
	 */
	public function listAction() {
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$events = $this->eventRepository->findDemanded($demand);
		$this->view->assign('events', $events);
	}

	/**
	 * Add an event in backend module
	 *
	 * @return void
	 */
	public function newEventAction() {
		$this->redirectToCreateNewRecord('tx_sfeventmgt_domain_model_event');
	}

	/**
	 * Redirect to tceform creating a new record
	 *
	 * @param string $table table name
	 * @return void
	 */
	private function redirectToCreateNewRecord($table) {
		$pid = $this->pageUid;

		$returnUrl = 'mod.php?M=web_SfEventMgtTxSfeventmgtM1&id=' . $this->pageUid . $this->getToken();
		$url = 'alt_doc.php?edit[' . $table . '][' . $pid . ']=new&returnUrl=' . urlencode($returnUrl);

		\TYPO3\CMS\Core\Utility\HttpUtility::redirect($url);
	}

	/**
	 * Get a CSRF token
	 *
	 * @return string
	 */
	protected function getToken() {
		return '&moduleToken=' . \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get()->generateToken('moduleCall', 'web_SfEventMgtTxSfeventmgtM1');
	}
}