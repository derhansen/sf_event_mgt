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

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use \DERHANSEN\SfEventMgt\Exception;
use \DERHANSEN\SfEventMgt\Service\ExportService;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\StorageRepository;

/**
 * Class ExportServiceTest
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ExportServiceTest extends UnitTestCase
{

    /**
     * @var ExportService
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $registrationRepository = $this->getMock(RegistrationRepository::class, [], [], '', false);
        $resourceFactory = $this->getMock(ResourceFactory::class, [], [], '', false);
        $this->subject = new ExportService($registrationRepository, $resourceFactory);
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
     * Data Provider for unit tests
     *
     * @return array
     */
    public function wrongFieldValuesInTypoScriptDataProvider()
    {
        return [
            'wrongFieldValuesInTypoScript' => [
                1,
                'uid, firstname, wrongfield'
            ],
        ];
    }

    /**
     * Data Provider for unit tests
     *
     * @return array
     */
    public function fieldValuesInTypoScriptDataProvider()
    {
        return [
            'fieldValuesWithWhitespacesInTypoScript' => [
                1,
                [
                    'fields' => 'uid, firstname, lastname',
                    'fieldDelimiter' => ',',
                    'fieldQuoteCharacter' => '"',
                    'prependBOM' => 0
                ],
                '"uid","firstname","lastname"' . chr(10) . '"1","Max","Mustermann"' . chr(10)
            ],
            'fieldValuesWithoutWhitespacesInTypoScript' => [
                1,
                [
                    'fields' => 'uid, firstname, lastname',
                    'fieldDelimiter' => ',',
                    'fieldQuoteCharacter' => '"',
                    'prependBOM' => 0
                ],
                '"uid","firstname","lastname"' . chr(10) . '"1","Max","Mustermann"' . chr(10)
            ],
            'fieldValuesWithDifferentDelimiter' => [
                1,
                [
                    'fields' => 'uid, firstname, lastname',
                    'fieldDelimiter' => ';',
                    'fieldQuoteCharacter' => '"',
                    'prependBOM' => 0
                ],
                '"uid";"firstname";"lastname"' . chr(10) . '"1";"Max";"Mustermann"' . chr(10)
            ],
            'fieldValuesWithDifferentQuoteCharacter' => [
                1,
                [
                    'fields' => 'uid, firstname, lastname',
                    'fieldDelimiter' => ',',
                    'fieldQuoteCharacter' => '\'',
                    'prependBOM' => 0
                ],
                '\'uid\',\'firstname\',\'lastname\'' . chr(10) . '\'1\',\'Max\',\'Mustermann\'' . chr(10)
            ],
            'fieldValuesWithBomSetting' => [
                1,
                [
                    'fields' => 'uid, firstname, lastname',
                    'fieldDelimiter' => ';',
                    'fieldQuoteCharacter' => '"',
                    'prependBOM' => 1
                ],
                chr(239) . chr(187) . chr(191) . '"uid";"firstname";"lastname"' . chr(10) . '"1";"Max";"Mustermann"' . chr(10)
            ],
        ];
    }

    /**
     * @test
     * @expectedException Exception
     * @return void
     */
    public function exportServiceThrowsExceptionWhenFieldIsNotValidForRegistrationModel()
    {
        $mockRegistration = $this->getMock(Registration::class, ['_hasProperty'], [], '', false);
        $mockRegistration->expects($this->at(0))->method('_hasProperty')->with(
            $this->equalTo('uid'))->will($this->returnValue(true));
        $mockRegistration->expects($this->at(1))->method('_hasProperty')->with(
            $this->equalTo('firstname'))->will($this->returnValue(true));
        $mockRegistration->expects($this->at(2))->method('_hasProperty')->with(
            $this->equalTo('wrongfield'))->will($this->returnValue(false));

        $allRegistrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $allRegistrations->attach($mockRegistration);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\Registration',
            ['findByEvent'], [], '', false);
        $registrationRepository->expects($this->once())->method('findByEvent')->will(
            $this->returnValue($allRegistrations)
        );
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->exportRegistrationsCsv(
            1,
            [
                'fields' => 'uid, firstname, wrongfield',
                'fieldDelimiter' => ',',
                'fieldQuoteCharacter' => '"'
            ]
        );
    }

    /**
     * @test
     * @dataProvider fieldValuesInTypoScriptDataProvider
     */
    public function exportServiceWorksWithDifferentFormattedTypoScriptValues($uid, $fields, $expected)
    {
        $mockRegistration = $this->getMock(Registration::class, ['_hasProperty', '_getCleanProperty'], [], '', false);
        $mockRegistration->expects($this->at(0))->method('_hasProperty')->with(
            $this->equalTo('uid'))->will($this->returnValue(true));
        $mockRegistration->expects($this->at(2))->method('_hasProperty')->with(
            $this->equalTo('firstname'))->will($this->returnValue(true));
        $mockRegistration->expects($this->at(4))->method('_hasProperty')->with(
            $this->equalTo('lastname'))->will($this->returnValue(true));
        $mockRegistration->expects($this->at(1))->method('_getCleanProperty')->with(
            $this->equalTo('uid'))->will($this->returnValue(1));
        $mockRegistration->expects($this->at(3))->method('_getCleanProperty')->with(
            $this->equalTo('firstname'))->will($this->returnValue('Max'));
        $mockRegistration->expects($this->at(5))->method('_getCleanProperty')->with(
            $this->equalTo('lastname'))->will($this->returnValue('Mustermann'));

        $allRegistrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $allRegistrations->attach($mockRegistration);

        $registrationRepository = $this->getMock(RegistrationRepository::class, ['findByEvent'], [], '', false);
        $registrationRepository->expects($this->once())->method('findByEvent')->will(
            $this->returnValue($allRegistrations));
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $returnValue = $this->subject->exportRegistrationsCsv($uid, $fields);
        $this->assertSame($expected, $returnValue);
    }

    /**
     * @test
     * @expectedException Exception
     * @return void
     */
    public function downloadRegistrationsCsvThrowsExceptionIfDefaultStorageNotFound()
    {
        $mockResourceFactory = $this->getMock(ResourceFactory::class, [], [], '', false);
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will($this->returnValue(null));
        $this->inject($this->subject, 'resourceFactory', $mockResourceFactory);
        $this->subject->downloadRegistrationsCsv(1, ['settings']);
    }

    /**
     * @test
     * @return void
     */
    public function downloadRegistrationsCsvDumpsRegistrationsContent()
    {
        $mockExportService = $this->getMock(ExportService::class, ['exportRegistrationsCsv'], [], '', false);
        $mockExportService->expects($this->once())->method('exportRegistrationsCsv')->will(
            $this->returnValue('CSV-DATA')
        );

        $mockFile = $this->getMock('TYPO3\\CMS\\Core\\Resource\\File', [], [], '', false);
        $mockFile->expects($this->once())->method('setContents')->with('CSV-DATA');

        $mockStorageRepository = $this->getMock(StorageRepository::class,
            ['getFolder', 'createFile', 'dumpFileContents'], [], '', false);
        $mockStorageRepository->expects($this->at(0))->method('getFolder')->with('_temp_');
        $mockStorageRepository->expects($this->at(1))->method('createFile')->will($this->returnValue($mockFile));
        $mockStorageRepository->expects($this->at(2))->method('dumpFileContents');

        $mockResourceFactory = $this->getMock(ResourceFactory::class, ['getDefaultStorage'], [], '', false);
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
            $this->returnValue($mockStorageRepository));
        $this->inject($mockExportService, 'resourceFactory', $mockResourceFactory);

        $mockExportService->downloadRegistrationsCsv(1, ['settings']);
    }
}
