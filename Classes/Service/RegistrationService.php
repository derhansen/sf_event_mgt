<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\FrontendUserRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Event\AfterRegistrationMovedFromWaitlist;
use DERHANSEN\SfEventMgt\Event\ModifyCheckRegistrationSuccessEvent;
use DERHANSEN\SfEventMgt\Payment\AbstractPayment;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

class RegistrationService
{
    protected EventDispatcherInterface $eventDispatcher;
    protected RegistrationRepository $registrationRepository;
    protected FrontendUserRepository $frontendUserRepository;
    protected HashService $hashService;
    protected PaymentService $paymentService;
    protected NotificationService $notificationService;

    public function injectFrontendUserRepository(FrontendUserRepository $frontendUserRepository): void
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }

    public function injectHashService(HashService $hashService): void
    {
        $this->hashService = $hashService;
    }

    public function injectNotificationService(NotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }

    public function injectEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function injectPaymentService(PaymentService $paymentService): void
    {
        $this->paymentService = $paymentService;
    }

    public function injectRegistrationRepository(RegistrationRepository $registrationRepository): void
    {
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * Duplicates the given registration (all public accessible properties) the
     * amount of times configured in amountOfRegistrations
     */
    public function createDependingRegistrations(Registration $registration): void
    {
        $registrations = $registration->getAmountOfRegistrations();
        for ($i = 1; $i <= $registrations - 1; $i++) {
            $newReg = GeneralUtility::makeInstance(Registration::class);
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
     */
    public function confirmDependingRegistrations(Registration $registration): void
    {
        $registrations = $this->registrationRepository->findByMainRegistration($registration);
        foreach ($registrations as $foundRegistration) {
            /** @var Registration $foundRegistration */
            $foundRegistration->setConfirmed(true);
            $this->registrationRepository->update($foundRegistration);
        }
    }

    /**
     * Checks if the registration can be confirmed and returns an array of variables
     */
    public function checkConfirmRegistration(int $regUid, string $hmac): array
    {
        /* @var $registration Registration */
        $registration = null;
        $failed = false;
        $messageKey = 'event.message.confirmation_successful';
        $titleKey = 'confirmRegistration.title.successful';

        if (!$this->hashService->validateHmac('reg-' . $regUid, $hmac)) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_wrong_hmac';
            $titleKey = 'confirmRegistration.title.failed';
        } else {
            $registration = $this->registrationRepository->findByUid($regUid);
        }

        if (!$failed && is_null($registration)) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_registration_not_found';
            $titleKey = 'confirmRegistration.title.failed';
        }

        if (!$failed && !$registration->getEvent()) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_registration_event_not_found';
            $titleKey = 'confirmRegistration.title.failed';
        }

        if (!$failed && $registration->getConfirmationUntil() < new DateTime()) {
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
            $titleKey,
        ];
    }

    /**
     * Cancels all depending registrations based on the given main registration
     */
    public function cancelDependingRegistrations(Registration $registration): void
    {
        $registrations = $this->registrationRepository->findByMainRegistration($registration);
        foreach ($registrations as $foundRegistration) {
            $this->registrationRepository->remove($foundRegistration);
        }
    }

    /**
     * Checks if the registration can be cancelled and returns an array of variables
     */
    public function checkCancelRegistration(int $regUid, string $hmac): array
    {
        /* @var $registration Registration */
        $registration = null;
        $failed = false;
        $messageKey = 'event.message.cancel_successful';
        $titleKey = 'cancelRegistration.title.successful';

        if (!$this->hashService->validateHmac('reg-' . $regUid, $hmac)) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_wrong_hmac';
            $titleKey = 'cancelRegistration.title.failed';
        } else {
            $registration = $this->registrationRepository->findByUid($regUid);
        }

        if (!$failed && is_null($registration)) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_registration_not_found_or_cancelled';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && !is_a($registration->getEvent(), Event::class)) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_event_not_found';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && $registration->getEvent()->getEnableCancel() === false) {
            $failed = true;
            $messageKey = 'event.message.confirmation_failed_cancel_disabled';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && $registration->getEvent()->getCancelDeadline() !== null
            && $registration->getEvent()->getCancelDeadline() < new DateTime()
        ) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_deadline_expired';
            $titleKey = 'cancelRegistration.title.failed';
        }

        if (!$failed && $registration->getEvent()->getStartdate() < new DateTime()) {
            $failed = true;
            $messageKey = 'event.message.cancel_failed_event_started';
            $titleKey = 'cancelRegistration.title.failed';
        }

        return [
            $failed,
            $registration,
            $messageKey,
            $titleKey,
        ];
    }

    /**
     * Returns the current frontend user object if available
     */
    public function getCurrentFeUserObject(): ?FrontendUser
    {
        $user = null;

        if (isset($GLOBALS['TSFE']->fe_user->user['uid'])) {
            /** @var FrontendUser $user */
            $user = $this->frontendUserRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        }

        return $user;
    }

    /**
     * Checks, if the registration can successfully be created.
     */
    public function checkRegistrationSuccess(Event $event, Registration $registration, int $result): array
    {
        $success = true;
        if ($event->getEnableRegistration() === false) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_NOT_ENABLED;
        } elseif ($event->getRegistrationDeadline() != null && $event->getRegistrationDeadline() < new DateTime()) {
            $success = false;
            $result = RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED;
        } elseif ($event->getStartdate() < new DateTime()) {
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

        $modifyCheckRegistrationSuccessEvent = new ModifyCheckRegistrationSuccessEvent($success, $result);
        $this->eventDispatcher->dispatch($modifyCheckRegistrationSuccessEvent);

        return [$modifyCheckRegistrationSuccessEvent->getSuccess(), $modifyCheckRegistrationSuccessEvent->getResult()];
    }

    /**
     * Returns if the given email is registered to the given event
     */
    protected function emailNotUnique(Event $event, string $email): bool
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
            ->executeQuery()
            ->fetchOne();

        return $registrations >= 1;
    }

    /**
     * Returns, if payment redirect for the payment method is enabled
     */
    public function redirectPaymentEnabled(Registration $registration): bool
    {
        if ($registration->getEvent()->getEnablePayment() === false) {
            return false;
        }

        /** @var AbstractPayment $paymentInstance */
        $paymentInstance = $this->paymentService->getPaymentInstance($registration->getPaymentmethod());

        return $paymentInstance !== null && $paymentInstance->isRedirectEnabled();
    }

    /**
     * Returns if the given amount of registrations for the event will be registrations for the waitlist
     * (depending on the total amount of registrations and free places)
     */
    public function isWaitlistRegistration(Event $event, int $amountOfRegistrations): bool
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
     */
    public function moveUpWaitlistRegistrations(Event $event, array $settings): void
    {
        // Early return if move up not enabled, no registrations on waitlist or no free places left
        if (!$event->getEnableWaitlistMoveup() || $event->getRegistrationsWaitlist()->count() === 0 ||
            $event->getFreePlaces() <= 0
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
                $registration->_setProperty('mainRegistration', null);
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
