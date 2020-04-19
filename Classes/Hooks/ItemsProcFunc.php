<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Hooks;

use DERHANSEN\SfEventMgt\Service\PaymentService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hooks for ItemsProcFunc
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ItemsProcFunc
{
    /**
     * @var PaymentService
     */
    protected $paymentService;

    /**
     * ItemsProcFunc constructor.
     */
    public function __construct()
    {
        $this->paymentService = GeneralUtility::makeInstance(PaymentService::class);
    }

    /**
     * Itemsproc function for payment method select field
     *
     * @param array $config
     */
    public function getPaymentMethods(array &$config)
    {
        $paymentMethods = $this->paymentService->getPaymentMethods();
        foreach ($paymentMethods as $value => $label) {
            array_push($config['items'], [$label, $value]);
        }
    }
}
