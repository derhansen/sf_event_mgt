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

use DERHANSEN\SfEventMgt\Payment\AbstractPayment;
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
     * @return null|AbstractPayment
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

    /**
     * Returns, if the given action is enabled for the payment method
     *
     * @param string $paymentMethod
     * @param string $action
     * @return bool
     */
    public function paymentActionEnabled($paymentMethod, $action)
    {
        $result = false;
        $paymentInstance = $this->getPaymentInstance($paymentMethod);
        switch ($action) {
            case 'redirectAction':
                $result = $paymentInstance->isRedirectEnabled();
                break;
            case 'successAction':
                $result = $paymentInstance->isSuccessLinkEnabled();
                break;
            case 'failureAction':
                $result = $paymentInstance->isFailureLinkEnabled();
                break;
            case 'cancelAction':
                $result = $paymentInstance->isCancelLinkEnabled();
                break;
            case 'notifyAction':
                $result = $paymentInstance->isNotifyLinkEnabled();
                break;
        }
        return $result;
    }

}