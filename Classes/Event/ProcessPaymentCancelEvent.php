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

/**
 * This event is triggered before the payment cancel view is rendered. This event must be used to handle the
 * registration (e.g. update/delete) in case of the payment process is cancelled.
 */
final class ProcessPaymentCancelEvent
{
    private array $variables;
    private string $paymentMethod;
    private bool $updateRegistration;
    private bool $removeRegistration;
    private Registration $registration;
    private array $getVariables;
    private PaymentController $paymentController;

    public function __construct(
        array $variables,
        string $paymentMethod,
        bool $updateRegistration,
        bool $removeRegistration,
        Registration $registration,
        array $getVariables,
        PaymentController $paymentController
    ) {
        $this->variables = $variables;
        $this->paymentMethod = $paymentMethod;
        $this->updateRegistration = $updateRegistration;
        $this->removeRegistration = $removeRegistration;
        $this->registration = $registration;
        $this->getVariables = $getVariables;
        $this->paymentController = $paymentController;
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
}
