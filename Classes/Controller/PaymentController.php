<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Controller;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Event\ModifyPaymentRedirectResponseEvent;
use DERHANSEN\SfEventMgt\Event\ProceedWithPaymentActionEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentCancelEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentFailureEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentInitializeEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentNotifyEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentSuccessEvent;
use DERHANSEN\SfEventMgt\Exception;
use DERHANSEN\SfEventMgt\Payment\Exception\PaymentException;
use DERHANSEN\SfEventMgt\Security\HashScope;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Security\Exception\InvalidHashException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class PaymentController extends AbstractController
{
    /**
     * Catches all PaymentExceptions and sets the Exception message to the response content
     */
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        try {
            $response = parent::processRequest($request);
        } catch (Exception $e) {
            $response = $this->responseFactory->createResponse()
                ->withStatus(200)
                ->withHeader('Content-Type', 'text/html; charset=utf-8');
            $response->getBody()->write('<div class="payment-error">' . $e->getMessage() . '</div>');
        } catch (InvalidHashException $e) {
            $response = $this->responseFactory->createResponse()
                ->withStatus(403)
                ->withHeader('Content-Type', 'text/html; charset=utf-8');
            $response->getBody()->write('<div class="payment-error">' . $e->getMessage() . '</div>');
        } catch (\Exception $e) {
            $response = $this->responseFactory->createResponse()
                ->withStatus(500)
                ->withHeader('Content-Type', 'text/html; charset=utf-8');
            $response->getBody()->write('<div class="payment-error">' . $e->getMessage() . '</div>');
        }

        return $response;
    }

    /**
     * Redirect to payment provider
     */
    public function redirectAction(Registration $registration, string $hmac): ResponseInterface
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $variables = [
            'sfEventMgtSettings' => $this->settings,
            'successUrl' => $this->getPaymentUriForAction('success', $registration),
            'failureUrl' => $this->getPaymentUriForAction('failure', $registration),
            'cancelUrl' => $this->getPaymentUriForAction('cancel', $registration),
            'notifyUrl' => $this->getPaymentUriForAction('notify', $registration),
            'registration' => $registration,
            'html' => '',
        ];

        $paymentMethod = $registration->getPaymentmethod();

        // If true, an external event listener requested the registration to be updated
        $updateRegistration = false;

        $processPaymentInitializeEvent = new ProcessPaymentInitializeEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $registration,
            $this
        );
        $this->eventDispatcher->dispatch($processPaymentInitializeEvent);
        $variables = $processPaymentInitializeEvent->getVariables();
        $updateRegistration = $processPaymentInitializeEvent->getUpdateRegistration();

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $variables);
        $response = $this->htmlResponse();

        $modifyPaymentRedirectResponseEvent = new ModifyPaymentRedirectResponseEvent(
            $response,
            $this->settings,
            $variables,
            $registration,
            $this
        );
        $this->eventDispatcher->dispatch($modifyPaymentRedirectResponseEvent);

        return $modifyPaymentRedirectResponseEvent->getResponse();
    }

    /**
     * Action is called when payment was successful
     */
    public function successAction(Registration $registration, string $hmac): ResponseInterface
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $variables = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        // If true, an external event listener requested the registration to be updated
        $updateRegistration = false;

        $processPaymentSuccessEvent = new ProcessPaymentSuccessEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $registration,
            $this->request->getQueryParams(),
            $this
        );
        $this->eventDispatcher->dispatch($processPaymentSuccessEvent);
        $variables = $processPaymentSuccessEvent->getVariables();
        $updateRegistration = $processPaymentSuccessEvent->getUpdateRegistration();

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $variables);

        return $this->htmlResponse();
    }

    /**
     * Action is called when payment failed
     */
    public function failureAction(Registration $registration, string $hmac): ResponseInterface
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $variables = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * Update- and remove flags
         */
        $updateRegistration = false;
        $removeRegistration = false;

        $processPaymentFailureEvent = new ProcessPaymentFailureEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $removeRegistration,
            $registration,
            $this->request->getQueryParams(),
            $this
        );
        $this->eventDispatcher->dispatch($processPaymentFailureEvent);
        $variables = $processPaymentFailureEvent->getVariables();
        $updateRegistration = $processPaymentFailureEvent->getUpdateRegistration();
        $removeRegistration = $processPaymentFailureEvent->getRemoveRegistration();

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        if ($removeRegistration) {
            // First cancel depending registrations
            if ($registration->getAmountOfRegistrations() > 1) {
                $this->registrationService->cancelDependingRegistrations($registration);
            }
            $this->registrationRepository->remove($registration);
        }

        $this->view->assign('result', $variables);

        return $this->htmlResponse();
    }

    /**
     * Action is called, when payment was cancelled
     */
    public function cancelAction(Registration $registration, string $hmac): ResponseInterface
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $variables = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * Update- and remove flags
         */
        $updateRegistration = false;
        $removeRegistration = false;

        $processPaymentCancelEvent = new ProcessPaymentCancelEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $removeRegistration,
            $registration,
            $this->request->getQueryParams(),
            $this
        );
        $this->eventDispatcher->dispatch($processPaymentCancelEvent);
        $variables = $processPaymentCancelEvent->getVariables();
        $updateRegistration = $processPaymentCancelEvent->getUpdateRegistration();
        $removeRegistration = $processPaymentCancelEvent->getRemoveRegistration();

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        if ($removeRegistration) {
            // First cancel depending registrations
            if ($registration->getAmountOfRegistrations() > 1) {
                $this->registrationService->cancelDependingRegistrations($registration);
            }
            $this->registrationRepository->remove($registration);
        }

        $this->view->assign('result', $variables);

        return $this->htmlResponse();
    }

    /**
     * Action can be called by payment provider to perform custom logic after the payment process
     */
    public function notifyAction(Registration $registration, string $hmac): ResponseInterface
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $variables = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        // If true, an external event listener requested the registration to be updated
        $updateRegistration = false;

        $processPaymentNotifyEvent = new ProcessPaymentNotifyEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $registration,
            $this->request->getQueryParams(),
            $this
        );
        $this->eventDispatcher->dispatch($processPaymentNotifyEvent);
        $variables = $processPaymentNotifyEvent->getVariables();
        $updateRegistration = $processPaymentNotifyEvent->getUpdateRegistration();

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $variables);

        return $this->htmlResponse();
    }

    /**
     * Checks if the given action can be called for the given registration / event and throws
     * an exception if action should not proceed
     *
     * @throws PaymentException
     */
    protected function proceedWithAction(Registration $registration, string $actionName): void
    {
        if ($registration->getEvent()->getEnablePayment() === false) {
            $message = LocalizationUtility::translate('payment.messages.paymentNotEnabled', 'SfEventMgt');
            throw new PaymentException($message, 1899934881);
        }

        if ($this->paymentService->paymentActionEnabled($registration->getPaymentmethod(), $actionName) === false) {
            $message = LocalizationUtility::translate('payment.messages.actionNotEnabled', 'SfEventMgt');
            throw new PaymentException($message, 1899934882);
        }

        if ($registration->getEvent()->getRestrictPaymentMethods()) {
            $selectedPaymentMethods = explode(',', $registration->getEvent()->getSelectedPaymentMethods());
            if (!in_array($registration->getPaymentmethod(), $selectedPaymentMethods)) {
                $message = LocalizationUtility::translate('payment.messages.paymentMethodNotAvailable', 'SfEventMgt');
                throw new PaymentException($message, 1899934884);
            }
        }

        $proceedWithPaymentActionEvent = new ProceedWithPaymentActionEvent($registration, $actionName);
        $this->eventDispatcher->dispatch($proceedWithPaymentActionEvent);

        if ($proceedWithPaymentActionEvent->getPerformPaidCheck() && $registration->getPaid()) {
            $message = LocalizationUtility::translate('payment.messages.paymentAlreadyProcessed', 'SfEventMgt');
            throw new PaymentException($message, 1899934883);
        }
    }

    /**
     * Checks the HMAC for the given action and registration
     */
    protected function validateHmacForAction(Registration $registration, string $hmac, string $action): void
    {
        $isValidHmac = $this->hashService->validateHmac(
            $action . '-' . $registration->getUid(),
            HashScope::PaymentAction->value,
            $hmac
        );
        if (!$isValidHmac) {
            $message = LocalizationUtility::translate('payment.messages.invalidHmac', 'SfEventMgt');
            throw new InvalidHashException($message, 1899934890);
        }
    }

    /**
     * Returns the payment Uri for the given action and registration
     */
    protected function getPaymentUriForAction(string $action, Registration $registration): string
    {
        $this->uriBuilder
            ->setCreateAbsoluteUri(true);

        return $this->uriBuilder->uriFor(
            $action,
            [
                'registration' => $registration,
                'hmac' => $this->hashService->hmac(
                    $action . 'Action-' . $registration->getUid(),
                    HashScope::PaymentAction->value
                ),
            ],
            'Payment',
            'sfeventmgt',
            'Pipayment'
        );
    }
}
