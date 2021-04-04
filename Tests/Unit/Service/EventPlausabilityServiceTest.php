<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\EventPlausabilityService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\EventPlausabilityService.
 */
class EventPlausabilityServiceTest extends UnitTestCase
{
    public function isStartDateBeforeEndDateDataProvider(): array
    {
        return [
            'no dates' => [
                0,
                0,
                true
            ],
            'startdate only' => [
               strtotime('2021-03-01T10:00:00+00:00'),
                0,
                true
            ],
            'startdate before enddate' => [
                strtotime('2021-03-01T10:00:00+00:00'),
                strtotime('2021-03-01T11:00:00+00:00'),
                true
            ],
            'enddate before startdate' => [
                strtotime('2021-03-01T11:00:00+00:00'),
                strtotime('2021-03-01T10:00:00+00:00'),
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider isStartDateBeforeEndDateDataProvider
     */
    public function isStartDateBeforeEndDateReturnsExpectedResults($startdate, $enddate, $expected)
    {
        $dataHandlerHooks = $this->getAccessibleMock(EventPlausabilityService::class, ['dummy'], [], '', false);
        $this->assertEquals($expected, $dataHandlerHooks->_call('isStartDateBeforeEndDate', $startdate, $enddate));
    }
}
