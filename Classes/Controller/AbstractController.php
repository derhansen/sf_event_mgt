<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Controller;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Pagination\NumberedPagination;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * EventController
 */
abstract class AbstractController extends ActionController
{
    /**
     * Properties in this array will be ignored by overwriteDemandObject()
     *
     * @var array
     */
    protected $ignoredSettingsForOverwriteDemand = ['storagepage', 'orderfieldallowed'];

    /**
     * EventRepository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository
     */
    protected $eventRepository;

    /**
     * Registration repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     */
    protected $registrationRepository;

    /**
     * Category repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Location repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository
     */
    protected $locationRepository;

    /**
     * Organisator repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository
     */
    protected $organisatorRepository;

    /**
     * Speaker repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\SpeakerRepository
     */
    protected $speakerRepository;

    /**
     * Notification Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\NotificationService
     */
    protected $notificationService;

    /**
     * ICalendar Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\ICalendarService
     */
    protected $icalendarService;

    /**
     * Hash Service
     *
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     */
    protected $hashService;

    /**
     * RegistrationService
     *
     * @var \DERHANSEN\SfEventMgt\Service\RegistrationService
     */
    protected $registrationService;

    /**
     * CalendarService
     *
     * @var \DERHANSEN\SfEventMgt\Service\CalendarService
     */
    protected $calendarService;

    /**
     * PaymentMethodService
     *
     * @var \DERHANSEN\SfEventMgt\Service\PaymentService
     */
    protected $paymentService;

    /**
     * FieldRepository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\Registration\FieldRepository
     */
    protected $fieldRepository;

    /**
     * DI for $calendarService
     *
     * @param \DERHANSEN\SfEventMgt\Service\CalendarService $calendarService
     */
    public function injectCalendarService(\DERHANSEN\SfEventMgt\Service\CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * DI for $categoryRepository
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(
        \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * DI for $eventRepository
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository $eventRepository
     */
    public function injectEventRepository(\DERHANSEN\SfEventMgt\Domain\Repository\EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * DI for $hashService
     *
     * @param \TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService
     */
    public function injectHashService(\TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    /**
     * DI for $icalendarService
     *
     * @param \DERHANSEN\SfEventMgt\Service\ICalendarService $icalendarService
     */
    public function injectIcalendarService(\DERHANSEN\SfEventMgt\Service\ICalendarService $icalendarService)
    {
        $this->icalendarService = $icalendarService;
    }

    /**
     * DI for $locationRepository
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository $locationRepository
     */
    public function injectLocationRepository(
        \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository $locationRepository
    ) {
        $this->locationRepository = $locationRepository;
    }

    /**
     * DI for $notificationService
     *
     * @param \DERHANSEN\SfEventMgt\Service\NotificationService $notificationService
     */
    public function injectNotificationService(\DERHANSEN\SfEventMgt\Service\NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * DI for $organisatorRepository
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository $organisatorRepository
     */
    public function injectOrganisatorRepository(
        \DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository $organisatorRepository
    ) {
        $this->organisatorRepository = $organisatorRepository;
    }

    /**
     * DI for $speakerRepository
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\SpeakerRepository $speakerRepository
     */
    public function injectSpeakerRepository(
        \DERHANSEN\SfEventMgt\Domain\Repository\SpeakerRepository $speakerRepository
    ) {
        $this->speakerRepository = $speakerRepository;
    }

    /**
     * DI for $paymentService
     *
     * @param \DERHANSEN\SfEventMgt\Service\PaymentService $paymentService
     */
    public function injectPaymentService(\DERHANSEN\SfEventMgt\Service\PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * DI for $registrationRepository
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository $registrationRepository
     */
    public function injectRegistrationRepository(
        \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository $registrationRepository
    ) {
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * DI for $registrationService
     *
     * @param \DERHANSEN\SfEventMgt\Service\RegistrationService $registrationService
     */
    public function injectRegistrationService(\DERHANSEN\SfEventMgt\Service\RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * DI for $fieldRepository
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Repository\Registration\FieldRepository $fieldRepository
     */
    public function injectFieldRepository(
        \DERHANSEN\SfEventMgt\Domain\Repository\Registration\FieldRepository $fieldRepository
    ) {
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * Returns an array with variables for the pagination. An array with pagination settings should be passed.
     * Applies default values if settings are not available:
     * - pagination disabled
     * - itemsPerPage = 10
     * - maxNumPages = 10
     *
     * @param QueryResultInterface $events
     * @param array $settings
     * @return array
     */
    protected function getPagination(QueryResultInterface $events, array $settings): array
    {
        $pagination = [];
        $currentPage = $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1;
        if (($settings['enablePagination'] ?? false) && (int)$settings['itemsPerPage'] > 0) {
            $paginator = new QueryResultPaginator($events, $currentPage, (int)($settings['itemsPerPage'] ?? 10));
            $pagination = new NumberedPagination($paginator, (int)($settings['maxNumPages'] ?? 10));
            $pagination = [
                'paginator' => $paginator,
                'pagination' => $pagination,
            ];
        }

        return $pagination;
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'] ?: null;
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
}
