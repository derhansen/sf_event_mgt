<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * ICalenderService
 */
class ICalendarService
{
    protected ConfigurationManager $configurationManager;
    protected FluidStandaloneService $fluidStandaloneService;

    public function injectConfigurationManager(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    public function injectFluidStandaloneService(FluidStandaloneService $fluidStandaloneService)
    {
        $this->fluidStandaloneService = $fluidStandaloneService;
    }

    /**
     * Initiates the ICS download for the given event
     *
     * @param Event $event The event
     * @throws Exception Exception
     */
    public function downloadiCalendarFile(Event $event): void
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
     * @param Event $event The event
     *
     * @return string
     */
    public function getiCalendarContent(Event $event): string
    {
        $icalView = GeneralUtility::makeInstance(StandaloneView::class);
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
            'typo3Host' => GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'),
        ]);
        // Render view and remove empty lines
        $icalContent = preg_replace('/^\h*\v+/m', '', $icalView->render());
        // Finally replace new lines with CRLF
        return str_replace(chr(10), chr(13) . chr(10), $icalContent);
    }
}
