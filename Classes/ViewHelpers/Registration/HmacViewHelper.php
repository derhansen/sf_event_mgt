<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Security\HashScope;
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Hmac ViewHelper for registrations
 */
class HmacViewHelper extends AbstractViewHelper
{
    protected HashService $hashService;

    public function injectHashService(HashService $hashService): void
    {
        $this->hashService = $hashService;
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('registration', 'object', 'Registration', true);
    }

    /**
     * Returns the hmac for the given registration in order to cancel the registration
     */
    public function render(): string
    {
        /** @var Registration $registration */
        $registration = $this->arguments['registration'];
        $result = '';
        if (is_a($registration, Registration::class)) {
            $result = $this->hashService->hmac('reg-' . $registration->getUid(), HashScope::RegistrationUid->value);
        }

        return $result;
    }
}
