<?php
namespace DERHANSEN\SfEventMgt\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Service;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

/**
 * AdministrationController
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AdministrationController extends AbstractController
{
    /**
     * CustomNotificationLogRepository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository
     */
    protected $customNotificationLogRepository = null;

    /**
     * ExportService
     *
     * @var \DERHANSEN\SfEventMgt\Service\ExportService
     */
    protected $exportService = null;

    /**
     * SettingsService
     *
     * @var \DERHANSEN\SfEventMgt\Service\SettingsService
     */
    protected $settingsService = null;

    /**
     * Backend User Session Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\BeUserSessionService
     */
    protected $beUserSessionService = null;

    /**
     * The current page uid
     *
     * @var int
     */
    protected $pid = 0;

    /**
     * DI for $customNotificationLogRepository
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository $customNotificationLogRepository
     */
    public function injectCustomNotificationLogRepository(
        \DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository $customNotificationLogRepository
    ) {
        $this->customNotificationLogRepository = $customNotificationLogRepository;
    }

    /**
     * DI for $exportService
     *
     * @param Service\ExportService $exportService
     */
    public function injectExportService(\DERHANSEN\SfEventMgt\Service\ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * DI for $settingsService
     *
     * @param Service\SettingsService $settingsService
     */
    public function injectSettingsService(\DERHANSEN\SfEventMgt\Service\SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * DI for $beUserSessionService
     *
     * @param Service\BeUserSessionService $beUserSessionService
     */
    public function injectBeUserSessionService(\DERHANSEN\SfEventMgt\Service\BeUserSessionService $beUserSessionService)
    {
        $this->beUserSessionService = $beUserSessionService;
    }

    /**
     * Initialize action
     *
     * @return void
     */
    public function initializeAction()
    {
        $this->pid = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id');
    }

    /**
     * Set date format for fields startDate and endDate
     *
     * @return void
     */
    public function initializeListAction()
    {
        if ($this->settings === null) {
            $this->redirect('settingsError');
        }
        $this->arguments->getArgument('searchDemand')
            ->getPropertyMappingConfiguration()->forProperty('startDate')
            ->setTypeConverterOption(
                DateTimeConverter::class,
                DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                $this->settings['search']['dateFormat']
            );
        $this->arguments->getArgument('searchDemand')
            ->getPropertyMappingConfiguration()->forProperty('endDate')
            ->setTypeConverterOption(
                DateTimeConverter::class,
                DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                $this->settings['search']['dateFormat']
            );
    }

    /**
     * List action for backend module
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand $searchDemand SearchDemand
     * @param int $messageId MessageID
     *
     * @return void
     */
    public function listAction(SearchDemand $searchDemand = null, $messageId = null)
    {
        /** @var EventDemand $demand */
        $demand = $this->objectManager->get(EventDemand::class);

        if ($searchDemand !== null) {
            $searchDemand->setFields($this->settings['search']['fields']);

            $sessionData = [];
            $sessionData['searchDemand'] = $searchDemand;
            $this->beUserSessionService->saveSessionData($sessionData);
        } else {
            // Try to restore search demand from Session
            $searchDemand = $this->beUserSessionService->getSessionDataByKey('searchDemand');
        }

        $demand->setSearchDemand($searchDemand);

        if ($this->pid > 0) {
            $demand->setStoragePage($this->pid);
        }

        $variables = [];
        if ($messageId !== null && is_numeric($messageId)) {
            $variables['showMessage'] = true;
            $variables['messageTitleKey'] = 'administration.message-' . $messageId . '.title';
            $variables['messageContentKey'] = 'administration.message-' . $messageId . '.content';
        }

        $variables['events'] = $this->eventRepository->findDemanded($demand);
        $variables['searchDemand'] = $searchDemand;
        $variables['csvExportPossible'] = $this->getBackendUser()->getDefaultUploadTemporaryFolder() !== null;
        $this->view->assignMultiple($variables);
    }

    /**
     * Export registrations for a given event
     *
     * @param int $eventUid Event UID
     *
     * @return bool Always FALSE, since no view should be rendered
     */
    public function exportAction($eventUid)
    {
        $this->exportService->downloadRegistrationsCsv($eventUid, $this->settings['csvExport']);

        return false;
    }

    /**
     * Calls the handleExpiredRegistrations Service
     *
     * @return void
     */
    public function handleExpiredRegistrationsAction()
    {
        $this->registrationService->handleExpiredRegistrations($this->settings['registration']['deleteExpiredRegistrations']);
        $this->redirect('list', 'Administration', 'SfEventMgt', ['demand' => null, 'messageId' => 1]);
    }

    /**
     * The index notify action
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     *
     * @return void
     */
    public function indexNotifyAction(Event $event)
    {
        $customNotifications = $this->settingsService->getCustomNotifications($this->settings);
        $logEntries = $this->customNotificationLogRepository->findByEvent($event);
        $this->view->assignMultiple([
            'event' => $event,
            'customNotifications' => $customNotifications,
            'logEntries' => $logEntries,
        ]);
    }

    /**
     * Notify action
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @param string $customNotification CustomNotification
     *
     * @return void
     */
    public function notifyAction(Event $event, $customNotification)
    {
        $customNotifications = $this->settingsService->getCustomNotifications($this->settings);
        $result = $this->notificationService->sendCustomNotification($event, $customNotification, $this->settings);
        $this->notificationService->createCustomNotificationLogentry(
            $event,
            $customNotifications[$customNotification],
            $result
        );
        $this->redirect('list', 'Administration', 'SfEventMgt', ['demand' => null, 'messageId' => 2]);
    }

    /**
     * Shows the settings error view
     *
     * @return void
     */
    public function settingsErrorAction()
    {
    }

    /**
     * Suppress default validation messages
     *
     * @return bool
     */
    protected function getErrorFlashMessage()
    {
        return false;
    }

    /**
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
}
