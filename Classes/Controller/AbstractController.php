<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Controller;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\Registration\FieldRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\SpeakerRepository;
use DERHANSEN\SfEventMgt\Service\CalendarService;
use DERHANSEN\SfEventMgt\Service\ICalendarService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\PaymentService;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * EventController
 *
 * @author Torben Hansen <derhansen@gmail.com>
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
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * Registration repository
     *
     * @var RegistrationRepository
     */
    protected $registrationRepository;

    /**
     * Category repository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Location repository
     *
     * @var LocationRepository
     */
    protected $locationRepository;

    /**
     * Organisator repository
     *
     * @var OrganisatorRepository
     */
    protected $organisatorRepository;

    /**
     * Speaker repository
     *
     * @var SpeakerRepository
     */
    protected $speakerRepository;

    /**
     * Notification Service
     *
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * ICalendar Service
     *
     * @var ICalendarService
     */
    protected $icalendarService;

    /**
     * Hash Service
     *
     * @var HashService
     */
    protected $hashService;

    /**
     * RegistrationService
     *
     * @var RegistrationService
     */
    protected $registrationService;

    /**
     * CalendarService
     *
     * @var CalendarService
     */
    protected $calendarService;

    /**
     * PaymentMethodService
     *
     * @var PaymentService
     */
    protected $paymentService;

    /**
     * FieldRepository
     *
     * @var FieldRepository
     */
    protected $fieldRepository;

    /**
     * DI for $calendarService
     *
     * @param CalendarService $calendarService
     */
    public function injectCalendarService(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * DI for $categoryRepository
     *
     * @param CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * DI for $eventRepository
     *
     * @param EventRepository $eventRepository
     */
    public function injectEventRepository(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * DI for $hashService
     *
     * @param HashService $hashService
     */
    public function injectHashService(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    /**
     * DI for $icalendarService
     *
     * @param ICalendarService $icalendarService
     */
    public function injectIcalendarService(ICalendarService $icalendarService)
    {
        $this->icalendarService = $icalendarService;
    }

    /**
     * DI for $locationRepository
     *
     * @param LocationRepository $locationRepository
     */
    public function injectLocationRepository(
        LocationRepository $locationRepository
    ) {
        $this->locationRepository = $locationRepository;
    }

    /**
     * DI for $notificationService
     *
     * @param NotificationService $notificationService
     */
    public function injectNotificationService(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * DI for $organisatorRepository
     *
     * @param OrganisatorRepository $organisatorRepository
     */
    public function injectOrganisatorRepository(
        OrganisatorRepository $organisatorRepository
    ) {
        $this->organisatorRepository = $organisatorRepository;
    }

    /**
     * DI for $speakerRepository
     *
     * @param SpeakerRepository $speakerRepository
     */
    public function injectSpeakerRepository(
        SpeakerRepository $speakerRepository
    ) {
        $this->speakerRepository = $speakerRepository;
    }

    /**
     * DI for $paymentService
     *
     * @param PaymentService $paymentService
     */
    public function injectPaymentService(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * DI for $registrationRepository
     *
     * @param RegistrationRepository $registrationRepository
     */
    public function injectRegistrationRepository(
        RegistrationRepository $registrationRepository
    ) {
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * DI for $registrationService
     *
     * @param RegistrationService $registrationService
     */
    public function injectRegistrationService(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * DI for $fieldRepository
     *
     * @param FieldRepository $fieldRepository
     */
    public function injectFieldRepository(
        FieldRepository $fieldRepository
    ) {
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'] ?: null;
    }

    /**
     * Overwrites a given demand object by an propertyName =>  $propertyValue array
     *
     * @param EventDemand $demand Demand
     * @param array $overwriteDemand OwerwriteDemand
     *
     * @return EventDemand
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
            ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
        }

        return $demand;
    }
}
