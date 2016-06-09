<?php
namespace DERHANSEN\SfEventMgt\Hooks;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use DERHANSEN\SfEventMgt\Service\PaymentService;

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
