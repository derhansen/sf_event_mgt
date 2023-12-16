<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Controller;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Event\InitAdministrationModuleTemplateEvent;
use DERHANSEN\SfEventMgt\Event\ModifyAdministrationIndexNotifyViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyAdministrationListViewVariablesEvent;
use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use DERHANSEN\SfEventMgt\Service\ExportService;
use DERHANSEN\SfEventMgt\Service\MaintenanceService;
use DERHANSEN\SfEventMgt\Service\SettingsService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AdministrationController extends AbstractController
{
    private const LANG_FILE = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:';

    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected CustomNotificationLogRepository $customNotificationLogRepository;
    protected ExportService $exportService;
    protected SettingsService $settingsService;
    protected BeUserSessionService $beUserSessionService;
    protected MaintenanceService $maintenanceService;
    protected IconFactory $iconFactory;
    protected PageRenderer $pageRenderer;
    protected int $pid = 0;

    public function injectCustomNotificationLogRepository(
        CustomNotificationLogRepository $customNotificationLogRepository
    ): void {
        $this->customNotificationLogRepository = $customNotificationLogRepository;
    }

    public function injectExportService(ExportService $exportService): void
    {
        $this->exportService = $exportService;
    }

    public function injectSettingsService(SettingsService $settingsService): void
    {
        $this->settingsService = $settingsService;
    }

    public function injectBeUserSessionService(BeUserSessionService $beUserSessionService): void
    {
        $this->beUserSessionService = $beUserSessionService;
    }

    public function injectIconFactory(IconFactory $iconFactory): void
    {
        $this->iconFactory = $iconFactory;
    }

    public function injectMaintenanceService(MaintenanceService $maintenanceService): void
    {
        $this->maintenanceService = $maintenanceService;
    }

    public function injectModuleTemplateFactory(ModuleTemplateFactory $moduleTemplateFactory): void
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function injectPageRenderer(PageRenderer $pageRenderer): void
    {
        $this->pageRenderer = $pageRenderer;
    }

    /**
     * Register docHeaderButtons
     */
    protected function registerDocHeaderButtons(ModuleTemplate $moduleTemplate): void
    {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        if ($this->request->getControllerActionName() === 'list') {
            $buttons = [
                [
                    'label' => 'administration.newEvent',
                    'link' => $this->getCreateNewRecordUri('tx_sfeventmgt_domain_model_event'),
                    'icon' => 'ext-sfeventmgt-event',
                    'group' => 1,
                ],
                [
                    'label' => 'administration.newLocation',
                    'link' => $this->getCreateNewRecordUri('tx_sfeventmgt_domain_model_location'),
                    'icon' => 'ext-sfeventmgt-location',
                    'group' => 1,
                ],
                [
                    'label' => 'administration.newOrganisator',
                    'link' => $this->getCreateNewRecordUri('tx_sfeventmgt_domain_model_organisator'),
                    'icon' => 'ext-sfeventmgt-organisator',
                    'group' => 1,
                ],
                [
                    'label' => 'administration.newSpeaker',
                    'link' => $this->getCreateNewRecordUri('tx_sfeventmgt_domain_model_speaker'),
                    'icon' => 'ext-sfeventmgt-speaker',
                    'group' => 1,
                ],
                [
                    'label' => 'administration.handleExpiredRegistrations',
                    'link' => $this->uriBuilder->reset()->setRequest($this->request)
                        ->uriFor('handleExpiredRegistrations', [], 'Administration'),
                    'icon' => 'ext-sfeventmgt-action-handle-expired',
                    'group' => 2,
                ],
            ];
            foreach ($buttons as $tableConfiguration) {
                $title = $this->getLanguageService()->sL(self::LANG_FILE . $tableConfiguration['label']);
                $icon = $this->iconFactory->getIcon($tableConfiguration['icon'], Icon::SIZE_SMALL);
                $viewButton = $buttonBar->makeLinkButton()
                    ->setHref($tableConfiguration['link'])
                    ->setDataAttributes([
                        'toggle' => 'tooltip',
                        'placement' => 'bottom',
                        'title' => $title,
                        ])
                    ->setTitle($title)
                    ->setIcon($icon);
                $buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_LEFT, $tableConfiguration['group']);
            }
        }
    }

    /**
     * Returns the create new record URL for the given table
     */
    private function getCreateNewRecordUri(string $table): string
    {
        $pid = $this->pid;
        $tsConfig = BackendUtility::getPagesTSconfig(0);
        if ($pid === 0 && isset($tsConfig['defaultPid.'])
            && is_array($tsConfig['defaultPid.'])
            && isset($tsConfig['defaultPid.'][$table])
        ) {
            $pid = (int)$tsConfig['defaultPid.'][$table];
        }

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

        return (string)$uriBuilder->buildUriFromRoute('record_edit', [
            'edit[' . $table . '][' . $pid . ']' => 'new',
            'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI'),
        ]);
    }

    /**
     * Initializes module template and returns a response which must be used as response for any extbase action
     * that should render a view.
     */
    protected function initModuleTemplateAndReturnResponse(string $templateFileName, array $variables = []): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->pageRenderer->addCssFile('EXT:sf_event_mgt/Resources/Public/Css/administration.css');

        $this->pageRenderer->loadJavaScriptModule('@derhansen/sf_event_mgt/administration-module.js');

        $this->registerDocHeaderButtons($moduleTemplate);

        $moduleTemplate->setFlashMessageQueue($this->getFlashMessageQueue());

        $initAdministrationModuleTemplateEvent = new InitAdministrationModuleTemplateEvent(
            $moduleTemplate,
            $this->uriBuilder,
            $this
        );
        $this->eventDispatcher->dispatch($initAdministrationModuleTemplateEvent);

        $variables['settings'] = $this->settings;
        $moduleTemplate->assignMultiple($variables);

        return $moduleTemplate->renderResponse($templateFileName);
    }

    public function initializeAction(): void
    {
        $this->pid = (int)($this->request->getQueryParams()['id'] ?? 0);
    }

    /**
     * Set date format for fields startDate and endDate
     */
    public function initializeListAction(): void
    {
        // Static format needed for date picker (flatpickr), see BackendController::generateJavascript() and #91606
        if (!empty($this->settings)) {
            $this->settings['search']['dateFormat'] = 'H:i d-m-Y';
        }
    }

    /**
     * List action for backend module
     */
    public function listAction(?SearchDemand $searchDemand = null, array $overwriteDemand = []): ResponseInterface
    {
        if (empty($this->settings)) {
            return $this->redirect('settingsError');
        }

        if ($searchDemand !== null) {
            $searchDemand->setFields($this->settings['search']['fields'] ?? 'title');

            $sessionData = [];
            $sessionData['searchDemand'] = $searchDemand->toArray();
            $sessionData['overwriteDemand'] = $overwriteDemand;
            $this->beUserSessionService->saveSessionData($sessionData);
        } else {
            // Try to restore search demand from Session
            $sessionSearchDemand = $this->beUserSessionService->getSessionDataByKey('searchDemand') ?? [];
            $searchDemand = SearchDemand::fromArray($sessionSearchDemand);
            $overwriteDemand = $this->beUserSessionService->getSessionDataByKey('overwriteDemand');
        }

        if ($this->isResetFilter()) {
            $searchDemand = GeneralUtility::makeInstance(SearchDemand::class);
            $overwriteDemand = [];

            $sessionData = [];
            $sessionData['searchDemand'] = $searchDemand->toArray();
            $sessionData['overwriteDemand'] = $overwriteDemand;
            $this->beUserSessionService->saveSessionData($sessionData);
        }

        // Initialize default ordering when no overwriteDemand is available
        if (empty($overwriteDemand)) {
            $overwriteDemand = [
                'orderField' => $this->settings['defaultSorting']['orderField'] ?? 'title',
                'orderDirection' => $this->settings['defaultSorting']['orderDirection'] ?? 'asc',
            ];
        }

        $eventDemand = GeneralUtility::makeInstance(EventDemand::class);
        $eventDemand = $this->overwriteEventDemandObject($eventDemand, $overwriteDemand);
        $eventDemand->setOrderFieldAllowed($this->settings['orderFieldAllowed'] ?? '');
        $eventDemand->setSearchDemand($searchDemand);
        $eventDemand->setStoragePage((string)$this->pid);
        $eventDemand->setIgnoreEnableFields(true);

        $events = [];
        $pagination = null;
        if ($this->getBackendUser()->isInWebMount($this->pid) &&
            $this->getBackendUser()->check('tables_select', 'tx_sfeventmgt_domain_model_event')
        ) {
            $events = $this->eventRepository->findDemanded($eventDemand);
            $pagination = $this->getPagination($events, $this->settings['pagination'] ?? []);
        }

        $modifyAdministrationListViewVariablesEvent = new ModifyAdministrationListViewVariablesEvent(
            [
                'pid' => $this->pid,
                'events' => $events,
                'searchDemand' => $searchDemand,
                'orderByFields' => $this->getOrderByFields(),
                'orderDirections' => $this->getOrderDirections(),
                'overwriteDemand' => $overwriteDemand,
                'pagination' => $pagination,
            ],
            $this
        );
        $this->eventDispatcher->dispatch($modifyAdministrationListViewVariablesEvent);
        $variables = $modifyAdministrationListViewVariablesEvent->getVariables();

        return $this->initModuleTemplateAndReturnResponse('Administration/List', $variables);
    }

    /**
     * Returns, if reset filter operation has been used
     */
    private function isResetFilter(): bool
    {
        $resetFilter = false;
        if ($this->request->hasArgument('operation')) {
            $resetFilter = $this->request->getArgument('operation') === 'reset-filters';
        }

        return $resetFilter;
    }

    /**
     * Export registrations for a given event
     */
    public function exportAction(int $eventUid): void
    {
        /** @var Event $event */
        $event = $this->eventRepository->findByUidIncludeHidden($eventUid);
        if ($event !== null) {
            $this->checkEventAccess($event);
            $this->exportService->downloadRegistrationsCsv($eventUid, $this->settings['csvExport'] ?? []);
        }
        exit();
    }

    /**
     * Handles expired registrations
     */
    public function handleExpiredRegistrationsAction(): ResponseInterface
    {
        $delete = (bool)($this->settings['registration']['deleteExpiredRegistrations'] ?? false);
        $this->maintenanceService->handleExpiredRegistrations($delete);

        $this->addFlashMessage(
            $this->getLanguageService()->sL(self::LANG_FILE . 'administration.message-1.content'),
            $this->getLanguageService()->sL(self::LANG_FILE . 'administration.message-1.title')
        );

        return $this->redirect('list');
    }

    /**
     * The index notify action
     */
    public function indexNotifyAction(Event $event): ResponseInterface
    {
        $this->checkEventAccess($event);
        $customNotification = GeneralUtility::makeInstance(CustomNotification::class);
        $customNotifications = $this->settingsService->getCustomNotifications($this->settings);
        $logEntries = $this->customNotificationLogRepository->findByEvent($event);

        $modifyAdministrationIndexNotifyViewVariablesEvent = new ModifyAdministrationIndexNotifyViewVariablesEvent(
            [
                'event' => $event,
                'recipients' => $this->getNotificationRecipients(),
                'customNotification' => $customNotification,
                'customNotifications' => $customNotifications,
                'logEntries' => $logEntries,
            ],
            $this
        );
        $this->eventDispatcher->dispatch($modifyAdministrationIndexNotifyViewVariablesEvent);
        $variables = $modifyAdministrationIndexNotifyViewVariablesEvent->getVariables();

        return $this->initModuleTemplateAndReturnResponse('Administration/IndexNotify', $variables);
    }

    /**
     * Returns an array of recipient option for the indexNotify action
     */
    public function getNotificationRecipients(): array
    {
        return [
            [
                'value' => CustomNotification::RECIPIENTS_ALL,
                'label' => $this->getLanguageService()->sL(
                    self::LANG_FILE . 'administration.notify.recipients.' . CustomNotification::RECIPIENTS_ALL
                ),
            ],
            [
                'value' => CustomNotification::RECIPIENTS_CONFIRMED,
                'label' => $this->getLanguageService()->sL(
                    self::LANG_FILE . 'administration.notify.recipients.' . CustomNotification::RECIPIENTS_CONFIRMED
                ),
            ],
            [
                'value' => CustomNotification::RECIPIENTS_UNCONFIRMED,
                'label' => $this->getLanguageService()->sL(
                    self::LANG_FILE . 'administration.notify.recipients.' . CustomNotification::RECIPIENTS_UNCONFIRMED
                ),
            ],
        ];
    }

    /**
     * Notify action
     */
    public function notifyAction(Event $event, CustomNotification $customNotification): ResponseInterface
    {
        $this->checkEventAccess($event);
        $customNotifications = $this->settingsService->getCustomNotifications($this->settings);
        $result = $this->notificationService->sendCustomNotification($event, $customNotification, $this->settings);
        $this->notificationService->createCustomNotificationLogentry(
            $event,
            $customNotifications[$customNotification->getTemplate()],
            $result,
            $customNotification
        );
        $this->addFlashMessage(
            $this->getLanguageService()->sL(self::LANG_FILE . 'administration.message-2.content'),
            $this->getLanguageService()->sL(self::LANG_FILE . 'administration.message-2.title')
        );
        return $this->redirect('list');
    }

    /**
     * Checks if the current backend user has access to the PID of the event and if not, enqueue an
     * access denied flash message and redirect to list view
     */
    public function checkEventAccess(Event $event): void
    {
        if ($this->getBackendUser()->isInWebMount($event->getPid()) === null) {
            $this->addFlashMessage(
                $this->getLanguageService()->sL(self::LANG_FILE . 'administration.accessdenied.content'),
                $this->getLanguageService()->sL(self::LANG_FILE . 'administration.accessdenied.title'),
                ContextualFeedbackSeverity::ERROR
            );

            $this->redirect('list');
        }
    }

    /**
     * Shows the settings error view
     */
    public function settingsErrorAction(): ResponseInterface
    {
        return $this->initModuleTemplateAndReturnResponse('Administration/SettingsError');
    }

    /**
     * Suppress default validation messages
     */
    protected function getErrorFlashMessage(): bool
    {
        return false;
    }

    /**
     * Returns an array with possible order directions
     */
    public function getOrderDirections(): array
    {
        return [
            'asc' => $this->getLanguageService()->sL(self::LANG_FILE . 'administration.sortOrder.asc'),
            'desc' => $this->getLanguageService()->sL(self::LANG_FILE . 'administration.sortOrder.desc'),
        ];
    }

    /**
     * Returns an array with possible orderBy fields
     */
    public function getOrderByFields(): array
    {
        return [
            'title' => $this->getLanguageService()->sL(self::LANG_FILE . 'administration.orderBy.title'),
            'startdate' => $this->getLanguageService()->sL(self::LANG_FILE . 'administration.orderBy.startdate'),
            'enddate' => $this->getLanguageService()->sL(self::LANG_FILE . 'administration.orderBy.enddate'),
        ];
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
