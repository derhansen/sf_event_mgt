<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration;

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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

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
     * @inject
     */
    protected $hashService;

    /**
     * Returns the hmac for the given registration in order to cancel the registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     *
     * @return array
     */
    public function render($registration)
    {
        $result = '';
        if ($registration) {
            $result = $this->hashService->generateHmac('reg-' . $registration->getUid());
        }
        return $result;
    }
}
