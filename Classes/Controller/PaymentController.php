<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Controller;

use DERHANSEN\SfEventMgt\Event\ProcessPaymentCancelEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentFailureEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentInitializeEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentNotifyEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentSuccessEvent;
use DERHANSEN\SfEventMgt\Payment\Exception\PaymentException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Security\Exception\InvalidHashException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * PaymentController
 */
class PaymentController extends AbstractController
{
    /**
     * Catches all PaymentExceptions and sets the Exception message to the response content
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        try {
            $response = parent::processRequest($request);
        } catch (\DERHANSEN\SfEventMgt\Exception $e) {
            // @todo return Response
            //$response->setContent('<div class="payment-error">' . $e->getMessage() . '</div>');
        } catch (\TYPO3\CMS\Extbase\Security\Exception\InvalidHashException $e) {
            // @todo return Response
            //$response->setContent('<div class="payment-error">' . $e->getMessage() . '</div>');
        }
        // @todo handle else case where exception is not catched

        return $response;
    }

    /**
     * Redirect to payment provider
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function redirectAction($registration, $hmac)
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

        /**
         * If true, the externally called BeforeRedirect method requested, that the registration should be updated
         */
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
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function successAction($registration, $hmac)
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $variables = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * If true, the externally called ProcessSuccess method requested, that the registration should be updated
         */
        $updateRegistration = false;

        $getVariables = is_array(GeneralUtility::_GET()) ? GeneralUtility::_GET() : [];
        $processPaymentSuccessEvent = new ProcessPaymentSuccessEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $registration,
            $getVariables,
            $this
        );
        $this->eventDispatcher->dispatch($processPaymentSuccessEvent);
        $variables = $processPaymentSuccessEvent->getVariables();
        $updateRegistration = $processPaymentSuccessEvent->getUpdateRegistration();

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $variables);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function failureAction($registration, $hmac)
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

        $getVariables = is_array(GeneralUtility::_GET()) ? GeneralUtility::_GET() : [];
        $processPaymentFailureEvent = new ProcessPaymentFailureEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $removeRegistration,
            $registration,
            $getVariables,
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
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function cancelAction($registration, $hmac)
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

        $getVariables = is_array(GeneralUtility::_GET()) ? GeneralUtility::_GET() : [];
        $processPaymentCancelEvent = new ProcessPaymentCancelEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $removeRegistration,
            $registration,
            $getVariables,
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
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function notifyAction($registration, $hmac)
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $variables = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * Initialize update-flag
         *
         * If true, the externally called ProcessNotify method requested, that the registration should be updated
         */
        $updateRegistration = false;

        $getVariables = is_array(GeneralUtility::_GET()) ? GeneralUtility::_GET() : [];
        $processPaymentNotifyEvent = new ProcessPaymentNotifyEvent(
            $variables,
            $paymentMethod,
            $updateRegistration,
            $registration,
            $getVariables,
            $this
        );
        $this->eventDispatcher->dispatch($processPaymentNotifyEvent);
        $variables = $processPaymentNotifyEvent->getVariables();
        $updateRegistration = $processPaymentNotifyEvent->getUpdateRegistration();

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $variables);
    }

    /**
     * Checks if the given action can be called for the given registration / event and throws
     * an exception if action should not proceed
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $actionName
     * @throws PaymentException
     */
    protected function proceedWithAction($registration, $actionName)
    {
        if ($registration->getEvent()->getEnablePayment() === false) {
            $message = LocalizationUtility::translate('payment.messages.paymentNotEnabled', 'SfEventMgt');
            throw new PaymentException($message, 1899934881);
        }

        if ($this->paymentService->paymentActionEnabled($registration->getPaymentmethod(), $actionName) === false) {
            $message = LocalizationUtility::translate('payment.messages.actionNotEnabled', 'SfEventMgt');
            throw new PaymentException($message, 1899934882);
        }

        if ($registration->getPaid()) {
            $message = LocalizationUtility::translate('payment.messages.paymentAlreadyProcessed', 'SfEventMgt');
            throw new PaymentException($message, 1899934883);
        }

        if ($registration->getEvent()->getRestrictPaymentMethods()) {
            $selectedPaymentMethods = explode(',', $registration->getEvent()->getSelectedPaymentMethods());
            if (!in_array($registration->getPaymentmethod(), $selectedPaymentMethods)) {
                $message = LocalizationUtility::translate('payment.messages.paymentMethodNotAvailable', 'SfEventMgt');
                throw new PaymentException($message, 1899934884);
            }
        }
    }

    /**
     * Checks the HMAC for the given action and registration
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     * @param string $action
     * @throws InvalidHashException
     */
    protected function validateHmacForAction($registration, $hmac, $action)
    {
        $result = $this->hashService->validateHmac($action . '-' . $registration->getUid(), $hmac);
        if (!$result) {
            $message = LocalizationUtility::translate('payment.messages.invalidHmac', 'SfEventMgt');
            throw new InvalidHashException($message, 1899934890);
        }
    }

    /**
     * Returns the payment Uri for the given action and registration
     *
     * @param string $action
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @throws \TYPO3\CMS\Extbase\Security\Exception\InvalidArgumentForHashGenerationException
     * @return string
     */
    protected function getPaymentUriForAction($action, $registration)
    {
        $this->uriBuilder
            ->setCreateAbsoluteUri(true);

        return $this->uriBuilder->uriFor(
            $action,
            [
                'registration' => $registration,
                'hmac' => $this->hashService->generateHmac($action . 'Action-' . $registration->getUid()),
            ],
            'Payment',
            'sfeventmgt',
            'Pipayment'
        );
    }
}
