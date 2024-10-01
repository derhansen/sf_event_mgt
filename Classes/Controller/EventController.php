<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Controller;

use DateInterval;
use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Event\AfterRegistrationCancelledEvent;
use DERHANSEN\SfEventMgt\Event\AfterRegistrationConfirmedEvent;
use DERHANSEN\SfEventMgt\Event\AfterRegistrationSavedEvent;
use DERHANSEN\SfEventMgt\Event\ModifyCalendarViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyCancelRegistrationViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyConfirmRegistrationViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyCreateDependingRegistrationsEvent;
use DERHANSEN\SfEventMgt\Event\ModifyDetailViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyListViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyRegistrationViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifySearchViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ProcessCancelDependingRegistrationsEvent;
use DERHANSEN\SfEventMgt\Event\ProcessRedirectToPaymentEvent;
use DERHANSEN\SfEventMgt\Event\WaitlistMoveUpEvent;
use DERHANSEN\SfEventMgt\Exception;
use DERHANSEN\SfEventMgt\Security\HashScope;
use DERHANSEN\SfEventMgt\Service\EventCacheService;
use DERHANSEN\SfEventMgt\Service\EventEvaluationService;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Cache\CacheTag;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Frontend\Controller\ErrorController;

class EventController extends AbstractController
{
    protected EventCacheService $eventCacheService;
    protected ViewFactoryInterface $viewFactory;
    protected EventEvaluationService $eventEvaluationService;

    public function injectEventCacheService(EventCacheService $cacheService): void
    {
        $this->eventCacheService = $cacheService;
    }

    public function injectViewFactoryInterface(ViewFactoryInterface $viewFactory): void
    {
        $this->viewFactory = $viewFactory;
    }

    public function injectEventEvaluationService(EventEvaluationService $eventEvaluationService): void
    {
        $this->eventEvaluationService = $eventEvaluationService;
    }

    /**
     * Assign contentObjectData and pageData view
     */
    protected function initializeView(): void
    {
        $this->view->assign('contentObjectData', $this->request->getAttribute('currentContentObject')->data ?? null);
        $this->view->assign('pageData', $this->getFrontendPageInformation()->getPageRecord());
    }

    /**
     * Initializes the current action
     */
    public function initializeAction(): void
    {
        static $cacheTagsSet = false;

        if (!$cacheTagsSet) {
            $this->request->getAttribute('frontend.cache.collector')->addCacheTags(new CacheTag('tx_sfeventmgt'));
            $cacheTagsSet = true;
        }
    }

    /**
     * Initialize list action and set format
     */
    public function initializeListAction(): void
    {
        if (isset($this->settings['list']['format'])) {
            $this->request = $this->request->withFormat($this->settings['list']['format']);
        }
    }

    /**
     * List view
     */
    public function listAction(array $overwriteDemand = []): ResponseInterface
    {
        $eventDemand = EventDemand::createFromSettings($this->settings);
        $foreignRecordDemand = ForeignRecordDemand::createFromSettings($this->settings);
        $categoryDemand = CategoryDemand::createFromSettings($this->settings);
        if ($this->isOverwriteDemand($overwriteDemand)) {
            $eventDemand = $this->overwriteEventDemandObject($eventDemand, $overwriteDemand);
        }
        $events = $this->eventRepository->findDemanded($eventDemand);
        $categories = $this->categoryRepository->findDemanded($categoryDemand);
        $locations = $this->locationRepository->findDemanded($foreignRecordDemand);
        $organisators = $this->organisatorRepository->findDemanded($foreignRecordDemand);
        $speakers = $this->speakerRepository->findDemanded($foreignRecordDemand);

        $modifyListViewVariablesEvent = new ModifyListViewVariablesEvent(
            [
                'events' => $events,
                'pagination' => $this->getPagination($events, $this->settings['pagination'] ?? []),
                'categories' => $categories,
                'locations' => $locations,
                'organisators' => $organisators,
                'speakers' => $speakers,
                'overwriteDemand' => $overwriteDemand,
                'eventDemand' => $eventDemand,
                'settings' => $this->settings,
            ],
            $this,
            $this->request
        );
        $this->eventDispatcher->dispatch($modifyListViewVariablesEvent);
        $variables = $modifyListViewVariablesEvent->getVariables();
        $this->view->assignMultiple($variables);

        $cacheDataCollector = $this->request->getAttribute('frontend.cache.collector');
        $this->eventCacheService->addPageCacheTagsByEventDemandObject($cacheDataCollector, $eventDemand);

        return $this->htmlResponse();
    }

