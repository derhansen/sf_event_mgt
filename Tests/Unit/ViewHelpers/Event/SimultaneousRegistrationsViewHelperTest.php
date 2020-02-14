<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Event;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\ViewHelpers\Event\SimultaneousRegistrationsViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test cases for SimultaneousRegistrationsViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SimultaneousRegistrationsViewHelperTest extends UnitTestCase
{
    /**
     * Viewhelper
     *
     * @var \DERHANSEN\SfEventMgt\ViewHelpers\Event\SimultaneousRegistrationsViewHelper
     */
    protected $viewhelper = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->viewhelper = new SimultaneousRegistrationsViewHelper();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->viewhelper);
    }

    /**
     * Data provider for simultaneousRegistrations
     *
     * @return array
     */
    public function simultaneousRegistrationsDataProvider()
    {
        return [
            'maxRegistrationsAndFreePlacesEqual' => [
                5,
                1,
                1,
                false,
                [
                    1 => 1
                ]
            ],
            'moreMaxRegistrationsThanFreePlaces' => [
                5,
                1,
                2,
                false,
                [
                    1 => 1
                ]
            ],
            'moreFreePlacesThanMaxRegistrations' => [
                5,
                10,
                1,
                false,
                [
                    1 => 1
                ]
            ],
            'moreFreePlacesThanMaxRegistrationsWithSimultaneousAllowed' => [
                5,
                10,
                5,
                false,
                [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ]
            ],
            'noFreePlacesAvailable' => [
                5,
                0,
                1,
                false,
                [
                    0 => 0
                ]
            ],
            'noFreePlacesAndNoMaxRegistrations' => [
                5,
                0,
                0,
                false,
                [
                    0 => 0
                ]
            ],
            'noMaxParticipants' => [
                0,
                0,
                3,
                false,
                [
                    1 => 1,
                    2 => 2,
                    3 => 3
                ]
            ],
            'SimultaneousAllowedWithWaitlistAndNotEnoughFreePlacesForFullRegistration' => [
                5,
                1,
                2,
                true,
                [
                    1 => 1, // Must only show one possible registration (which will not be on the waitlist)
                ]
            ],
            'SimultaneousAllowedWithWaitlistAndNoFreePlacesforFullRegistration' => [
                5,
                0,
                2,
                true,
                [
                    1 => 1,
                    2 => 2,
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider simultaneousRegistrationsDataProvider
     * @param mixed $maxParticipants
     * @param mixed $freePlaces
     * @param mixed $maxRegistrations
     * @param mixed $waitlist
     * @param mixed $expected
     * @return void
     */
    public function viewHelperReturnsExpectedValues($maxParticipants, $freePlaces, $maxRegistrations, $waitlist, $expected)
    {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects($this->any())->method('getFreePlaces')->will($this->returnValue($freePlaces));
        $mockEvent->expects($this->any())->method('getMaxParticipants')->will($this->returnValue($maxParticipants));
        $mockEvent->expects($this->any())->method('getEnableWaitlist')->will($this->returnValue($waitlist));
        $mockEvent->expects($this->any())->method('getMaxRegistrationsPerUser')->will($this->returnValue($maxRegistrations));
        $this->viewhelper->setArguments(['event' => $mockEvent]);
        $actual = $this->viewhelper->render();
        $this->assertEquals($expected, $actual);
    }
}
