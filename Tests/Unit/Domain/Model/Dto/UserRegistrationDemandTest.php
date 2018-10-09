<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class UserRegistrationDemandTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new UserRegistrationDemand();
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
     */
    public function getDisplayModeReturnsInitialValue()
    {
        $this->assertSame(
            'all',
            $this->subject->getDisplayMode()
        );
    }

    /**
     * @test
     */
    public function setDisplayModeForStringSetsDisplayMode()
    {
        $this->subject->setDisplayMode('past');

        $this->assertAttributeEquals(
            'past',
            'displayMode',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStoragePageReturnsInitialValue()
    {
        $this->assertSame(
            null,
            $this->subject->getStoragePage()
        );
    }

    /**
     * @test
     */
    public function setStoragePageForStringSetsStoragePage()
    {
        $this->subject->setStoragePage('1,2,3');

        $this->assertAttributeEquals(
            '1,2,3',
            'storagePage',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCurrentDateTimeReturnsDateTimeObjectIfNoValueSet()
    {
        $this->assertInstanceOf('DateTime', $this->subject->getCurrentDateTime());
    }

    /**
     * @test
     */
    public function getCurrentDateTimeReturnsGivenValueIfValueSet()
    {
        $this->subject->setCurrentDateTime(new \DateTime('01.01.2014'));
        $this->assertEquals(
            new \DateTime('01.01.2014'),
            $this->subject->getCurrentDateTime()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getOrderFieldReturnsEmptyStringIfNoValueSet()
    {
        $this->assertSame(
            '',
            $this->subject->getOrderField()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getOrderFieldReturnsGivenValueIfValueSet()
    {
        $this->subject->setOrderField('title');
        $this->assertSame(
            'title',
            $this->subject->getOrderField()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getOrderDirectionReturnsEmptyStringIfNoValueSet()
    {
        $this->assertSame(
            '',
            $this->subject->getOrderDirection()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getOrderDirectionReturnsGivenValueIfValueSet()
    {
        $this->subject->setOrderDirection('asc');
        $this->assertSame(
            'asc',
            $this->subject->getOrderDirection()
        );
    }

    /**
     * @test
     * @return void
     */
    public function getUserReturnsInitialValue()
    {
        $this->assertNull($this->subject->getUser());
    }

    /**
     * @test
     * @return void
     */
    public function setUserSetsUser()
    {
        $user = new FrontendUser();
        $this->subject->setUser($user);
        $this->assertSame($this->subject->getUser(), $user);
    }
}