    /**
     * Calendar view
     */
    public function calendarAction(array $overwriteDemand = []): ResponseInterface
    {
        $eventDemand = EventDemand::createFromSettings($this->settings);
        $foreignRecordDemand = ForeignRecordDemand::createFromSettings($this->settings);
        $categoryDemand = CategoryDemand::createFromSettings($this->settings);
        if ($this->isOverwriteDemand($overwriteDemand)) {
            $eventDemand = $this->overwriteEventDemandObject($eventDemand, $overwriteDemand);
        }

        // Set month/year to demand if not given
        if (!$eventDemand->getMonth()) {
            $currentMonth = (int)date('n');
            $eventDemand->setMonth($currentMonth);
        } else {
            $currentMonth = $eventDemand->getMonth();
        }
        if (!$eventDemand->getYear()) {
            $currentYear = (int)date('Y');
            $eventDemand->setYear($currentYear);
        } else {
            $currentYear = $eventDemand->getYear();
        }

        // If a weeknumber is given in overwriteDemand['week'], we overwrite the current month
        if ($overwriteDemand['week'] ?? false) {
            $firstDayOfWeek = (new DateTime())->setISODate($currentYear, (int)$overwriteDemand['week']);
            $currentMonth = (int)$firstDayOfWeek->format('m');
            $eventDemand->setMonth($currentMonth);
        } else {
            $firstDayOfWeek = (new DateTime())->setISODate($currentYear, (int)date('W'));
        }

        // Set demand from calendar date range instead of month / year
        if ((bool)($this->settings['calendar']['includeEventsForEveryDayOfAllCalendarWeeks'] ?? false)) {
            $eventDemand = $this->changeEventDemandToFullMonthDateRange($eventDemand);
        }

        $events = $this->eventRepository->findDemanded($eventDemand);
        $weeks = $this->calendarService->getCalendarArray(
            $currentMonth,
            $currentYear,
            strtotime('today midnight'),
            (int)($this->settings['calendar']['firstDayOfWeek'] ?? 1),
            $events
        );

        $modifyCalendarViewVariablesEvent = new ModifyCalendarViewVariablesEvent(
            [
                'events' => $events,
                'weeks' => $weeks,
                'categories' => $this->categoryRepository->findDemanded($categoryDemand),
                'locations' => $this->locationRepository->findDemanded($foreignRecordDemand),
                'organisators' => $this->organisatorRepository->findDemanded($foreignRecordDemand),
                'eventDemand' => $eventDemand,
                'overwriteDemand' => $overwriteDemand,
                'currentPageId' => $this->getFrontendPageInformation()->getId(),
                'firstDayOfMonth' => DateTime::createFromFormat(
                    'd.m.Y',
                    sprintf('1.%s.%s', $currentMonth, $currentYear)
                ),
                'previousMonthConfig' => $this->calendarService->getDateConfig($currentMonth, $currentYear, '-1 month'),
                'nextMonthConfig' => $this->calendarService->getDateConfig($currentMonth, $currentYear, '+1 month'),
                'weekConfig' => $this->calendarService->getWeekConfig($firstDayOfWeek),
                'settings' => $this->settings,
            ],
            $this,
            $this->request
        );
        $this->eventDispatcher->dispatch($modifyCalendarViewVariablesEvent);
        $variables = $modifyCalendarViewVariablesEvent->getVariables();

        $this->view->assignMultiple($variables);
        return $this->htmlResponse();
    }

    /**
     * Changes the given event demand object to select a date range for a calendar month including days of the previous
     * month for the first week and they days for the next month for the last week
     */
    protected function changeEventDemandToFullMonthDateRange(EventDemand $eventDemand): EventDemand
    {
        $calendarDateRange = $this->calendarService->getCalendarDateRange(
            $eventDemand->getMonth(),
            $eventDemand->getYear(),
            (int)($this->settings['calendar']['firstDayOfWeek'] ?? 0)
        );

        $eventDemand->setMonth(0);
        $eventDemand->setYear(0);

        $startDate = new DateTime();
        $startDate->setTimestamp($calendarDateRange['firstDayOfCalendar']);
        $endDate = new DateTime();
        $endDate->setTimestamp($calendarDateRange['lastDayOfCalendar']);
        $endDate->setTime(23, 59, 59);

        $searchDemand = GeneralUtility::makeInstance(SearchDemand::class);
        $searchDemand->setStartDate($startDate);
        $searchDemand->setEndDate($endDate);
        $eventDemand->setSearchDemand($searchDemand);

        return $eventDemand;
    }

