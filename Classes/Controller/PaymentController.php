<?php
namespace DERHANSEN\SfEventMgt\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Payment\Exception\PaymentException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Security\Exception\InvalidHashException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * PaymentController
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentController extends AbstractController
{
    /**
     * Catches all PaymentExceptions and sets the Exception message to the response content
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        try {
            parent::processRequest($request, $response);
        } catch (\DERHANSEN\SfEventMgt\Exception $e) {
            $response->setContent('<div class="payment-error">' . $e->getMessage() . '</div>');
        } catch (\TYPO3\CMS\Extbase\Security\Exception\InvalidHashException $e) {
            $response->setContent('<div class="payment-error">' . $e->getMessage() . '</div>');
        }
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

        $values = [
            'sfEventMgtSettings' => $this->settings,
            'successUrl' => $this->getPaymentUriForAction('success', $registration),
            'failureUrl' => $this->getPaymentUriForAction('failure', $registration),
            'cancelUrl' => $this->getPaymentUriForAction('cancel', $registration),
            'notifyUrl' => $this->getPaymentUriForAction('notify', $registration),
            'registration' => $registration,
            'html' => ''
        ];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * If true, the externally called BeforeRedirect method requested, that the registration should be updated
         */
        $updateRegistration = false;

        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'BeforeRedirect' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, $registration, $this]
        );

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $values);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function successAction($registration, $hmac)
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $values = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * If true, the externally called ProcessSuccess method requested, that the registration should be updated
         */
        $updateRegistration = false;

        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'ProcessSuccess' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, $registration, GeneralUtility::_GET(), $this]
        );

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $values);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function failureAction($registration, $hmac)
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $values = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * Update- and remove flags
         */
        $updateRegistration = false;
        $removeRegistration = false;

        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'ProcessFailure' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, &$removeRegistration, $registration, GeneralUtility::_GET(), $this]
        );

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

        $this->view->assign('result', $values);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function cancelAction($registration, $hmac)
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $values = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * Update- and remove flags
         */
        $updateRegistration = false;
        $removeRegistration = false;

        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'ProcessCancel' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, &$removeRegistration, $registration, GeneralUtility::_GET(), $this]
        );

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

        $this->view->assign('result', $values);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function notifyAction($registration, $hmac)
    {
        $this->validateHmacForAction($registration, $hmac, $this->actionMethodName);
        $this->proceedWithAction($registration, $this->actionMethodName);

        $values = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * Initialize update-flag
         *
         * If true, the externally called ProcessNotify method requested, that the registration should be updated
         */
        $updateRegistration = false;

        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'ProcessNotify' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, $registration, GeneralUtility::_GET(), $this]
        );

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $values);
    }

    /**
     * Checks if the given action can be called for the given registration / event and throws
     * an exception if action should not proceed
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $actionName
     * @throws PaymentException
     * @return void
     */
    protected function proceedWithAction($registration, $actionName)
    {
        if ($registration->getEvent()->getEnablePayment() === false) {
            $message = LocalizationUtility::translate('payment.messages.paymentNotEnabled', 'sf_event_mgt');
            throw new PaymentException($message, 1899934881);
        }

        if ($this->paymentService->paymentActionEnabled($registration->getPaymentmethod(), $actionName) === false) {
            $message = LocalizationUtility::translate('payment.messages.actionNotEnabled', 'sf_event_mgt');
            throw new PaymentException($message, 1899934882);
        }

        if ($registration->getPaid()) {
            $message = LocalizationUtility::translate('payment.messages.paymentAlreadyProcessed', 'sf_event_mgt');
            throw new PaymentException($message, 1899934883);
        }

        if ($registration->getEvent()->getRestrictPaymentMethods()) {
            $selectedPaymentMethods = explode(',', $registration->getEvent()->getSelectedPaymentMethods());
            if (!in_array($registration->getPaymentmethod(), $selectedPaymentMethods)) {
                $message = LocalizationUtility::translate('payment.messages.paymentMethodNotAvailable', 'sf_event_mgt');
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
            $message = LocalizationUtility::translate('payment.messages.invalidHmac', 'sf_event_mgt');
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
                'hmac' => $this->hashService->generateHmac($action . 'Action-' . $registration->getUid())
            ],
            'Payment',
            'sfeventmgt',
            'Pipayment'
        );
    }
}
