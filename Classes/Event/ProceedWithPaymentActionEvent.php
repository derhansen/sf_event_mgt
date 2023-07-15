<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;

/**
 * This event is triggered in PaymentController::proceedWithAction() and allows to skip the "$registration->getPaid()"
 * check for various actions.
 */
final class ProceedWithPaymentActionEvent
{
    private bool $performPaidCheck = true;
    private Registration $registration;
    private string $actionName;

    public function __construct(Registration $registration, string $actionName)
    {
        $this->registration = $registration;
        $this->actionName = $actionName;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    public function getPerformPaidCheck(): bool
    {
        return $this->performPaidCheck;
    }

    public function setPerformPaidCheck(bool $performPaidCheck): void
    {
        $this->performPaidCheck = $performPaidCheck;
    }
}
