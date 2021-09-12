<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Payment\AbstractPayment;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * PaymentService
 */
class PaymentService
{
    /**
     * Returns an array of configured payment methods available for all events
     *
     * @return array
     */
    public function getPaymentMethods(): array
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
     * @param array|null $arguments
     * @return string|null
     */
    protected function translate(string $key, string $extension, ?array $arguments = null): ?string
    {
        return LocalizationUtility::translate($key, $extension, $arguments);
    }

    /**
     * Returns an array of payment methods configured in the event
     *
     * @param Event $event
     * @return array
     */
    public function getRestrictedPaymentMethods(Event $event): array
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
    protected function getConfiguredPaymentMethodConfig(): array
    {
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('sf_event_mgt');
        $allPaymentMethods = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'] ?? '';
        if ((bool)($extensionConfiguration['enableInvoice'] ?? false) === false) {
            unset($allPaymentMethods['invoice']);
        }
        if ((bool)($extensionConfiguration['enableTransfer'] ?? false) === false) {
            unset($allPaymentMethods['transfer']);
        }

        return $allPaymentMethods;
    }

    /**
     * Returns an instance of the given payment method
     *
     * @param string $paymentMethod
     * @return AbstractPayment|null
     */
    public function getPaymentInstance(string $paymentMethod): ?AbstractPayment
    {
        $paymentInstance = null;
        $configuredPaymentMethods = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'] ?? '';
        if (isset($configuredPaymentMethods[$paymentMethod]) &&
            class_exists($configuredPaymentMethods[$paymentMethod]['class'] ?? '')) {
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
    public function paymentActionEnabled(string $paymentMethod, string $action): bool
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
