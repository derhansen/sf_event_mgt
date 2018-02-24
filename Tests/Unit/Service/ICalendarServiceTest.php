<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Exception;
use DERHANSEN\SfEventMgt\Service\ICalendarService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Resource\ResourceFactory;

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
     * @expectedException Exception
     * @return void
     */
    public function downloadiCalendarFileThrowsExceptionIfNoDefaultStorageFound()
    {
        $mockEvent = $this->getMock(Event::class, [], [], '', false);

        /** @var ICalendarService $mockIcalendarService */
        $mockIcalendarService = $this->getMock(ICalendarService::class, ['getiCalendarContent'], [], '', false);

        $mockResourceFactory = $this->getMock(ResourceFactory::class, ['getDefaultStorage'], [], '', false);
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will($this->returnValue(null));
        $this->inject($mockIcalendarService, 'resourceFactory', $mockResourceFactory);

        $mockIcalendarService->downloadiCalendarFile($mockEvent);
    }

    /**
     * @test
     * @return void
     */
    public function downloadiCalendarFileDumpsCsvFile()
    {
        $mockEvent = $this->getMock(Event::class,
            [], [], '', false);

        $mockICalendarService = $this->getMock(ICalendarService::class,
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

        $mockResourceFactory = $this->getMock(ResourceFactory::class,
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

        $mockEvent = $this->getMock(Event::class,
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