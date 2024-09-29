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
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event is triggered in PaymentController::proceedWithAction() and allows to skip the "$registration->getPaid()"
 * check for various actions.
 */
final class ProceedWithPaymentActionEvent
{
    private bool $performPaidCheck = true;

    public function __construct(
        private readonly Registration $registration,
        private readonly string $actionName,
        private readonly ServerRequestInterface $request
    ) {
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

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
