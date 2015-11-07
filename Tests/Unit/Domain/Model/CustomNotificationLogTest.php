<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

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

use DERHANSEN\SfEventMgt\Domain\Model\Event;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CustomNotificationLogTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog();
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
     * Test if initial value for event is returned
     *
     * @test
     * @return void
     */
    public function getEventReturnsInitialValueForEvent()
    {
        $this->assertSame(
            null,
            $this->subject->getEvent()
        );
    }

    /**
     * Test if event can be set
     *
     * @test
     * @return void
     */
    public function setEventForEventSetsEvent()
    {
        $event = new Event();
        $this->subject->setEvent($event);
        $this->assertEquals($event, $this->subject->getEvent());
    }

    /**
     * Test if details can be set
     *
     * @test
     * @return void
     */
    public function setDetailsForStringSetsDetails()
    {
        $this->subject->setDetails('Description');
        $this->assertEquals('Description', $this->subject->getDetails());
    }

    /**
     * Test if emailsSent can be set
     *
     * @test
     * @return void
     */
    public function setEmailsSentForIntSetsEmailsSent()
    {
        $this->subject->setEmailsSent(100);
        $this->assertEquals(100, $this->subject->getEmailsSent());
    }

    /**
     * Test if tstamp can be set
     *
     * @test
     * @return void
     */
    public function setTstampForDateTimeSetsTstamp()
    {
        $tstamp = new \DateTime('01.01.2014 10:00:00');
        $this->subject->setTstamp($tstamp);
        $this->assertEquals($tstamp, $this->subject->getTstamp());
    }

    /**
     * Test if backend user can be set to field cruser_id
     *
     * @test
     * @return void
     */
    public function setCruserIdForBackendUserSetsBackendUser()
    {
        $beuser = new \TYPO3\CMS\Beuser\Domain\Model\BackendUser();
        $this->subject->setCruserId($beuser);
        $this->assertEquals($beuser, $this->subject->getCruserId());
    }
}
