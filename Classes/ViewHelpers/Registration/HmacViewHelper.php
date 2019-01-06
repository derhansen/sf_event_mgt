<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Hmac ViewHelper for registrations
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class HmacViewHelper extends AbstractViewHelper
{
    /**
     * Hash Service
     *
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     * */
    protected $hashService;

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
     * Initialize arguments
     *
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('registration', 'object', 'Registration', false);
    }

    /**
     * Returns the hmac for the given registration in order to cancel the registration
     *
     * @return string
     */
    public function render()
    {
        /** @var Registration $registration */
        $registration = $this->arguments['registration'];
        $result = '';
        if ($registration && is_a(Registration::class, $registration)) {
            $result = $this->hashService->generateHmac('reg-' . $registration->getUid());
        }

        return $result;
    }
}
