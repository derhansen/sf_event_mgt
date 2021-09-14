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
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Hmac ViewHelper for registrations
 */
class HmacViewHelper extends AbstractViewHelper
{
    protected HashService $hashService;

    public function injectHashService(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('registration', 'object', 'Registration', true);
    }

    /**
     * Returns the hmac for the given registration in order to cancel the registration
     *
     * @return string
     */
    public function render(): string
    {
        /** @var Registration $registration */
        $registration = $this->arguments['registration'];
        $result = '';
        if ($registration && is_a($registration, Registration::class)) {
            $result = $this->hashService->generateHmac('reg-' . $registration->getUid());
        }

        return $result;
    }
}