    /**
     * Detail view for an event
     *
     * @return mixed
     */
    public function detailAction(?Event $event = null)
    {
        $event = $this->eventEvaluationService->evaluateForDetailAction($this->request, $this->settings, $event);
        if (is_null($event) && isset($this->settings['event']['errorHandling'])) {
            return $this->handleEventNotFoundError($this->settings);
        }

        $modifyDetailViewVariablesEvent = new ModifyDetailViewVariablesEvent(
            [
                'event' => $event,
                'settings' => $this->settings,
            ],
            $this,
            $this->request
        );
        $this->eventDispatcher->dispatch($modifyDetailViewVariablesEvent);
        $variables = $modifyDetailViewVariablesEvent->getVariables();

        $this->view->assignMultiple($variables);
        if ($event !== null) {
            $cacheDataCollector = $this->request->getAttribute('frontend.cache.collector');
            $this->eventCacheService->addCacheTagsByEventRecords($cacheDataCollector, [$event]);
        }

        return $this->htmlResponse();
    }

    /**
     * Error handling if event is not found
     *
     * @param array $settings
     * @return ResponseInterface
     * @throws Exception
     * @throws PropagateResponseException
     * @throws PageNotFoundException
     */
    protected function handleEventNotFoundError(array $settings): ResponseInterface
    {
        if (empty($settings['event']['errorHandling'])) {
            throw new Exception('Event errorHandling not configured. Please check settings.event.errorHandling', 1671205677);
        }

        $configuration = GeneralUtility::trimExplode(',', $settings['event']['errorHandling'], true);

        switch ($configuration[0]) {
            case 'redirectToListView':
                $listPid = (int)($settings['listPid'] ?? 0) > 0 ? (int)$settings['listPid'] : 1;
                return $this->redirect('list', null, null, null, $listPid);
            case 'pageNotFoundHandler':
                $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction(
                    $this->request,
                    'Event not found.'
                );
                throw new PropagateResponseException($response, 1631261423);
            case 'showStandaloneTemplate':
            default:
                $status = (int)($configuration[2] ?? 200);
                $viewFactoryData = new ViewFactoryData(
                    templatePathAndFilename: GeneralUtility::getFileAbsFileName($configuration[1]),
                    request: $this->request,
                );
                $view = $this->viewFactory->create($viewFactoryData);

                $response = $this->responseFactory->createResponse()
                    ->withStatus($status)
                    ->withHeader('Content-Type', 'text/html; charset=utf-8');
                $response->getBody()->write($view->render());
                return $response;
        }
    }

    /**
     * Initiates the iCalendar download for the given event
     *
     * @return mixed
     */
    public function icalDownloadAction(?Event $event = null)
    {
        $event = $this->eventEvaluationService->evaluateForIcalDownloadAction($this->request, $this->settings, $event);
        if (is_null($event) && isset($this->settings['event']['errorHandling'])) {
            return $this->handleEventNotFoundError($this->settings);
        }
        $this->icalendarService->downloadiCalendarFile($this->request, $event);
        exit();
    }

    /**
     * Registration view for an event
     */
    public function registrationAction(?Event $event = null): ResponseInterface
    {
        $event = $this->eventEvaluationService->evaluateForRegistrationAction($this->request, $this->settings, $event);
        if (is_null($event) && isset($this->settings['event']['errorHandling'])) {
            return $this->handleEventNotFoundError($this->settings);
        }
        if ($event->getRestrictPaymentMethods()) {
            $paymentMethods = $this->paymentService->getRestrictedPaymentMethods($event);
        } else {
            $paymentMethods = $this->paymentService->getPaymentMethods();
        }

        $modifyRegistrationViewVariablesEvent = new ModifyRegistrationViewVariablesEvent(
            [
                'event' => $event,
                'paymentMethods' => $paymentMethods,
                'settings' => $this->settings,
            ],
            $this,
            $this->request
        );
        $this->eventDispatcher->dispatch($modifyRegistrationViewVariablesEvent);
        $variables = $modifyRegistrationViewVariablesEvent->getVariables();
        $this->view->assignMultiple($variables);

        return $this->htmlResponse();
    }

    /**
     * Removes all possible spamcheck fields (which do not belong to the domain model) from arguments.
     */
    protected function removePossibleSpamCheckFieldsFromArguments(): void
    {
        $arguments = $this->request->getArguments();
        if (!isset($arguments['event'])) {
            return;
        }

        // Remove a possible honeypot field
        $honeypotField = 'hp' . (int)$arguments['event'];
        if (isset($arguments['registration'][$honeypotField])) {
            unset($arguments['registration'][$honeypotField]);
        }

        // Remove a possible challenge/response field
        if (isset($arguments['registration']['cr-response'])) {
            unset($arguments['registration']['cr-response']);
        }

        $this->request = $this->request->withArguments($arguments);
    }

