<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Payment;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Payment\AbstractPayment.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AbstractPaymentTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Payment\AbstractPayment
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMockForAbstractClass('DERHANSEN\\SfEventMgt\\Payment\\AbstractPayment');
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
     * @test
     * @return void
     */
    public function isEnableRedirectReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->isRedirectEnabled());
    }

    /**
     * @test
     * @return void
     */
    public function isCancelLinkEnabledReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->isCancelLinkEnabled());
    }

    /**
     * @test
     * @return void
     */
    public function isNotifyLinkEnabledReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->isNotifyLinkEnabled());
    }

    /**
     * @test
     * @return void
     */
    public function isSuccessLinkEnabledReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->isSuccessLinkEnabled());
    }

    /**
     * @test
     * @return void
     */
    public function isFailureLinkEnabledReturnsDefaultValue()
    {
        $this->assertFalse($this->subject->isFailureLinkEnabled());
    }
}
