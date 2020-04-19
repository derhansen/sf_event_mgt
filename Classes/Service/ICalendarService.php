<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * ICalenderService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarService
{
    /**
     * The object manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * The configuration manager
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     */
    protected $configurationManager;

    /**
     * FluidStandaloneService
     *
     * @var \DERHANSEN\SfEventMgt\Service\FluidStandaloneService
     */
    protected $fluidStandaloneService;

    /**
     * DI for $configurationManager
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager
     */
    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager
    ) {
        $this->configurationManager = $configurationManager;
    }

    /**
     * DI for $fluidStandaloneService
     *
     * @param FluidStandaloneService $fluidStandaloneService
     */
    public function injectFluidStandaloneService(
        \DERHANSEN\SfEventMgt\Service\FluidStandaloneService $fluidStandaloneService
    ) {
        $this->fluidStandaloneService = $fluidStandaloneService;
    }

    /**
     * DI for $objectManager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Initiates the ICS download for the given event
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event The event
     * @throws Exception Exception
     */
    public function downloadiCalendarFile(\DERHANSEN\SfEventMgt\Domain\Model\Event $event)
    {
        $content = $this->getICalendarContent($event);
        header('Content-Disposition: attachment; filename="event' . $event->getUid() . '.ics"');
        header('Content-Type: text/calendar');
        header('Content-Length: ' . strlen($content));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: no-cache');
        echo $content;
    }

    /**
     * Returns the rendered iCalendar entry for the given event
     * according to RFC 2445
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event The event
     *
     * @return string
     */
    public function getiCalendarContent(\DERHANSEN\SfEventMgt\Domain\Model\Event $event)
    {
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $icalView */
        $icalView = $this->objectManager->get(StandaloneView::class);
        $icalView->setFormat('txt');
        $templateRootPaths = $this->fluidStandaloneService->getTemplateFolders('template');
        $layoutRootPaths = $this->fluidStandaloneService->getTemplateFolders('layout');
        $partialRootPaths = $this->fluidStandaloneService->getTemplateFolders('partial');
        $icalView->setTemplateRootPaths($templateRootPaths);
        $icalView->setLayoutRootPaths($layoutRootPaths);
        $icalView->setPartialRootPaths($partialRootPaths);
        $icalView->setTemplate('Event/ICalendar.txt');
        $icalView->assignMultiple([
            'event' => $event,
            'typo3Host' => GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY')
        ]);
        // Render view and remove empty lines
        $icalContent = preg_replace('/^\h*\v+/m', '', $icalView->render());
        // Finally replace new lines with CRLF
        $icalContent = str_replace(chr(10), chr(13) . chr(10), $icalContent);

        return $icalContent;
    }
}
