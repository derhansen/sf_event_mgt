<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Hooks;

use DERHANSEN\SfEventMgt\Service\PaymentService;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hooks for ItemsProcFunc
 */
class ItemsProcFunc
{
    protected PaymentService $paymentService;

    public function __construct()
    {
        $this->paymentService = GeneralUtility::makeInstance(PaymentService::class);
    }

    /**
     * Itemsproc function for payment method select field
     */
    public function getPaymentMethods(array &$config): void
    {
        $paymentMethods = $this->paymentService->getPaymentMethods();
        foreach ($paymentMethods as $value => $label) {
            $config['items'][] = [$label, $value];
        }
    }

    /**
     * Itemsproc function for fe_user data select field
     */
    public function getFeuserValues(array &$config): void
    {
        $defaultExcludedFields = [
            'password',
            'usergroup',
            'tx_extbase_type',
            'disable',
            'starttime',
            'endtime',
            'felogin_forgotHash',
            'felogin_redirectPid',
            'TSconfig',
        ];
        $columns = $GLOBALS['TCA']['fe_users']['columns'] ?? [];
        foreach ($columns as $columnName => $columnConfig) {
            if (($columnConfig['label'] ?? '') === '' || in_array($columnName, $defaultExcludedFields, true)) {
                continue;
            }

            $label = $this->getLanguageService()->sL($columnConfig['label']);
            $config['items'][] = [$label, $columnName];
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
