<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Payment;

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
use DERHANSEN\SfEventMgt\Payment\AbstractPayment;

/**
 * Test case for class DERHANSEN\SfEventMgt\Payment\AbstractPayment.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AbstractPaymentTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
        $this->subject = $this->getAccessibleMockForAbstractClass(AbstractPayment::class);
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