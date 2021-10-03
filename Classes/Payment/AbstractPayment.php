<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Payment;

/**
 * AbstractPayment
 */
abstract class AbstractPayment
{
    /**
     * Enable redirect for payment method
     *
     * @var bool
     */
    protected bool $enableRedirect = false;

    /**
     * Enable success link for payment method
     *
     * @var bool
     */
    protected bool $enableSuccessLink = false;

    /**
     * Enable failure link for payment method
     *
     * @var bool
     */
    protected bool $enableFailureLink = false;

    /**
     * Enable cancel link for payment method
     *
     * @var bool
     */
    protected bool $enableCancelLink = false;

    /**
     * Enable notify link for payment method
     *
     * @var bool
     */
    protected bool $enableNotifyLink = false;

    /**
     * Returns, if redirect is enabled for the payment method
     *
     * @return bool
     */
    public function isRedirectEnabled(): bool
    {
        return $this->enableRedirect;
    }

    /**
     * Returns, if the success link is enabled for the payment method
     *
     * @return bool
     */
    public function isSuccessLinkEnabled(): bool
    {
        return $this->enableSuccessLink;
    }

    /**
     * Returns, if the failure link is enabled for the payment method
     *
     * @return bool
     */
    public function isFailureLinkEnabled(): bool
    {
        return $this->enableFailureLink;
    }

    /**
     * Returns, if the cancel link is enabled for the payment method
     *
     * @return bool
     */
    public function isCancelLinkEnabled(): bool
    {
        return $this->enableCancelLink;
    }

    /**
     * Returns, if the notify link is enabled for the payment method
     *
     * @return bool
     */
    public function isNotifyLinkEnabled(): bool
    {
        return $this->enableNotifyLink;
    }
}
