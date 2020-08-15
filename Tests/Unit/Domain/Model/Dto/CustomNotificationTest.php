<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotificationTest
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CustomNotificationTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new CustomNotification();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getTemplateReturnsInitialValue()
    {
        $this->assertEquals('', $this->subject->getTemplate());
    }

    /**
     * @test
     */
    public function setTemplateSetsValueForString()
    {
        $this->subject->setTemplate('a-template');
        $this->assertEquals('a-template', $this->subject->getTemplate());
    }

    /**
     * @test
     */
    public function getRecipientsReturnsInitialValue()
    {
        $this->assertEquals(CustomNotification::RECIPIENTS_CONFIRMED, $this->subject->getRecipients());
    }

    /**
     * @test
     */
    public function setRecipientsSetsValueForInteger()
    {
        $this->subject->setRecipients(CustomNotification::RECIPIENTS_ALL);
        $this->assertEquals(CustomNotification::RECIPIENTS_ALL, $this->subject->getRecipients());
    }

    /**
     * @test
     */
    public function getOverwriteSubjectReturnsInitialValue()
    {
        $this->assertEquals('', $this->subject->getOverwriteSubject());
    }

    /**
     * @test
     */
    public function setOverwriteSubjectSetsValueForString()
    {
        $this->subject->setOverwriteSubject('subject');
        $this->assertEquals('subject', $this->subject->getOverwriteSubject());
    }

    /**
     * @test
     */
    public function getAdditionalMessageReturnsInitialValue()
    {
        $this->assertEquals('', $this->subject->getAdditionalMessage());
    }

    /**
     * @test
     */
    public function setAdditionalMessageSetsValueForString()
    {
        $this->subject->setAdditionalMessage('message');
        $this->assertEquals('message', $this->subject->getAdditionalMessage());
    }
}
