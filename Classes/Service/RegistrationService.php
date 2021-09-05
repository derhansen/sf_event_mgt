<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\FrontendUserRepository;
use DERHANSEN\SfEventMgt\Event\AfterRegistrationMovedFromWaitlist;
use DERHANSEN\SfEventMgt\Payment\AbstractPayment;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * RegistrationService
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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * RegistrationRepository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     * */
    protected $registrationRepository;

    protected ?FrontendUserRepository $frontendUserRepository = null;

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
     * Notification Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\NotificationService
     */
    protected $notificationService;

    public function injectFrontendUserRepository(FrontendUserRepository $frontendUserRepository)
    {
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
     * @param \DERHANSEN\SfEventMgt\Service\NotificationService $notificationService
     */
    public function injectNotificationService(\DERHANSEN\SfEventMgt\Service\NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
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
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function injectEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
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
            $this->registrationRepository->add($newReg);
        }
    }

    /**
     * Confirms all depending registrations based on the given main registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
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

        if (!$failed && is_null($registration->getEvent())) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_event_not_found';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && $registration->getEvent()->getEnableCancel() === false) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_cancel_disabled';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && $registration->getEvent()->getCancelDeadline()
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
     * @return FrontendUser|null
     */
    public function getCurrentFeUserObject(): ?FrontendUser
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
     * Returns if the given email is registered to the given event
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
     * Handles the process of moving registration up from the waitlist.
     *
     * @param Event $event
     * @param array $settings
     */
    public function moveUpWaitlistRegistrations(Event $event, array $settings)
    {
        // Early return if move up not enabled, no registrations on waitlist or no free places left
        if (!$event->getEnableWaitlistMoveup() || $event->getRegistrationsWaitlist()->count() === 0 ||
            $event->getFreePlaces() === 0
        ) {
            return;
        }

        $keepMainRegistrationDependency = $settings['waitlist']['moveUp']['keepMainRegistrationDependency'] ?? false;
        $freePlaces = $event->getFreePlaces();
        $moveupRegistrations = $this->registrationRepository->findWaitlistMoveUpRegistrations($event);

        /** @var Registration $registration */
        foreach ($moveupRegistrations as $registration) {
            $registration->setWaitlist(false);
            $registration->setIgnoreNotifications(false);

            if (!(bool)$keepMainRegistrationDependency) {
                $registration->_setProperty('mainRegistration', 0);
            }

            $this->registrationRepository->update($registration);

            // Send messages to user and admin
            $this->notificationService->sendUserMessage(
                $event,
                $registration,
                $settings,
                MessageType::REGISTRATION_WAITLIST_MOVE_UP
            );
            $this->notificationService->sendAdminMessage(
                $registration->getEvent(),
                $registration,
                $settings,
                MessageType::REGISTRATION_WAITLIST_MOVE_UP
            );

            $this->eventDispatcher->dispatch(new AfterRegistrationMovedFromWaitlist($registration, $this));

            $freePlaces--;
            if ($freePlaces === 0) {
                break;
            }
        }
    }
}
