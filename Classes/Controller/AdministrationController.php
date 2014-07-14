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

use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use SKYFILLERS\SfEventMgt\Service;

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
	 * The current page uid
	 *
	 * @var int
	 */
	protected $pid = 0;

	/**
	 * exportService
	 *
	 * @var \SKYFILLERS\SfEventMgt\Service\ExportService
	 * @inject
	 */
	protected $exportService = NULL;

	/**
	 * registrationService
	 *
	 * @var \SKYFILLERS\SfEventMgt\Service\RegistrationService
	 * @inject
	 */
	protected $registrationService = NULL;

	/**
	 * Initialize action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->pid = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id');
	}

	/**
	 * Set date format for field dateOfBirth
	 *
	 * @return void
	 */
	public function initializeListAction() {
		$this->arguments->getArgument('demand')
			->getPropertyMappingConfiguration()->forProperty('startDate')
			->setTypeConverterOption(
				'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
				DateTimeConverter::CONFIGURATION_DATE_FORMAT,
				$this->settings['search']['dateFormat']
			);
		$this->arguments->getArgument('demand')
			->getPropertyMappingConfiguration()->forProperty('endDate')
			->setTypeConverterOption(
				'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
				DateTimeConverter::CONFIGURATION_DATE_FORMAT,
				$this->settings['search']['dateFormat']
			);
	}

	/**
	 * List action for backend module
	 *
	 * @param \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand $demand
	 * @param int $messageId
	 * @return void
	 */
	public function listAction($demand = NULL, $messageId = NULL) {
		if ($demand === NULL) {
			$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		}

		if ($this->pid > 0) {
			$demand->setStoragePage($this->pid);
		}

		if ($messageId !== NULL && is_numeric($messageId)) {
			$this->view->assign('showMessage', TRUE);
			$this->view->assign('messageTitleKey', 'administration.message-' . $messageId . '.title');
			$this->view->assign('messageContentKey', 'administration.message-' . $messageId . '.content');
		}

		$events = $this->eventRepository->findDemanded($demand);
		$this->view->assign('events', $events);
		$this->view->assign('demand', $demand);
	}

	/**
	 * Add an event in backend module
	 *
	 * @return void
	 */
	public function newEventAction() {
		$token = '&moduleToken=' . \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get()->generateToken(
				'moduleCall', 'web_SfEventMgtTxSfeventmgtM1');

		$returnUrl = 'mod.php?M=web_SfEventMgtTxSfeventmgtM1&id=' . $this->pid . $token;
		$url = 'alt_doc.php?edit[tx_sfeventmgt_domain_model_event][' . $this->pid .
			']=new&returnUrl=' . urlencode($returnUrl);
		$this->redirectToUri($url);
	}

	/**
	 * export registrations for a given event
	 *
	 * @param int $eventUid
	 * @return void
	 */
	public function exportAction($eventUid) {
		$this->exportService->downloadRegistrationsCsv($eventUid, $this->settings['csvExport']);
	}

	/**
	 * Calls the handleExpiredRegistrations Service
	 *
	 * @return void
	 */
	public function handleExpiredRegistrationsAction() {
		$this->registrationService->handleExpiredRegistrations(
			$this->settings['registration']['deleteExpiredRegistrations']);
		$this->redirect('list', 'Administration', 'SfEventMgt', array('demand' => NULL, 'messageId' => 1));
	}
}