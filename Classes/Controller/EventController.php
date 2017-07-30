<?php
namespace DERHANSEN\SfEventMgt\Controller;

/*
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

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use DERHANSEN\SfEventMgt\Utility\Page;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

/**
 * EventController
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Configuration Manager
     *
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * EventRepository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository
     * @inject
     */
    protected $eventRepository = null;

    /**
     * Registration repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     * @inject
     */
    protected $registrationRepository = null;

    /**
     * Category repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository = null;

    /**
     * Location repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository
     * @inject
     */
    protected $locationRepository = null;

    /**
     * Notification Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\NotificationService
     * @inject
     */
    protected $notificationService = null;

    /**
     * ICalendar Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\ICalendarService
     * @inject
     */
    protected $icalendarService = null;

    /**
     * Hash Service
     *
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     * @inject
     */
    protected $hashService;

    /**
     * RegistrationService
     *
     * @var \DERHANSEN\SfEventMgt\Service\RegistrationService
     * @inject
     */
    protected $registrationService = null;

    /**
     * CalendarService
     *
     * @var \DERHANSEN\SfEventMgt\Service\CalendarService
     * @inject
     */
    protected $calendarService = null;

    /**
     * UtilityService
     *
     * @var \DERHANSEN\SfEventMgt\Service\UtilityService
     * @inject
     */
    protected $utilityService = null;

    /**
     * PaymentMethodService
     *
     * @var \DERHANSEN\SfEventMgt\Service\PaymentService
     * @inject
     */
    protected $paymentService = null;
    
    /**
     * Properties in this array will be ignored by overwriteDemandObject()
     *
     * @var array
     */
    protected $ignoredSettingsForOverwriteDemand = ['storagepage', 'orderfieldallowed'];

    /**
     * Creates an event demand object with the given settings
     *
     * @param array $settings The settings
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand
     */
    public function createEventDemandObjectFromSettings(array $settings)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
        $demand->setDisplayMode($settings['displayMode']);
        $demand->setStoragePage(Page::extendPidListByChildren($settings['storagePage'], $settings['recursive']));
        $demand->setCategory($settings['category']);
        $demand->setIncludeSubcategories($settings['includeSubcategories']);
        $demand->setTopEventRestriction((int)$settings['topEventRestriction']);
        $demand->setOrderField($settings['orderField']);
        $demand->setOrderFieldAllowed($settings['orderFieldAllowed']);
        $demand->setOrderDirection($settings['orderDirection']);
        $demand->setQueryLimit($settings['queryLimit']);
        $demand->setLocation($settings['location']);
        return $demand;
    }

    /**
     * Creates a foreign record demand object with the given settings
     *
     * @param array $settings The settings
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand
     */
    public function createForeignRecordDemandObjectFromSettings(array $settings)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\ForeignRecordDemand');
        $demand->setStoragePage(Page::extendPidListByChildren($settings['storagePage'], $settings['recursive']));
        $demand->setRestrictForeignRecordsToStoragePage((bool)$settings['restrictForeignRecordsToStoragePage']);
        return $demand;
    }

    /**
     * Creates a category demand object with the given settings
     *
     * @param array $settings The settings
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand
     */
    public function createCategoryDemandObjectFromSettings(array $settings)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand $demand */
        $demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\CategoryDemand');
        $demand->setStoragePage(Page::extendPidListByChildren($settings['storagePage'], $settings['recursive']));
        $demand->setRestrictToStoragePage((bool)$settings['restrictForeignRecordsToStoragePage']);
        $demand->setCategories($settings['categoryMenu']['categories']);
        $demand->setIncludeSubcategories($settings['categoryMenu']['includeSubcategories']);
        return $demand;
    }

    /**
     * Overwrites a given demand object by an propertyName =>  $propertyValue array
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand Demand
     * @param array $overwriteDemand OwerwriteDemand
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand
     */
    protected function overwriteEventDemandObject(EventDemand $demand, array $overwriteDemand)
    {
        foreach ($this->ignoredSettingsForOverwriteDemand as $property) {
            unset($overwriteDemand[$property]);
        }

        foreach ($overwriteDemand as $propertyName => $propertyValue) {
            if (in_array(strtolower($propertyName), $this->ignoredSettingsForOverwriteDemand, true)) {
                continue;
            }
            \TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
        }
        return $demand;
    }

    /**
     * Hook into request processing and catch exceptions
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @throws \Exception
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        try {
            parent::processRequest($request, $response);
        } catch (\Exception $exception) {
            $this->handleKnownExceptionsElseThrowAgain($exception);
        }
    }

    /**
     * Handle known exceptions
     *
     * @param \Exception $exception
     * @throws \Exception
     */
    private function handleKnownExceptionsElseThrowAgain(\Exception $exception)
    {
        $previousException = $exception->getPrevious();
        if ($this->actionMethodName === 'detailAction'
            && $previousException instanceof \TYPO3\CMS\Extbase\Property\Exception
        ) {
            $this->handleEventNotFoundError($this->settings);
        } else {
            throw $exception;
        }
    }

    /**
     * Initialize list action and set format
     *
     * @return void
     */
    public function initializeListAction()
    {
        if (isset($this->settings['list']['format'])) {
            $this->request->setFormat($this->settings['list']['format']);
        }
    }

    /**
     * List view
     *
     * @param array $overwriteDemand OverwriteDemand
     *
     * @return void
     */
    public function listAction(array $overwriteDemand = [])
    {
        $eventDemand = $this->createEventDemandObjectFromSettings($this->settings);
        $foreignRecordDemand = $this->createForeignRecordDemandObjectFromSettings($this->settings);
        $categoryDemand = $this->createCategoryDemandObjectFromSettings($this->settings);
        if ($this->isOverwriteDemand($overwriteDemand)) {
            $eventDemand = $this->overwriteEventDemandObject($eventDemand, $overwriteDemand);
        }
        $events = $this->eventRepository->findDemanded($eventDemand);
        $categories = $this->categoryRepository->findDemanded($categoryDemand);
        $locations = $this->locationRepository->findDemanded($foreignRecordDemand);
        $this->view->assignMultiple([
            'events' => $events,
            'categories' => $categories,
            'locations' => $locations,
            'overwriteDemand' => $overwriteDemand,
            'eventDemand' => $eventDemand
        ]);
    }

    /**
     * Calendar view
     *
     * @param array $overwriteDemand OverwriteDemand
     *
     * @return void
     */
    public function calendarAction(array $overwriteDemand = [])
    {
        $eventDemand = $this->createEventDemandObjectFromSettings($this->settings);
        $foreignRecordDemand = $this->createForeignRecordDemandObjectFromSettings($this->settings);
        $categoryDemand = $this->createCategoryDemandObjectFromSettings($this->settings);
        if ($this->isOverwriteDemand($overwriteDemand)) {
            $eventDemand = $this->overwriteEventDemandObject($eventDemand, $overwriteDemand);
        }

        // Set month/year to demand if not given
        if (!$eventDemand->getMonth()) {
            $currentMonth = date('n');
            $eventDemand->setMonth($currentMonth);
        } else {
            $currentMonth = $eventDemand->getMonth();
        }
        if (!$eventDemand->getYear()) {
            $currentYear = date('Y');
            $eventDemand->setYear($currentYear);
        } else {
            $currentYear = $eventDemand->getYear();
        }

        // Set demand from calendar date range instead of month / year
        if ((bool)$this->settings['calendar']['includeEventsForEveryDayOfAllCalendarWeeks']) {
            $eventDemand = $this->changeEventDemandToFullMonthDateRange($eventDemand);
        }

        $events = $this->eventRepository->findDemanded($eventDemand);
        $weeks = $this->calendarService->getCalendarArray(
            $currentMonth,
            $currentYear,
            strtotime('today midnight'),
            (int)$this->settings['calendar']['firstDayOfWeek'],
            $events
        );

        $values = [
            'weeks' => $weeks,
            'categories' => $this->categoryRepository->findDemanded($categoryDemand),
            'locations' => $this->locationRepository->findDemanded($foreignRecordDemand),
            'eventDemand' => $eventDemand,
            'overwriteDemand' => $overwriteDemand,
            'currentPageId' => $GLOBALS['TSFE']->id,
            'firstDayOfMonth' => \DateTime::createFromFormat('d.m.Y', sprintf('1.%s.%s', $currentMonth, $currentMonth)),
            'previousMonthConfig' => $this->calendarService->getDateConfig($currentMonth, $currentYear, '-1 month'),
            'nextMonthConfig' => $this->calendarService->getDateConfig($currentMonth, $currentYear, '+1 month')
        ];

        $this->view->assignMultiple($values);
    }

    /**
     * Changes the given event demand object to select a date range for a calendar month including days of the previous
     * month for the first week and they days for the next month for the last week
     *
     * @param EventDemand $eventDemand
     * @return EventDemand
     */
    protected function changeEventDemandToFullMonthDateRange(EventDemand $eventDemand)
    {
        $calendarDateRange = $this->calendarService->getCalendarDateRange(
            $eventDemand->getMonth(),
            $eventDemand->getYear(),
            $this->settings['calendar']['firstDayOfWeek']
        );

        $eventDemand->setMonth(0);
        $eventDemand->setYear(0);

        $startDate = new \DateTime();
        $startDate->setTimestamp($calendarDateRange['firstDayOfCalendar']);
        $endDate = new \DateTime();
        $endDate->setTimestamp($calendarDateRange['lastDayOfCalendar']);
        $endDate->setTime(23, 59, 59);

        $searchDemand = new SearchDemand();
        $searchDemand->setStartDate($startDate);
        $searchDemand->setEndDate($endDate);
        $eventDemand->setSearchDemand($searchDemand);
        return $eventDemand;
    }

    /**
     * Detail view for an event
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     */
    public function detailAction(Event $event = null)
    {
        if (is_null($event) && isset($this->settings['detail']['errorHandling'])) {
            $this->handleEventNotFoundError($this->settings);
        }
        $this->view->assign('event', $event);
    }

    /**
     * Error handling if event is not found
     *
     * @param array $settings
     * @return string
     */
    protected function handleEventNotFoundError($settings)
    {
        if (empty($settings['detail']['errorHandling'])) {
            return;
        }

        switch ($settings['detail']['errorHandling']) {
            case 'redirectToListView':
                $listPid = (int)$settings['listPid'] > 0 ? (int)$settings['listPid'] : 1;
                $this->redirect('list', null, null, null, $listPid);
                break;
            case 'pageNotFoundHandler':
                $GLOBALS['TSFE']->pageNotFoundAndExit('Event not found.');
                break;
            default:
        }
    }

    /**
     * Initiates the iCalendar download for the given event
     *
     * @param Event $event The event
     *
     * @return bool
     */
    public function icalDownloadAction(Event $event)
    {
        $this->icalendarService->downloadiCalendarFile($event);
        return false;
    }

    /**
     * Registration view for an event
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     *
     * @return void
     */
    public function registrationAction(Event $event)
    {
        if ($event->getRestrictPaymentMethods()) {
            $paymentMethods = $this->paymentService->getRestrictedPaymentMethods($event);
        } else {
            $paymentMethods = $this->paymentService->getPaymentMethods();
        }

        $this->view->assignMultiple([
            'event' => $event,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Set date format for field dateOfBirth
     *
     * @return void
     */
    public function initializeSaveRegistrationAction()
    {
        $this->arguments->getArgument('registration')
            ->getPropertyMappingConfiguration()->forProperty('dateOfBirth')
            ->setTypeConverterOption(
                'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                $this->settings['registration']['formatDateOfBirth']
            );
    }

    /**
     * Saves the registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @validate $registration \DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator
     *
     * @return void
     */
    public function saveRegistrationAction(Registration $registration, Event $event)
    {
        $autoConfirmation = (bool)$this->settings['registration']['autoConfirmation'] || $event->getEnableAutoconfirm();
        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        $success = $this->registrationService->checkRegistrationSuccess($event, $registration, $result);

        // Save registration if no errors
        if ($success) {
            $isWaitlistRegistration = $this->registrationService->isWaitlistRegistration(
                $event,
                $registration->getAmountOfRegistrations()
            );
            $linkValidity = (int)$this->settings['confirmation']['linkValidity'];
            if ($linkValidity === 0) {
                // Use 3600 seconds as default value if not set
                $linkValidity = 3600;
            }
            $confirmationUntil = new \DateTime();
            $confirmationUntil->add(new \DateInterval('PT' . $linkValidity . 'S'));

            $registration->setEvent($event);
            $registration->setPid($event->getPid());
            $registration->setConfirmationUntil($confirmationUntil);
            $registration->setLanguage($GLOBALS['TSFE']->config['config']['language']);
            $registration->setFeUser($this->registrationService->getCurrentFeUserObject());
            $registration->setWaitlist($isWaitlistRegistration);
            $registration->_setProperty('_languageUid', $GLOBALS['TSFE']->sys_language_uid);
            $this->registrationRepository->add($registration);

            // Persist registration, so we have an UID
            $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

            // Add new registration (or waitlist registration) to event
            if ($isWaitlistRegistration) {
                $event->addRegistrationWaitlist($registration);
                $messageType = MessageType::REGISTRATION_WAITLIST_NEW;
            } else {
                $event->addRegistration($registration);
                $messageType = MessageType::REGISTRATION_NEW;
            }
            $this->eventRepository->update($event);

            // Send notifications to user and admin if confirmation link should be sent
            if (!$autoConfirmation) {
                $this->notificationService->sendUserMessage(
                    $event,
                    $registration,
                    $this->settings,
                    $messageType
                );
                $this->notificationService->sendAdminMessage(
                    $event,
                    $registration,
                    $this->settings,
                    $messageType
                );
            }

            // Create given amount of registrations if necessary
            if ($registration->getAmountOfRegistrations() > 1) {
                $this->registrationService->createDependingRegistrations($registration);
            }

            // Clear cache for configured pages
            $this->utilityService->clearCacheForConfiguredUids($this->settings);
        }

        if ($autoConfirmation && $success) {
            $this->redirect(
                'confirmRegistration',
                null,
                null,
                [
                    'reguid' => $registration->getUid(),
                    'hmac' => $this->hashService->generateHmac('reg-' . $registration->getUid())
                ]
            );
        } else {
            $this->redirect(
                'saveRegistrationResult',
                null,
                null,
                [
                    'result' => $result,
                    'eventuid' => $event->getUid(),
                    'hmac' => $this->hashService->generateHmac('event-' . $event->getUid())
                ]
            );
        }
    }

    /**
     * Shows the result of the saveRegistrationAction
     *
     * @param int $result Result
     * @param int $eventuid
     * @param string $hmac
     *
     * @return void
     */
    public function saveRegistrationResultAction($result, $eventuid, $hmac)
    {
        $event = null;

        switch ($result) {
            case RegistrationResult::REGISTRATION_SUCCESSFUL:
                $messageKey = 'event.message.registrationsuccessful';
                $titleKey = 'registrationResult.title.successful';
                break;
            case RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST:
                $messageKey = 'event.message.registrationwaitlistsuccessful';
                $titleKey = 'registrationWaitlistResult.title.successful';
                break;
            case RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED:
                $messageKey = 'event.message.registrationfailedeventexpired';
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

        if (!$this->hashService->validateHmac('event-' . $eventuid, $hmac)) {
            $messageKey = 'event.message.registrationsuccessfulwrongeventhmac';
            $titleKey = 'registrationResult.title.failed';
        } else {
            $event = $this->eventRepository->findByUid((int)$eventuid);
        }

        $this->view->assignMultiple([
            'messageKey' => $messageKey,
            'titleKey' => $titleKey,
            'event' => $event,
        ]);
    }

    /**
     * Confirms the registration if possible and sends e-mails to admin and user
     *
     * @param int $reguid UID of registration
     * @param string $hmac HMAC for parameters
     *
     * @return void
     */
    public function confirmRegistrationAction($reguid, $hmac)
    {
        $event = null;

        /* @var $registration Registration */
        list($failed, $registration, $messageKey, $titleKey) = $this->registrationService->checkConfirmRegistration(
            $reguid,
            $hmac
        );

        if ($failed === false) {
            $registration->setConfirmed(true);
            $event = $registration->getEvent();
            $this->registrationRepository->update($registration);

            $messageType = MessageType::REGISTRATION_CONFIRMED;
            if ($registration->getWaitlist()) {
                $messageType = MessageType::REGISTRATION_WAITLIST_CONFIRMED;
            }

            // Send notifications to user and admin
            $this->notificationService->sendUserMessage(
                $registration->getEvent(),
                $registration,
                $this->settings,
                $messageType
            );
            $this->notificationService->sendAdminMessage(
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

        // Redirect to payment provider if payment/redirect is enabled
        $paymentPid = (int)$this->settings['paymentPid'];
        if (!$failed && $paymentPid > 0 && $this->registrationService->redirectPaymentEnabled($registration)) {
            $this->uriBuilder->reset()
                ->setTargetPageUid($paymentPid)
                ->setUseCacheHash(false);
            $uri =  $this->uriBuilder->uriFor(
                'redirect',
                [
                    'registration' => $registration,
                    'hmac' => $this->hashService->generateHmac('redirectAction-' . $registration->getUid())
                ],
                'Payment',
                'sfeventmgt',
                'Pipayment'
            );
            $this->redirectToUri($uri);
        }

        $this->view->assignMultiple([
            'messageKey' => $messageKey,
            'titleKey' => $titleKey,
            'event' => $event,
        ]);
    }

    /**
     * Cancels the registration if possible and sends e-mails to admin and user
     *
     * @param int $reguid UID of registration
     * @param string $hmac HMAC for parameters
     *
     * @return void
     */
    public function cancelRegistrationAction($reguid, $hmac)
    {
        $event = null;

        /* @var $registration Registration */
        list($failed, $registration, $messageKey, $titleKey) = $this->registrationService->checkCancelRegistration($reguid, $hmac);

        if ($failed === false) {
            $event = $registration->getEvent();

            // Send notifications (must run before cancelling the registration)
            $this->notificationService->sendUserMessage(
                $registration->getEvent(),
                $registration,
                $this->settings,
                MessageType::REGISTRATION_CANCELLED
            );
            $this->notificationService->sendAdminMessage(
                $registration->getEvent(),
                $registration,
                $this->settings,
                MessageType::REGISTRATION_CANCELLED
            );

            // First cancel depending registrations
            if ($registration->getAmountOfRegistrations() > 1) {
                $this->registrationService->cancelDependingRegistrations($registration);
            }

            // Finally cancel registration
            $this->registrationRepository->remove($registration);

            // Clear cache for configured pages
            $this->utilityService->clearCacheForConfiguredUids($this->settings);
        }

        $this->view->assignMultiple([
            'messageKey' => $messageKey,
            'titleKey' => $titleKey,
            'event' => $event,
        ]);
    }

    /**
     * Set date format for field startDate and endDate
     *
     * @return void
     */
    public function initializeSearchAction()
    {
        if ($this->settings !== null && $this->settings['search']['dateFormat']) {
            $this->arguments->getArgument('searchDemand')
                ->getPropertyMappingConfiguration()->forProperty('startDate')
                ->setTypeConverterOption(
                    'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                    DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                    $this->settings['search']['dateFormat']
                );
            $this->arguments->getArgument('searchDemand')
                ->getPropertyMappingConfiguration()->forProperty('endDate')
                ->setTypeConverterOption(
                    'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                    DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                    $this->settings['search']['dateFormat']
                );
        }
    }

    /**
     * Search view
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand $searchDemand SearchDemand
     * @param array $overwriteDemand OverwriteDemand
     *
     * @return void
     */
    public function searchAction(SearchDemand $searchDemand = null, array $overwriteDemand = [])
    {
        $eventDemand = $this->createEventDemandObjectFromSettings($this->settings);
        $eventDemand->setSearchDemand($searchDemand);
        $foreignRecordDemand = $this->createForeignRecordDemandObjectFromSettings($this->settings);
        $categoryDemand = $this->createCategoryDemandObjectFromSettings($this->settings);

        if ($searchDemand !== null) {
            $searchDemand->setFields($this->settings['search']['fields']);

            if ($this->settings['search']['adjustTime'] && $searchDemand->getStartDate() !== null) {
                $searchDemand->getStartDate()->setTime(0, 0, 0);
            }

            if ($this->settings['search']['adjustTime'] && $searchDemand->getEndDate() !== null) {
                $searchDemand->getEndDate()->setTime(23, 59, 59);
            }
        }

        if ($this->isOverwriteDemand($overwriteDemand)) {
            $eventDemand = $this->overwriteEventDemandObject($eventDemand, $overwriteDemand);
        }

        $categories = $this->categoryRepository->findDemanded($categoryDemand);
        $locations = $this->locationRepository->findDemanded($foreignRecordDemand);

        $events = $this->eventRepository->findDemanded($eventDemand);

        $this->view->assignMultiple([
            'events' => $events,
            'categories' => $categories,
            'locations' => $locations,
            'searchDemand' => $searchDemand,
            'overwriteDemand' => $overwriteDemand,
        ]);
    }

    /**
     * Returns if a demand object can be overwritten with the given overwriteDemand array
     *
     * @param array $overwriteDemand
     * @return bool
     */
    protected function isOverwriteDemand($overwriteDemand)
    {
        return $this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== [];
    }

}
