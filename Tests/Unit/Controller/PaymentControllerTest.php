<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Controller\PaymentController;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

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
            PaymentController::class,
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
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
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

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'redirectActionBeforeRedirectPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
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
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $arguments = [&$values, &$updateRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'successActionProcessSuccessPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
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
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $removeRegistration = false;
        $arguments = [&$values, &$updateRegistration, &$removeRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'failureActionProcessFailurePaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
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
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $removeRegistration = false;
        $arguments = [&$values, &$updateRegistration, &$removeRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'cancelActionProcessCancelPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
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
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $arguments = [&$values, &$updateRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'notifyActionProcessNotifyPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $this->subject->notifyAction($mockRegistration, 'a-hmac');
    }
}
