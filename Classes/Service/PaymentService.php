<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Payment\AbstractPayment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PaymentService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentService
{
    /**
     * Returns an array of configured payment methods available for all events
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $paymentMethods = [];
        $configuredPaymentMethods = $this->getConfiguredPaymentMethodConfig();
        foreach ($configuredPaymentMethods as $key => $value) {
            $paymentMethods[$key] = $this->translate('payment.title.' . $key, $value['extkey']);
        }

        return $paymentMethods;
    }

    /**
     * Translates the given key (required, so translations can be mocked)
     *
     * @param string $key
     * @param string $extension
     * @param array $arguments
     * @return null|string
     */
    protected function translate($key, $extension, $arguments = null)
    {
        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, $extension, $arguments);
    }

    /**
     * Returns an array of payment methods configured in the event
     *
     * @param Event $event
     * @return array
     */
    public function getRestrictedPaymentMethods($event)
    {
        $restrictedPaymentMethods = [];
        $allPaymentMethods = $this->getPaymentMethods();
        $selectedPaymentMethods = explode(',', $event->getSelectedPaymentMethods());
        foreach ($selectedPaymentMethods as $selectedPaymentMethod) {
            if (isset($allPaymentMethods[$selectedPaymentMethod])) {
                $restrictedPaymentMethods[$selectedPaymentMethod] = $allPaymentMethods[$selectedPaymentMethod];
            }
        }

        return $restrictedPaymentMethods;
    }

    /**
     * Returns an array of payment method configurations and respects enabled/disabled payment methods from
     * the extension configuration
     *
     * @return array
     */
    protected function getConfiguredPaymentMethodConfig()
    {
        $extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sf_event_mgt']);
        $allPaymentMethods = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'];
        if ((bool)$extensionConfiguration['enableInvoice'] === false) {
            unset($allPaymentMethods['invoice']);
        }
        if ((bool)$extensionConfiguration['enableTransfer'] === false) {
            unset($allPaymentMethods['transfer']);
        }

        return $allPaymentMethods;
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
            /** @var AbstractPayment $paymentInstance */
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