    /**
     * Processes incoming registrations fields and adds field values to arguments
     */
    protected function setRegistrationFieldValuesToArguments(): void
    {
        $arguments = $this->request->getArguments();
        if (!isset($arguments['event'])) {
            return;
        }

        /** @var Event $event */
        $event = $this->eventRepository->findByUid((int)$this->request->getArgument('event'));
        if (!is_a($event, Event::class)) {
            return;
        }

        $registrationMvcArgument = $this->arguments->getArgument('registration');
        $propertyMapping = $registrationMvcArgument->getPropertyMappingConfiguration();
        $propertyMapping->allowProperties('fieldValues');
        $propertyMapping->allowCreationForSubProperty('fieldValues');
        $propertyMapping->allowModificationForSubProperty('fieldValues');

        // Set event to registration (required for validation)
        $propertyMapping->allowProperties('event');
        $propertyMapping->allowCreationForSubProperty('event');
        $propertyMapping->allowModificationForSubProperty('event');
        $arguments['registration']['event'] = (int)$this->request->getArgument('event');

        if (count($event->getRegistrationFieldsUids()) === 0) {
            // Set arguments to request, so event is set for event
            $this->request = $this->request->withArguments($arguments);
            return;
        }

        // allow creation of new objects (for validation)
        $propertyMapping->setTypeConverterOptions(
            PersistentObjectConverter::class,
            [
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED => true,
                PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED => true,
            ]
        );

        $index = 0;
        foreach ((array)($arguments['registration']['fields'] ?? []) as $fieldUid => $value) {
            // Only accept registration fields of the current event
            if (!in_array((int)$fieldUid, $event->getRegistrationFieldsUids(), true)) {
                continue;
            }

            // allow subvalues in new property mapper
            $propertyMapping->forProperty('fieldValues')->allowProperties($index);
            $propertyMapping->forProperty('fieldValues.' . $index)->allowAllProperties();
            $propertyMapping->allowCreationForSubProperty('fieldValues.' . $index);
            $propertyMapping->allowModificationForSubProperty('fieldValues.' . $index);

            if (is_array($value)) {
                if (empty($value)) {
                    $value = '';
                } else {
                    $value = json_encode($value);
                }
            }

            /** @var Registration\Field $field */
            $field = $this->fieldRepository->findByUid((int)$fieldUid);

            $arguments['registration']['fieldValues'][$index] = [
                'pid' => $field->getPid(),
                'value' => $value,
                'field' => (string)$fieldUid,
                'valueType' => $field->getValueType(),
            ];

            $index++;
        }

        // Remove temporary "fields" field
        if (isset($arguments['registration']['fields'])) {
            $arguments = ArrayUtility::removeByPath($arguments, 'registration/fields');
        }
        $this->request = $this->request->withArguments($arguments);
    }

    /**
     * Initialize arguments for saveRegistrationAction and ensures, action is only called via POST
     */
    public function initializeSaveRegistrationAction(): void
    {
        if ($this->request->getMethod() !== 'POST') {
            $response = GeneralUtility::makeInstance(ErrorController::class)->accessDeniedAction(
                $this->request,
                'HTTP method is not allowed'
            );
            throw new PropagateResponseException($response, 1726573183);
        }

        $this->arguments->getArgument('registration')
            ->getPropertyMappingConfiguration()->forProperty('dateOfBirth')
            ->setTypeConverterOption(
                DateTimeConverter::class,
                DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                $this->settings['registration']['formatDateOfBirth'] ?? 'd.m.Y'
            );
        $this->removePossibleSpamCheckFieldsFromArguments();
        $this->setRegistrationFieldValuesToArguments();
    }

