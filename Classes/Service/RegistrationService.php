<?php
namespace DERHANSEN\SfEventMgt\Service;

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

use \TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;

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
     * @inject
     */
    protected $objectManager;

    /**
     * RegistrationRepository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     * @inject
     */
    protected $registrationRepository;

    /**
     * Hash Service
     *
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     * @inject
     */
    protected $hashService;

    /**
     * Handles expired registrations. If the $delete parameter is set, then
     * registrations are deleted, else just hidden
     *
     * @param bool $delete Delete
     *
     * @return void
     */
    public function handleExpiredRegistrations($delete = false)
    {
        $registrations = $this->registrationRepository->findExpiredRegistrations(new \DateTime());
        if ($registrations->count() > 0) {
            foreach ($registrations as $registration) {
                /** @var \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration */
                if ($delete) {
                    $this->registrationRepository->remove($registration);
                } else {
                    $registration->setHidden(true);
                    $this->registrationRepository->update($registration);
                }
            }
        }
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
            $newReg = $this->objectManager->get('DERHANSEN\SfEventMgt\Domain\Model\Registration');
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

        return array(
            $failed,
            $registration,
            $messageKey,
            $titleKey
        );
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

        return array(
            $failed,
            $registration,
            $messageKey,
            $titleKey
        );
    }
}