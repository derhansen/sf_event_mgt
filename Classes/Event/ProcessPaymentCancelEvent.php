<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Controller\PaymentController;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event is triggered before the payment cancel view is rendered. This event must be used to handle the
 * registration (e.g. update/delete) in case of the payment process is cancelled.
 */
final class ProcessPaymentCancelEvent
{
    public function __construct(
        private array $variables,
        private readonly string $paymentMethod,
        private bool $updateRegistration,
        private bool $removeRegistration,
        private readonly Registration $registration,
        private readonly array $getVariables,
        private readonly PaymentController $paymentController,
        private readonly ServerRequestInterface $request
    ) {
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getUpdateRegistration(): bool
    {
        return $this->updateRegistration;
    }

    public function getRemoveRegistration(): bool
    {
        return $this->removeRegistration;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getGetVariables(): array
    {
        return $this->getVariables;
    }

    public function getPaymentController(): PaymentController
    {
        return $this->paymentController;
    }

    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }

    public function setUpdateRegistration(bool $updateRegistration): void
    {
        $this->updateRegistration = $updateRegistration;
    }

    public function setRemoveRegistration(bool $removeRegistration): void
    {
        $this->removeRegistration = $removeRegistration;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
