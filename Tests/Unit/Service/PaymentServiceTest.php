<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DERHANSEN\SfEventMgt\Payment\Invoice;
use DERHANSEN\SfEventMgt\Service\PaymentService;

/**
 * Class PaymentServiceTest
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentServiceTest extends UnitTestCase
{

    /**
     * @var PaymentService
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'] = [
            'invoice' => [
                'class' => 'DERHANSEN\\SfEventMgt\\Payment\\Invoice',
                'extkey' => 'sf_event_mgt'
            ],
            'transfer' => [
                'class' => 'DERHANSEN\\SfEventMgt\\Payment\\Transfer',
                'extkey' => 'sf_event_mgt'
            ]
        ];
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
     * DataProvider for getPaymentMethodsReturnsDefaultPaymentMethods
     *
     * @return array
     */
    public function getPaymentMethodsDataProvider()
    {
        return [
            'Default Payment Methods enabled' => [
                'extConf' => [
                    'enableInvoice' => true,
                    'enableTransfer' => true
                ],
                'expected' => ['invoice' => null, 'transfer' => null]
            ],
            'Invoice disabled' => [
                'extConf' => [
                    'enableInvoice' => false,
                    'enableTransfer' => true
                ],
                'expected' => ['transfer' => null]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getPaymentMethodsDataProvider
     * @return void
     */
    public function getPaymentMethodsReturnsDefaultPaymentMethods($extConf, $expected)
    {
        $this->subject = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Service\\PaymentService', ['translate'], [], '', false);
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sf_event_mgt'] = serialize($extConf);
        $this->assertEquals($expected, $this->subject->getPaymentMethods());
    }

    /**
     * @test
     * @return void
     */
    public function getPaymentInstanceReturnsInvoicePaymentInsance()
    {
        $this->subject = new PaymentService();
        $this->assertInstanceOf('DERHANSEN\SfEventMgt\Payment\Invoice', $this->subject->getPaymentInstance('invoice'));
    }

    /**
     * @test
     * @return void
     */
    public function getPaymentInstanceReturnsTransferPaymentInsance()
    {
        $this->subject = new PaymentService();
        $this->assertInstanceOf('DERHANSEN\SfEventMgt\Payment\Transfer', $this->subject->getPaymentInstance('transfer'));
    }

    /**
     * Data provider for paymentActionEnabledForDefaultPaymentMethodReturnsExpectedResult
     *
     * @return array
     */
    public function paymentActionEnabledForDefaultPaymentMethodDataProvider()
    {
        return [
            'redirectAction' => [
                'redirectAction',
                false
            ],
            'successAction' => [
                'successAction',
                false
            ],
            'failureAction' => [
                'failureAction',
                false
            ],
            'cancelAction' => [
                'cancelAction',
                false
            ],
            'notifyAction' => [
                'notifyAction',
                false
            ],
        ];
    }

    /**
     * @test
     * @dataProvider paymentActionEnabledForDefaultPaymentMethodDataProvider
     * @return void
     */
    public function paymentActionEnabledForDefaultPaymentMethodReturnsExpectedResult($action, $expected)
    {
        $this->subject = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Service\\PaymentService', ['getPaymentInstance'], [], '', false);
        $this->subject->expects($this->once())->method('getPaymentInstance')->will($this->returnValue(new Invoice()));

        $this->assertEquals($expected, $this->subject->paymentActionEnabled('invoice', $action));
    }

    /**
     * Data provider for paymentActionEnabledForCustomPaymentMethodReturnsExpectedResult
     *
     * @return array
     */
    public function paymentActionEnabledForCustomPaymentMethodDataProvider()
    {
        return [
            'redirectAction' => [
                'redirectAction',
                true
            ],
            'successAction' => [
                'successAction',
                true
            ],
            'failureAction' => [
                'failureAction',
                true
            ],
            'cancelAction' => [
                'cancelAction',
                true
            ],
            'notifyAction' => [
                'notifyAction',
                true
            ],
        ];
    }

    /**
     * @test
     * @dataProvider paymentActionEnabledForCustomPaymentMethodDataProvider
     * @return void
     */
    public function paymentActionEnabledForCustomPaymentMethodReturnsExpectedResult($action, $expected)
    {
        $mockPaymentInstance = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\Payment\\Invoice',
            [
                'isRedirectEnabled',
                'isSuccessLinkEnabled',
                'isFailureLinkEnabled',
                'isCancelLinkEnabled',
                'isNotifyLinkEnabled',
            ],
            [],
            '',
            false
        );

        $mockPaymentInstance->expects($this->any())->method('isRedirectEnabled')->will($this->returnValue(true));
        $mockPaymentInstance->expects($this->any())->method('isSuccessLinkEnabled')->will($this->returnValue(true));
        $mockPaymentInstance->expects($this->any())->method('isFailureLinkEnabled')->will($this->returnValue(true));
        $mockPaymentInstance->expects($this->any())->method('isCancelLinkEnabled')->will($this->returnValue(true));
        $mockPaymentInstance->expects($this->any())->method('isNotifyLinkEnabled')->will($this->returnValue(true));

        $this->subject = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Service\\PaymentService', ['getPaymentInstance'], [], '', false);
        $this->subject->expects($this->once())->method('getPaymentInstance')->will(
            $this->returnValue($mockPaymentInstance)
        );

        $this->assertEquals($expected, $this->subject->paymentActionEnabled('invoice', $action));
    }

}