    /**
     * Saves the registration
     *
     * @Extbase\Validate("DERHANSEN\SfEventMgt\Validation\Validator\RegistrationFieldValidator", param="registration")
     * @Extbase\Validate("DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator", param="registration")
     */
    public function saveRegistrationAction(Registration $registration, Event $event): ResponseInterface
    {
        $event = $this->eventEvaluationService->evaluateForSaveRegistrationAction(
            $this->request,
            $this->settings,
            $event
        );
        if (is_null($event) && isset($this->settings['event']['errorHandling'])) {
            return $this->handleEventNotFoundError($this->settings);
        }
        $autoConfirmation = (bool)($this->settings['registration']['autoConfirmation'] ?? false) ||
            $event->getEnableAutoconfirm();
        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        [$success, $result] = $this->registrationService->checkRegistrationSuccess($event, $registration, $result);

        // Save registration if no errors
        $registrationUid = 0;
        if ($success) {
            $isWaitlistRegistration = $this->registrationService->isWaitlistRegistration(
                $event,
                $registration->getAmountOfRegistrations()
            );
            $linkValidity = (int)($this->settings['confirmation']['linkValidity'] ?? 3600);
            if ($linkValidity === 0) {
                // Use 3600 seconds as default value if not set or zero
                $linkValidity = 3600;
            }
            $confirmationUntil = new DateTime();
            $confirmationUntil->add(new DateInterval('PT' . $linkValidity . 'S'));
            $price = $this->registrationService->evaluateRegistrationPrice($event, $registration, $this->request);

            $registration->setEvent($event);
            $registration->setPid($event->getPid());
            $registration->setRegistrationDate(new DateTime());
            $registration->setConfirmationUntil($confirmationUntil);
            $registration->setLanguage($this->getCurrentLanguageCode());
            $registration->setFeUser($this->registrationService->getCurrentFeUserObject());
            $registration->setWaitlist($isWaitlistRegistration);
            $registration->setPrice($price);

            $this->registrationRepository->add($registration);

            // Persist registration, so we have an UID
            $this->persistAll();
            $registrationUid = $registration->getUid();

            if ($isWaitlistRegistration) {
                $messageType = MessageType::REGISTRATION_WAITLIST_NEW;
            } else {
                $messageType = MessageType::REGISTRATION_NEW;
            }

            $this->eventDispatcher->dispatch(new AfterRegistrationSavedEvent($registration, $this, $this->request));

            // Send notifications to user and admin if confirmation link should be sent
            if (!$autoConfirmation) {
                $this->notificationService->sendUserMessage(
                    $this->request,
                    $event,
                    $registration,
                    $this->settings,
                    $messageType
                );
                $this->notificationService->sendAdminMessage(
                    $this->request,
                    $event,
                    $registration,
                    $this->settings,
                    $messageType
                );
            }

            // Create given amount of registrations if necessary
            $modifyCreateDependingRegistrationsEvent = new ModifyCreateDependingRegistrationsEvent(
                $registration,
                ($registration->getAmountOfRegistrations() > 1),
                $this,
                $this->request
            );
            $this->eventDispatcher->dispatch($modifyCreateDependingRegistrationsEvent);
            $createDependingRegistrations = $modifyCreateDependingRegistrationsEvent->getCreateDependingRegistrations();
            if ($createDependingRegistrations) {
                $this->registrationService->createDependingRegistrations($registration);
            }

            // Flush page cache for event, since new registration has been added
            $this->eventCacheService->flushEventCache($event->getUid(), $event->getPid());
        }

        if ($autoConfirmation && $success) {
            return $this->redirect(
                'confirmRegistration',
                null,
                null,
                [
                    'reguid' => $registration->getUid(),
                    'hmac' => $this->hashService->hmac(
                        'reg-' . $registration->getUid(),
                        HashScope::RegistrationUid->value
                    ),
                ]
            );
        }

        return $this->redirect(
            'saveRegistrationResult',
            null,
            null,
            [
                'result' => $result,
                'eventuid' => $event->getUid(),
                'reguid' => $registrationUid,
                'hmac' => $this->hashService->hmac(
                    'event-' . $event->getUid() . '-reg-' . $registrationUid,
                    HashScope::SaveRegistrationResult->value
                ),
            ]
        );
    }

