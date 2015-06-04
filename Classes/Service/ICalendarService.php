<?php
namespace DERHANSEN\SfEventMgt\Service;

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

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * ICalenderService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarService {

	/**
	 * The object manager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;

	/**
	 * The configuration manager
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * ResourceFactory
	 *
	 * @var \TYPO3\CMS\Core\Resource\ResourceFactory
	 * @inject
	 */
	protected $resourceFactory = NULL;

	/**
	 * Initiates the ICS download for the given event
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event The event
	 *
	 * @throws \RuntimeException Exception
	 *
	 * @return void
	 */
	public function downloadiCalendarFile(\DERHANSEN\SfEventMgt\Domain\Model\Event $event) {
		$storage = $this->resourceFactory->getDefaultStorage();
		if ($storage === NULL) {
			throw new \RuntimeException('Could not get the default storage', 1475590001);
		}
		$icalContent = $this->getICalendarContent($event);
		$tempFolder = $storage->getFolder('_temp_');
		$tempFile = $storage->createFile('event.ics', $tempFolder);
		$tempFile->setContents($icalContent);
		$storage->dumpFileContents($tempFile, TRUE, 'event_' . $event->getUid() . '.ics');
	}

	/**
	 * Returns the rendered iCalendar entry for the given event
	 * according to RFC 2445
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event The event
	 *
	 * @return string
	 */
	public function getiCalendarContent(\DERHANSEN\SfEventMgt\Domain\Model\Event $event) {
		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $icalView */
		$icalView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
		$icalView->setFormat('txt');
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$templateRootPath = GeneralUtility::getFileAbsFileName(
			$extbaseFrameworkConfiguration['plugin.']['tx_sfeventmgt.']['view.']['templateRootPath']);
		$layoutRootPath = GeneralUtility::getFileAbsFileName(
			$extbaseFrameworkConfiguration['plugin.']['tx_sfeventmgt.']['view.']['layoutRootPath']);

		$icalView->setLayoutRootPath($layoutRootPath);
		$icalView->setTemplatePathAndFilename($templateRootPath . 'Event/ICalendar.txt');
		$icalView->assignMultiple(array(
			'event' => $event,
			'typo3Host' => GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY')
		));
		// Render view and remove empty lines
		$icalContent = preg_replace('/^\h*\v+/m', '', $icalView->render());
		// Finally replace new lines with CRLF
		$icalContent = str_replace(chr(10), chr(13) . chr(10), $icalContent);
		return $icalContent;
	}
}