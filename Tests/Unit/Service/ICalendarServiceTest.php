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

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\ICalendarService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarServiceTest extends UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Service\ICalendarService
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Service\ICalendarService();
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
     * @expectedException \RuntimeException
     * @return void
     */
    public function downloadiCalendarFileThrowsExceptionIfNoDefaultStorageFound()
    {
        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event',
            [], [], '', false);

        $mockIcalendarService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ICalendarService',
            ['getiCalendarContent'], [], '', false);

        $mockResourceFactory = $this->getMock('TYPO3\\CMS\\Core\\Resource\\ResourceFactory',
            ['getDefaultStorage'], [], '', false);
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
            $this->returnValue(null));
        $this->inject($mockIcalendarService, 'resourceFactory', $mockResourceFactory);

        $mockIcalendarService->downloadiCalendarFile($mockEvent);
    }

    /**
     * @test
     * @return void
     */
    public function downloadiCalendarFileDumpsCsvFile()
    {
        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event',
            [], [], '', false);

        $mockICalendarService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ICalendarService',
            ['getiCalendarContent'], [], '', false);
        $mockICalendarService->expects($this->once())->method('getiCalendarContent')->with($mockEvent)->will(
            $this->returnValue('iCalendar Data'));

        $mockFile = $this->getMock('TYPO3\\CMS\\Core\\Resource\\File', [], [], '', false);
        $mockFile->expects($this->once())->method('setContents')->with('iCalendar Data');

        $mockStorageRepository = $this->getMock('TYPO3\CMS\Core\Resource\StorageRepository',
            ['getFolder', 'createFile', 'dumpFileContents'], [], '', false);
        $mockStorageRepository->expects($this->at(0))->method('getFolder')->with('_temp_');
        $mockStorageRepository->expects($this->at(1))->method('createFile')->will($this->returnValue($mockFile));
        $mockStorageRepository->expects($this->at(2))->method('dumpFileContents');

        $mockResourceFactory = $this->getMock('TYPO3\\CMS\\Core\\Resource\\ResourceFactory',
            ['getDefaultStorage'], [], '', false);
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
            $this->returnValue($mockStorageRepository));
        $this->inject($mockICalendarService, 'resourceFactory', $mockResourceFactory);

        $mockICalendarService->downloadiCalendarFile($mockEvent);
    }

    /**
     * @test
     * @return void
     */
    public function getiCalendarContentAssignsVariablesToView()
    {
        $_SERVER['HTTP_HOST'] = 'myhostname.tld';

        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event',
            [], [], '', false);

        $iCalendarView = $this->getMock('TYPO3\\CMS\\Fluid\\View\\StandaloneView', [], [], '', false);
        $iCalendarView->expects($this->once())->method('setFormat')->with('txt');
        $iCalendarView->expects($this->once())->method('assignMultiple')->with([
            'event' => $mockEvent,
            'typo3Host' => 'myhostname.tld'
        ]);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            [], [], '', false);
        $objectManager->expects($this->once())->method('get')->will($this->returnValue($iCalendarView));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $fluidStandaloneService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\FluidStandaloneService',
            [], [], '', false);
        $fluidStandaloneService->expects($this->any())->method('getTemplateFolders')->will($this->returnValue([]));
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $this->subject->getiCalendarContent($mockEvent);
    }
}