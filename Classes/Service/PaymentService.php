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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PaymentService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentService
{

    /**
     * Returns an array of configured payment methods
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $paymentMethods = [];
        $configuredPaymentMethods = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'];
        foreach ($configuredPaymentMethods as $key => $value) {
            $paymentMethods[$key] = LocalizationUtility::translate('payment.title.' . $key, $value['extkey']);
        }
        return $paymentMethods;
    }

    /**
     * Returns an instance of the given payment method
     *
     * @param string $paymentMethod
     * @return null|object
     */
    public function getPaymentInstance($paymentMethod)
    {
        $paymentInstance = null;
        $configuredPaymentMethods = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'];
        if (isset($configuredPaymentMethods[$paymentMethod]) &&
            class_exists($configuredPaymentMethods[$paymentMethod]['class'])) {
            $paymentInstance = GeneralUtility::makeInstance($configuredPaymentMethods[$paymentMethod]['class']);
        }
        return $paymentInstance;
    }

}