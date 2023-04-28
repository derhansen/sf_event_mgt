<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Event;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\ViewHelpers\Event\SimultaneousRegistrationsViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test cases for SimultaneousRegistrationsViewHelper
 */
class SimultaneousRegistrationsViewHelperTest extends UnitTestCase
{
    protected SimultaneousRegistrationsViewHelper $viewhelper;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->viewhelper = new SimultaneousRegistrationsViewHelper();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->viewhelper);
    }

    public static function simultaneousRegistrationsDataProvider(): array
    {
        return [
            'maxRegistrationsAndFreePlacesEqual' => [
                5,
                1,
                1,
                false,
                [
                    1 => 1,
                ],
            ],
            'moreMaxRegistrationsThanFreePlaces' => [
                5,
                1,
                2,
                false,
                [
                    1 => 1,
                ],
            ],
            'moreFreePlacesThanMaxRegistrations' => [
                5,
                10,
                1,
                false,
                [
                    1 => 1,
                ],
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
                    5 => 5,
                ],
            ],
            'noFreePlacesAvailable' => [
                5,
                0,
                1,
                false,
                [
                    0 => 0,
                ],
            ],
            'noFreePlacesAndNoMaxRegistrations' => [
                5,
                0,
                0,
                false,
                [
                    0 => 0,
                ],
            ],
            'noMaxParticipants' => [
                0,
                0,
                3,
                false,
                [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                ],
            ],
            'SimultaneousAllowedWithWaitlistAndNotEnoughFreePlacesForFullRegistration' => [
                5,
                1,
                2,
                true,
                [
                    1 => 1, // Must only show one possible registration (which will not be on the waitlist)
                ],
            ],
            'SimultaneousAllowedWithWaitlistAndNoFreePlacesforFullRegistration' => [
                5,
                0,
                2,
                true,
                [
                    1 => 1,
                    2 => 2,
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider simultaneousRegistrationsDataProvider
     */
    public function viewHelperReturnsExpectedValues(
        int $maxParticipants,
        int $freePlaces,
        int $maxRegistrations,
        bool $waitlist,
        array $expected
    ): void {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getFreePlaces')->willReturn($freePlaces);
        $mockEvent->expects(self::any())->method('getMaxParticipants')->willReturn($maxParticipants);
        $mockEvent->expects(self::any())->method('getEnableWaitlist')->willReturn($waitlist);
        $mockEvent->expects(self::any())->method('getMaxRegistrationsPerUser')->willReturn($maxRegistrations);
        $this->viewhelper->setArguments(['event' => $mockEvent]);
        $actual = $this->viewhelper->render();
        self::assertEquals($expected, $actual);
    }
}
