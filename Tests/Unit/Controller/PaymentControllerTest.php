<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\PaymentController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentControllerTest extends UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Controller\PaymentController
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\Controller\\PaymentController',
            [
                'redirect',
                'validateHmacForAction',
                'proceedWithAction'
            ],
            [],
            '',
            false
        );
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * Test if redirect signal gets emitted
     *
     * @test
     * @return void
     */
    public function redirectActionCallsBeforeRedirectSignal()
    {
        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder', ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService', [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'sfEventMgtSettings' => null,
            'successUrl' => null,
            'failureUrl' => null,
            'cancelUrl' => null,
            'notifyUrl' => null,
            'registration' => $mockRegistration,
            'html' => ''
        ];
        $updateRegistration = false;
        $arguments = [&$values, &$updateRegistration, $mockRegistration, $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            'DERHANSEN\SfEventMgt\Controller\PaymentController',
            'redirectActionBeforeRedirectPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->redirectAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if success signal gets emitted
     *
     * @test
     * @return void
     */
    public function successActionCallsProcessSuccessSignal()
    {
        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder', ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService', [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $arguments = [&$values, &$updateRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            'DERHANSEN\SfEventMgt\Controller\PaymentController',
            'successActionProcessSuccessPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->successAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if failure signal gets emitted
     *
     * @test
     * @return void
     */
    public function failureActionCallsProcessFailureSignal()
    {
        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder', ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService', [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $removeRegistration = false;
        $arguments = [&$values, &$updateRegistration, &$removeRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            'DERHANSEN\SfEventMgt\Controller\PaymentController',
            'failureActionProcessFailurePaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->failureAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if cancel signal gets emitted
     *
     * @test
     * @return void
     */
    public function cancelActionCallsProcessCancelSignal()
    {
        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder', ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService', [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $removeRegistration = false;
        $arguments = [&$values, &$updateRegistration, &$removeRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            'DERHANSEN\SfEventMgt\Controller\PaymentController',
            'cancelActionProcessCancelPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->cancelAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if notify signal gets emitted
     *
     * @test
     * @return void
     */
    public function notifyActionCallsProcessNotifySignal()
    {
        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder', ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService', [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $arguments = [&$values, &$updateRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher', ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            'DERHANSEN\SfEventMgt\Controller\PaymentController',
            'notifyActionProcessNotifyPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->notifyAction($mockRegistration, 'a-hmac');
    }

}
