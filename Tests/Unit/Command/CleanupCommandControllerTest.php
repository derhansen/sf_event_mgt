<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Command;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Command\CleanupCommandController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CleanupCommandControllerTest extends UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Command\CleanupCommandController
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Command\CleanupCommandController();
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
    public function cleanupCommandHandlesExpiredRegistrationsAndCleansCacheTest()
    {

        // Inject configuration and configurationManager
        $configuration = [
            'plugin.' => [
                'tx_sfeventmgt.' => [
                    'settings.' => [
                        'clearCacheUids' => '1,2,3',
                        'registration.' => [
                            'deleteExpiredRegistrations' => 0
                        ]
                    ]
                ]
            ]
        ];

        $configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
            ['getConfiguration'], [], '', false);
        $configurationManager->expects($this->once())->method('getConfiguration')->will(
            $this->returnValue($configuration));
        $this->inject($this->subject, 'configurationManager', $configurationManager);

        $registrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['handleExpiredRegistrations'], [], '', false);
        $registrationService->expects($this->once())->method('handleExpiredRegistrations')->with(0)->will(
            $this->returnValue($configuration));
        $this->inject($this->subject, 'registrationService', $registrationService);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            ['clearCacheForConfiguredUids'], [], '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids')->
        with($configuration['plugin.']['tx_sfeventmgt.']['settings.']);
        $this->inject($this->subject, 'utilityService', $utilityService);

        $this->subject->cleanupCommand();
    }
}
