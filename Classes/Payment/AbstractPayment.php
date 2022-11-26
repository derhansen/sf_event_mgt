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
    protected bool $enableRedirect = false;
    protected bool $enableSuccessLink = false;
    protected bool $enableFailureLink = false;
    protected bool $enableCancelLink = false;
    protected bool $enableNotifyLink = false;

    /**
     * Returns, if redirect is enabled for the payment method
     */
    public function isRedirectEnabled(): bool
    {
        return $this->enableRedirect;
    }

    /**
     * Returns, if the success link is enabled for the payment method
     */
    public function isSuccessLinkEnabled(): bool
    {
        return $this->enableSuccessLink;
    }

    /**
     * Returns, if the failure link is enabled for the payment method
     */
    public function isFailureLinkEnabled(): bool
    {
        return $this->enableFailureLink;
    }

    /**
     * Returns, if the cancel link is enabled for the payment method
     */
    public function isCancelLinkEnabled(): bool
    {
        return $this->enableCancelLink;
    }

    /**
     * Returns, if the notify link is enabled for the payment method
     */
    public function isNotifyLinkEnabled(): bool
    {
        return $this->enableNotifyLink;
    }
}