    /**
     * Shows the result of the saveRegistrationAction
     */
    public function saveRegistrationResultAction(int $result, int $eventuid, string $hmac): ResponseInterface
    {
        $reguid = $this->request->hasArgument('reguid') ? (int)$this->request->getArgument('reguid') : 0;

        $event = null;
        $registration = null;
        $failed = true;

        switch ($result) {
            case RegistrationResult::REGISTRATION_SUCCESSFUL:
                $messageKey = 'event.message.registrationsuccessful';
                $titleKey = 'registrationResult.title.successful';
                $failed = false;
                break;
            case RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST:
                $messageKey = 'event.message.registrationwaitlistsuccessful';
                $titleKey = 'registrationWaitlistResult.title.successful';
                $failed = false;
                break;
            case RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED:
                $messageKey = 'event.message.registrationfailedeventexpired';
                $titleKey = 'registrationResult.title.failed';
                break;
            case RegistrationResult::REGISTRATION_FAILED_EVENT_ENDED:
                $messageKey = 'event.message.registrationfailedeventended';
                $titleKey = 'registrationResult.title.failed';
                break;
            case RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS:
                $messageKey = 'event.message.registrationfailedmaxparticipants';
                $titleKey = 'registrationResult.title.failed';
                break;
            case RegistrationResult::REGISTRATION_NOT_ENABLED:
                $messageKey = 'event.message.registrationfailednotenabled';
                $titleKey = 'registrationResult.title.failed';
                break;
            case RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED:
                $messageKey = 'event.message.registrationfaileddeadlineexpired';
                $titleKey = 'registrationResult.title.failed';
                break;
            case RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES:
                $messageKey = 'event.message.registrationfailednotenoughfreeplaces';
                $titleKey = 'registrationResult.title.failed';
                break;
            case RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED:
                $messageKey = 'event.message.registrationfailedmaxamountregistrationsexceeded';
                $titleKey = 'registrationResult.title.failed';
                break;
            case RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE:
                $messageKey = 'event.message.registrationfailedemailnotunique';
                $titleKey = 'registrationResult.title.failed';
                break;
            default:
                $messageKey = '';
                $titleKey = '';
        }

        $isValidHmac = $this->hashService->validateHmac(
            'event-' . $eventuid . '-reg-' . $reguid,
            HashScope::SaveRegistrationResult->value,
            $hmac
        );
        if (!$isValidHmac) {
            $messageKey = 'event.message.registrationsuccessfulwrongeventhmac';
            $titleKey = 'registrationResult.title.failed';
        } else {
            $event = $this->eventRepository->findByUid($eventuid);
            $registration = $this->registrationRepository->findByUid($reguid);
        }

        $this->view->assignMultiple([
            'messageKey' => $messageKey,
            'titleKey' => $titleKey,
            'event' => $event,
            'registration' => $registration,
            'result' => $result,
            'failed' => $failed,
        ]);

        return $this->htmlResponse();
    }

    /**
     * Shows the verify confirmation registration view, where the user has to submit a form to finally
     * confirm the registration
     */
    public function verifyConfirmRegistrationAction(int $reguid, string $hmac): ResponseInterface
    {
        /* @var $registration Registration */
        [$failed, $registration, $messageKey, $titleKey] = $this->registrationService->checkConfirmRegistration(
            $reguid,
            $hmac
        );

        $variables = [
            'reguid' => $reguid,
            'hmac' => $hmac,
            'confirmationPossible' => $registration && !$registration->getConfirmed(),
            'failed' => $failed,
            'messageKey' => $messageKey,
            'titleKey' => $titleKey,
            'event' => $registration?->getEvent(),
            'registration' => $registration,
            'settings' => $this->settings,
        ];

        $this->view->assignMultiple($variables);

        return $this->htmlResponse();
    }

    /**
     * Confirms the registration if possible and sends emails to admin and user
     */
    public function confirmRegistrationAction(int $reguid, string $hmac): ResponseInterface
    {
        $event = null;

        /* @var $registration Registration */
        [$failed, $registration, $messageKey, $titleKey] = $this->registrationService->checkConfirmRegistration(
            $reguid,
            $hmac
        );

        if ($failed === false) {
            $registration->setConfirmed(true);
            $event = $registration->getEvent();
            $this->registrationRepository->update($registration);

            $this->eventDispatcher->dispatch(new AfterRegistrationConfirmedEvent($registration, $this, $this->request));

            $messageType = MessageType::REGISTRATION_CONFIRMED;
            if ($registration->getWaitlist()) {
                $messageType = MessageType::REGISTRATION_WAITLIST_CONFIRMED;
            }

            // Send notifications to user and admin
            $this->notificationService->sendUserMessage(
                $this->request,
                $registration->getEvent(),
                $registration,
                $this->settings,
                $messageType
            );
            $this->notificationService->sendAdminMessage(
                $this->request,
                $registration->getEvent(),
                $registration,
                $this->settings,
                $messageType
            );

            // Confirm registrations depending on main registration if necessary
            if ($registration->getAmountOfRegistrations() > 1) {
                $this->registrationService->confirmDependingRegistrations($registration);
            }
        }

        // Redirect to payment provider if payment/redirect is enabled.
        // Skip if the registration is a waitlist registration, since it is not sure, if the user will participate.
        $paymentPid = (int)($this->settings['paymentPid'] ?? 0);
        $paymentRedirectResponse = null;
        $processRedirect = !$failed &&
            $paymentPid > 0 &&
            $registration &&
            !$registration->getWaitlist() &&
            $this->registrationService->redirectPaymentEnabled($registration);
        if ($processRedirect) {
            $paymentRedirectResponse = $this->getRedirectToPaymentResponse($paymentPid, $registration);
        }

        if ($paymentRedirectResponse instanceof ResponseInterface) {
            return $paymentRedirectResponse;
        }

        $modifyConfirmRegistrationViewVariablesEvent = new ModifyConfirmRegistrationViewVariablesEvent(
            [
                'failed' => $failed,
                'messageKey' => $messageKey,
                'titleKey' => $titleKey,
                'event' => $event,
                'registration' => $registration,
                'settings' => $this->settings,
            ],
            $this,
            $this->request
        );
        $this->eventDispatcher->dispatch($modifyConfirmRegistrationViewVariablesEvent);
        $variables = $modifyConfirmRegistrationViewVariablesEvent->getVariables();
        $this->view->assignMultiple($variables);

        return $this->htmlResponse();
    }

