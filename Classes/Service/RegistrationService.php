<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Payment\AbstractPayment;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * RegistrationService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationService
{
    /**
     * The object manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * */
    protected $objectManager;

    /**
     * RegistrationRepository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     * */
    protected $registrationRepository;

    /**
     * FrontendUserRepository
     *
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     * */
    protected $frontendUserRepository;

    /**
     * Hash Service
     *
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     * */
    protected $hashService;

    /**
     * Payment Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\PaymentService
     * */
    protected $paymentService;

    /**
     * DI for $frontendUserRepository
     *
     * @param \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $frontendUserRepository
     */
    public function injectFrontendUserRepository(
        \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $frontendUserRepository
    ) {
        $this->frontendUserRepository = $frontendUserRepository;
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
     * DI for $objectManager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
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
     * Duplicates (all public accessable properties) the given registration the
     * amount of times configured in amountOfRegistrations
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     *
     * @return void
     */
    public function createDependingRegistrations($registration)
    {
        $registrations = $registration->getAmountOfRegistrations();
        for ($i = 1; $i <= $registrations - 1; $i++) {
            /** @var \DERHANSEN\SfEventMgt\Domain\Model\Registration $newReg */
            $newReg = $this->objectManager->get(Registration::class);
            $properties = ObjectAccess::getGettableProperties($registration);
            foreach ($properties as $propertyName => $propertyValue) {
                ObjectAccess::setProperty($newReg, $propertyName, $propertyValue);
            }
            $newReg->setMainRegistration($registration);
            $newReg->setAmountOfRegistrations(1);
            $newReg->setIgnoreNotifications(true);
            $newReg->_setProperty('_languageUid', $registration->_getProperty('_languageUid'));
            $this->registrationRepository->add($newReg);
        }
    }

    /**
     * Confirms all depending registrations based on the given main registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     *
     * @return void
     */
    public function confirmDependingRegistrations($registration)
    {
        $registrations = $this->registrationRepository->findByMainRegistration($registration);
        foreach ($registrations as $foundRegistration) {
            /** @var \DERHANSEN\SfEventMgt\Domain\Model\Registration $foundRegistration */
            $foundRegistration->setConfirmed(true);
            $this->registrationRepository->update($foundRegistration);
        }
    }

    /**
     * Checks if the registration can be confirmed and returns an array of variables
     *
     * @param int $reguid UID of registration
     * @param string $hmac HMAC for parameters
     *
     * @return array
     */
    public function checkConfirmRegistration($reguid, $hmac)
    {
        /* @var $registration Registration */
        $registration = null;
        $failed = false;
        $messageKey = 'event.message.confirmation_successful';
        $titleKey = 'confirmRegistration.title.successful';

        if (!$this->hashService->validateHmac('reg-' . $reguid, $hmac)) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_wrong_hmac';
            $titleKey = 'confirmRegistration.title.failed';
        } else {
            $registration = $this->registrationRepository->findByUid($reguid);
        }

        if (!$failed && is_null($registration)) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_registration_not_found';
            $titleKey = 'confirmRegistration.title.failed';
        }

        if (!$failed && $registration->getConfirmationUntil() < new \DateTime()) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_confirmation_until_expired';
            $titleKey = 'confirmRegistration.title.failed';
        }

        if (!$failed && $registration->getConfirmed() === true) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_already_confirmed';
            $titleKey = 'confirmRegistration.title.failed';
        }

        if (!$failed && $registration->getWaitlist()) {
            $messageKey = 'event.message.confirmation_waitlist_successful';
            $titleKey = 'confirmRegistrationWaitlist.title.successful';
        }

        return [
            $failed,
            $registration,
            $messageKey,
            $titleKey
        ];
    }

    /**
     * Cancels all depending registrations based on the given main registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     *
     * @return void
     */
    public function cancelDependingRegistrations($registration)
    {
        $registrations = $this->registrationRepository->findByMainRegistration($registration);
        foreach ($registrations as $foundRegistration) {
            $this->registrationRepository->remove($foundRegistration);
        }
    }

    /**
     * Checks if the registration can be cancelled and returns an array of variables
     *
     * @param int $reguid UID of registration
     * @param string $hmac HMAC for parameters
     *
     * @return array
     */
    public function checkCancelRegistration($reguid, $hmac)
    {
        /* @var $registration Registration */
        $registration = null;
        $failed = false;
        $messageKey = 'event.message.cancel_successful';
        $titleKey = 'cancelRegistration.title.successful';

        if (!$this->hashService->validateHmac('reg-' . $reguid, $hmac)) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_wrong_hmac';
            $titleKey = 'cancelRegistration.title.failed';
        } else {
            $registration = $this->registrationRepository->findByUid($reguid);
        }

        if (!$failed && is_null($registration)) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_registration_not_found_or_cancelled';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && $registration->getEvent()->getEnableCancel() === false) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_cancel_disabled';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && $registration->getEvent()->getCancelDeadline() > 0
            && $registration->getEvent()->getCancelDeadline() < new \DateTime()
        ) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_deadline_expired';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && $registration->getEvent()->getStartdate() < new \DateTime()) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_event_started';
            $titleKey = 'cancelRegistration.title.failed';
        }

        return [
            $failed,
            $registration,
            $messageKey,
            $titleKey
        ];
    }

    /**
     * Returns the current frontend user object if available
     *
     * @return mixed \TYPO3\CMS\Extbase\Domain\Model\FrontendUser|null
     */
    public function getCurrentFeUserObject()
    {
        if (isset($GLOBALS['TSFE']->fe_user->user['uid'])) {
            return $this->frontendUserRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        }

        return null;
    }

    /**
     * Checks, if the registration can successfully be created. Note, that
     * $result is passed by reference!
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     * @param int $result Result
     *
     * @return array
     */
    public function checkRegistrationSuccess($event, $registration, $result)
    {
        $success = true;
        if ($event->getEnableRegistration() === false) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_NOT_ENABLED;
        } elseif ($event->getRegistrationDeadline() != null && $event->getRegistrationDeadline() < new \DateTime()) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED;
        } elseif ($event->getStartdate() < new \DateTime()) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED;
        } elseif ($event->getRegistrations()->count() >= $event->getMaxParticipants()
            && $event->getMaxParticipants() > 0 && !$event->getEnableWaitlist()
        ) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS;
        } elseif ($event->getFreePlaces() < $registration->getAmountOfRegistrations()
            && $event->getMaxParticipants() > 0 && !$event->getEnableWaitlist()
        ) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES;
        } elseif ($event->getMaxRegistrationsPerUser() < $registration->getAmountOfRegistrations()) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED;
        } elseif ($event->getUniqueEmailCheck() &&
            $this->emailNotUnique($event, $registration->getEmail())
        ) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE;
        } elseif ($event->getRegistrations()->count() >= $event->getMaxParticipants()
            && $event->getMaxParticipants() > 0 && $event->getEnableWaitlist()
        ) {
            $result = RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST;
        }

        return [$success, $result];
    }

    /**
     * Returns if the given e-mail is registered to the given event
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
     * @param string $email
     * @return bool
     */
    protected function emailNotUnique($event, $email)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_registration');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        $registrations = $queryBuilder->count('email')
            ->from('tx_sfeventmgt_domain_model_registration')
            ->where(
                $queryBuilder->expr()->eq(
                    'event',
                    $queryBuilder->createNamedParameter($event->getUid(), Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'email',
                    $queryBuilder->createNamedParameter($email, Connection::PARAM_STR)
                )
            )
            ->execute()
            ->fetchColumn();

        return $registrations >= 1;
    }

    /**
     * Returns, if payment redirect for the payment method is enabled
     *
     * @param Registration $registration
     * @return bool
     */
    public function redirectPaymentEnabled($registration)
    {
        if ($registration->getEvent()->getEnablePayment() === false) {
            return false;
        }

        /** @var AbstractPayment $paymentInstance */
        $paymentInstance = $this->paymentService->getPaymentInstance($registration->getPaymentmethod());
        if ($paymentInstance !== null && $paymentInstance->isRedirectEnabled()) {
            return true;
        }

        return false;
    }

    /**
     * Returns if the given amount of registrations for the event will be registrations for the waitlist
     * (depending on the total amount of registrations and free places)
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
     * @param int $amountOfRegistrations
     * @return bool
     */
    public function isWaitlistRegistration($event, $amountOfRegistrations)
    {
        if ($event->getMaxParticipants() === 0 || !$event->getEnableWaitlist()) {
            return false;
        }

        $result = false;
        if (($event->getFreePlaces() > 0 && $event->getFreePlaces() < $amountOfRegistrations)
            || $event->getFreePlaces() <= 0) {
            $result = true;
        }

        return $result;
    }

    /**
     * Fixes the event uid of a registration if the event has been saved as a child of a translated event.
     *
     * Since TYPO3 9.5 (#82363), registrations for events are saved to the translated event record
     *
     * Example:
     *
     * When a registration is saved for a translated event, the registration $registration->setEvent($event) will
     * now save the UID of the translated event instead of the uid of the event in default language.
     *
     * This behavior breaks limitations on events (e.g. max participants). Therefore, the registration must always
     * be related to the default event language (Extbase behavior before TYPO3 9.5)
     *
     * @param Registration $registration
     * @param Event $event
     * @return void
     */
    public function fixRegistrationEvent(Registration $registration, Event $event)
    {
        // Early return when event is in default language
        if ((int)$event->_getProperty('_languageUid') === 0) {
            return;
        }
        $this->updateRegistrationEventUid($registration, $event);
        $this->updateEventRegistrationCounters($event);
    }

    /**
     * Sets the "event" field of the given registration to the uid of the given event
     *
     * @param Registration $registration
     * @param Event $event
     * @return void
     */
    protected function updateRegistrationEventUid(Registration $registration, Event $event)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_registration');
        $queryBuilder->update('tx_sfeventmgt_domain_model_registration')
            ->set('event', $event->getUid())
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($registration->getUid(), Connection::PARAM_INT)
                )
            )
            ->execute();
    }

    /**
     * Updates registration/waitlist registration counters for the given event
     *
     * @param Event $event
     * @return void
     */
    protected function updateEventRegistrationCounters(Event $event)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_event');

        $countRegistrations = $this->getEventRegistrationCount($event, 0);
        $countRegistrationsWaitlist = $this->getEventRegistrationCount($event, 1);

        $queryBuilder->update('tx_sfeventmgt_domain_model_event')
            ->set('registration', $countRegistrations)
            ->set('registration_waitlist', $countRegistrationsWaitlist)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($event->getUid(), Connection::PARAM_INT)
                )
            )
            ->orWhere(
                $queryBuilder->expr()->eq(
                    'l10n_parent',
                    $queryBuilder->createNamedParameter($event->getUid(), Connection::PARAM_INT)
                )
            )
            ->execute();
    }

    /**
     * Returns the total amount of registrations/waitlist registrations for an event
     *
     * @param Event $event
     * @param int $waitlist
     * @return mixed
     */
    protected function getEventRegistrationCount(Event $event, int $waitlist = 0)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_registration');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        return $queryBuilder->count('uid')
            ->from('tx_sfeventmgt_domain_model_registration')
            ->where(
                $queryBuilder->expr()->eq(
                    'event',
                    $queryBuilder->createNamedParameter($event->getUid(), Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'waitlist',
                    $queryBuilder->createNamedParameter($waitlist, Connection::PARAM_INT)
                )
            )
            ->execute()
            ->fetchColumn();
    }
}
