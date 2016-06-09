<?php
namespace DERHANSEN\SfEventMgt\Payment;

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

/**
 * AbstractPayment
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
abstract class AbstractPayment
{

    /**
     * Enable redirect for payment method
     *
     * @var bool
     */
    protected $enableRedirect = false;

    /**
     * Enable success link for payment method
     *
     * @var bool
     */
    protected $enableSuccessLink = false;

    /**
     * Enable failure link for payment method
     *
     * @var bool
     */
    protected $enableFailureLink = false;

    /**
     * Enable cancel link for payment method
     *
     * @var bool
     */
    protected $enableCancelLink = false;

    /**
     * Enable notify link for payment method
     *
     * @var bool
     */
    protected $enableNotifyLink = false;

    /**
     * Returns, if redirect is enabled for the payment method
     *
     * @return bool
     */
    public function isRedirectEnabled()
    {
        return $this->enableRedirect;
    }

    /**
     * Returns, if the success link is enabled for the payment method
     *
     * @return bool
     */
    public function isSuccessLinkEnabled()
    {
        return $this->enableSuccessLink;
    }

    /**
     * Returns, if the failure link is enabled for the payment method
     *
     * @return bool
     */
    public function isFailureLinkEnabled()
    {
        return $this->enableFailureLink;
    }

    /**
     * Returns, if the cancel link is enabled for the payment method
     *
     * @return bool
     */
    public function isCancelLinkEnabled()
    {
        return $this->enableCancelLink;
    }

    /**
     * Returns, if the notify link is enabled for the payment method
     *
     * @return bool
     */
    public function isNotifyLinkEnabled()
    {
        return $this->enableNotifyLink;
    }

}