    /**
     * Returns a response object to the given payment PID. Extension authors can use ProcessRedirectToPaymentEvent
     * PSR-14 event to intercept the redirect response.
     */
    private function getRedirectToPaymentResponse(int $paymentPid, Registration $registration): ?ResponseInterface
    {
        $processRedirectToPaymentEvent = new ProcessRedirectToPaymentEvent($registration, $this, $this->request);
        $this->eventDispatcher->dispatch($processRedirectToPaymentEvent);
        if ($processRedirectToPaymentEvent->getProcessRedirect()) {
            $this->uriBuilder->reset()
                ->setTargetPageUid($paymentPid);
            $uri = $this->uriBuilder->uriFor(
                'redirect',
                [
                    'registration' => $registration,
                    'hmac' => $this->hashService->hmac(
                        'redirectAction-' . $registration->getUid(),
                        HashScope::PaymentAction->value
                    ),
                ],
                'Payment',
                'sfeventmgt',
                'Pipayment'
            );
            return $this->redirectToUri($uri);
        }

        return null;
    }

    /**
     * Shows the verify cancel registration view, where the user has to submit a form to finally cancel the registration
     */
    public function verifyCancelRegistrationAction(int $reguid, string $hmac): ResponseInterface
    {
        /* @var $registration Registration */
        [$failed, $registration, $messageKey, $titleKey] = $this->registrationService->checkCancelRegistration(
            $reguid,
            $hmac
        );

        $variables = [
            'reguid' => $reguid,
            'hmac' => $hmac,
            'cancellationPossible' => !$failed,
            'failed' => $failed,
            'messageKey' => $messageKey,
            'titleKey' => $titleKey,
            'event' => $registration?->getEvent(),
            'registration' => $registration,
            'settings' => $this->settings,
        ];

        $this->view->assignMultiple($variables);

        return $this->htmlResponse();
    }

    /**
     * Cancels the registration if possible and sends emails to admin and user
     */
    public function cancelRegistrationAction(int $reguid, string $hmac): ResponseInterface
    {
        $event = null;

        /* @var $registration Registration */
        [$failed, $registration, $messageKey, $titleKey] = $this->registrationService->checkCancelRegistration(
            $reguid,
            $hmac
        );

        if ($failed === false) {
            $event = $registration->getEvent();

            // Send notifications (must run before cancelling the registration)
            $this->notificationService->sendUserMessage(
                $this->request,
                $registration->getEvent(),
                $registration,
                $this->settings,
                MessageType::REGISTRATION_CANCELLED
            );
            $this->notificationService->sendAdminMessage(
                $this->request,
                $registration->getEvent(),
                $registration,
                $this->settings,
                MessageType::REGISTRATION_CANCELLED
            );

            // First cancel depending registrations
            $processCancelDependingRegistrations = new ProcessCancelDependingRegistrationsEvent(
                $registration,
                $registration->getAmountOfRegistrations() > 1,
                $this->request
            );
            $this->eventDispatcher->dispatch($processCancelDependingRegistrations);
            if ($processCancelDependingRegistrations->getProcessCancellation()) {
                $this->registrationService->cancelDependingRegistrations($registration);
            }

            // Finally cancel registration
            $this->registrationRepository->remove($registration);

            // Persist changes, so following functions can work with $event properties (e.g. amount of registrations)
            $this->persistAll();

            $afterRegistrationCancelledEvent = new AfterRegistrationCancelledEvent(
                $registration,
                $this,
                $this->request
            );
            $this->eventDispatcher->dispatch($afterRegistrationCancelledEvent);

            // Dispatch event, so waitlist registrations can be moved up and default move up process can be stopped
            $waitlistMoveUpEvent = new WaitlistMoveUpEvent($event, $this, $this->request, true);
            $this->eventDispatcher->dispatch($waitlistMoveUpEvent);

            // Move up waitlist registrations if configured on event basis and if not disabled by $waitlistMoveUpEvent
            if ($waitlistMoveUpEvent->getProcessDefaultMoveUp()) {
                $this->registrationService->moveUpWaitlistRegistrations($this->request, $event, $this->settings);
            }

            // Flush page cache for event, since amount of registrations has changed
            $this->eventCacheService->flushEventCache($event->getUid(), $event->getPid());
        }

        $modifyCancelRegistrationViewVariablesEvent = new ModifyCancelRegistrationViewVariablesEvent(
            [
                'failed' => $failed,
                'messageKey' => $messageKey,
                'titleKey' => $titleKey,
                'event' => $event,
                'settings' => $this->settings,
            ],
            $this,
            $this->request
        );
        $this->eventDispatcher->dispatch($modifyCancelRegistrationViewVariablesEvent);
        $variables = $modifyCancelRegistrationViewVariablesEvent->getVariables();
        $this->view->assignMultiple($variables);

        return $this->htmlResponse();
    }

    /**
     * Set date format for field startDate and endDate
     */
    public function initializeSearchAction(): void
    {
        if ($this->settings !== [] && ($this->settings['search']['dateFormat'] ?? false)) {
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
        if ($this->arguments->hasArgument('searchDemand')) {
            $propertyMappingConfiguration = $this->arguments->getArgument('searchDemand')
                ->getPropertyMappingConfiguration();
            $propertyMappingConfiguration->allowAllProperties();
            $propertyMappingConfiguration->setTypeConverterOption(
                PersistentObjectConverter::class,
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
                true
            );
        }
    }

    /**
     * Search view
     */
    public function searchAction(?SearchDemand $searchDemand = null, array $overwriteDemand = []): ResponseInterface
    {
        $eventDemand = EventDemand::createFromSettings($this->settings);
        $eventDemand->setSearchDemand($searchDemand);
        $foreignRecordDemand = ForeignRecordDemand::createFromSettings($this->settings);
        $categoryDemand = CategoryDemand::createFromSettings($this->settings);

        if ($searchDemand !== null) {
            $searchDemand->setFields($this->settings['search']['fields'] ?? '');

            $adjustTime = (bool)($this->settings['search']['adjustTime'] ?? false);
            if ($adjustTime && $searchDemand->getStartDate() !== null) {
                $searchDemand->getStartDate()->setTime(0, 0);
            }

            if ($adjustTime && $searchDemand->getEndDate() !== null) {
                $searchDemand->getEndDate()->setTime(23, 59, 59);
            }
        }

        if ($this->isOverwriteDemand($overwriteDemand)) {
            $eventDemand = $this->overwriteEventDemandObject($eventDemand, $overwriteDemand);
        }

        $categories = $this->categoryRepository->findDemanded($categoryDemand);
        $locations = $this->locationRepository->findDemanded($foreignRecordDemand);
        $organisators = $this->organisatorRepository->findDemanded($foreignRecordDemand);
        $speakers = $this->speakerRepository->findDemanded($foreignRecordDemand);
        $events = $this->eventRepository->findDemanded($eventDemand);

        $modifySearchViewVariablesEvent = new ModifySearchViewVariablesEvent(
            [
                'events' => $events,
                'categories' => $categories,
                'locations' => $locations,
                'organisators' => $organisators,
                'speakers' => $speakers,
                'searchDemand' => $searchDemand,
                'overwriteDemand' => $overwriteDemand,
                'settings' => $this->settings,
            ],
            $this,
            $this->request,
        );
        $this->eventDispatcher->dispatch($modifySearchViewVariablesEvent);
        $variables = $modifySearchViewVariablesEvent->getVariables();
        $this->view->assignMultiple($variables);

        return $this->htmlResponse();
    }

    /**
     * Returns if a demand object can be overwritten with the given overwriteDemand array
     */
    protected function isOverwriteDemand(array $overwriteDemand): bool
    {
        return (int)($this->settings['disableOverrideDemand'] ?? 0) !== 1 && $overwriteDemand !== [];
    }

    /**
     * Calls persistAll() of the persistenceManager
     */
    protected function persistAll(): void
    {
        GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();
    }

    /**
     * Returns the language code of the current language
     */
    protected function getCurrentLanguageCode(): string
    {
        if ($this->request->getAttribute('language') instanceof SiteLanguage) {
            /** @var SiteLanguage $siteLanguage */
            $siteLanguage = $this->request->getAttribute('language');
            return $siteLanguage->getLocale()->getLanguageCode();
        }

        return '';
    }
}
