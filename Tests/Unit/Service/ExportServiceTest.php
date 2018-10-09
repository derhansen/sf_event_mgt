<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Exception;
use DERHANSEN\SfEventMgt\Service\ExportService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Resource\Exception\InsufficientFileAccessPermissionsException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
        $this->subject = new ExportService();
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
        $mockRegistration = $this->getMockBuilder(Registration::class)->setMethods(['_hasProperty'])->getMock();
        $mockRegistration->expects($this->at(0))->method('_hasProperty')->with(
            $this->equalTo('uid')
        )->will($this->returnValue(true));
        $mockRegistration->expects($this->at(1))->method('_hasProperty')->with(
            $this->equalTo('firstname')
        )->will($this->returnValue(true));
        $mockRegistration->expects($this->at(2))->method('_hasProperty')->with(
            $this->equalTo('wrongfield')
        )->will($this->returnValue(false));

        $allRegistrations = new ObjectStorage();
        $allRegistrations->attach($mockRegistration);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByEvent'])
            ->disableOriginalConstructor()
            ->getMock();
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
     * @param mixed $uid
     * @param mixed $fields
     * @param mixed $expected
     */
    public function exportServiceWorksWithDifferentFormattedTypoScriptValues($uid, $fields, $expected)
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->at(0))->method('_hasProperty')->with(
            $this->equalTo('uid')
        )->will($this->returnValue(true));
        $mockRegistration->expects($this->at(2))->method('_hasProperty')->with(
            $this->equalTo('firstname')
        )->will($this->returnValue(true));
        $mockRegistration->expects($this->at(4))->method('_hasProperty')->with(
            $this->equalTo('lastname')
        )->will($this->returnValue(true));
        $mockRegistration->expects($this->at(1))->method('_getCleanProperty')->with(
            $this->equalTo('uid')
        )->will($this->returnValue(1));
        $mockRegistration->expects($this->at(3))->method('_getCleanProperty')->with(
            $this->equalTo('firstname')
        )->will($this->returnValue('Max'));
        $mockRegistration->expects($this->at(5))->method('_getCleanProperty')->with(
            $this->equalTo('lastname')
        )->will($this->returnValue('Mustermann'));

        $allRegistrations = new ObjectStorage();
        $allRegistrations->attach($mockRegistration);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects($this->once())->method('findByEvent')->will(
            $this->returnValue($allRegistrations)
        );
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
        $mockResourceFactory = $this->getMockBuilder(ResourceFactory::class)->disableOriginalConstructor()->getMock();
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
        $mockExportService = $this->getMockBuilder(ExportService::class)
            ->setMethods(['exportRegistrationsCsv'])
            ->getMock();
        $mockExportService->expects($this->once())->method('exportRegistrationsCsv')->will(
            $this->returnValue('CSV-DATA')
        );

        $mockFile = $this->getMockBuilder(File::class)->disableOriginalConstructor()->getMock();
        $mockFile->expects($this->once())->method('setContents')->with('CSV-DATA');

        $mockStorageRepository = $this->getMockBuilder(StorageRepository::class)
            ->setMethods(['getFolder', 'createFile', 'dumpFileContents'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockStorageRepository->expects($this->at(0))->method('getFolder')->with('_temp_');
        $mockStorageRepository->expects($this->at(1))->method('createFile')->will($this->returnValue($mockFile));
        $mockStorageRepository->expects($this->at(2))->method('dumpFileContents');

        $mockResourceFactory = $this->getMockBuilder(ResourceFactory::class)
            ->setMethods(['getDefaultStorage'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
            $this->returnValue($mockStorageRepository)
        );
        $this->inject($mockExportService, 'resourceFactory', $mockResourceFactory);

        $mockExportService->downloadRegistrationsCsv(1, ['settings']);
    }

    /**
     * @test
     * @return void
     */
    public function hasWriteAccessToTempFolderReturnsFalseIfNoDefaultStorage()
    {
        $mockResourceFactory = $this->getMockBuilder(ResourceFactory::class)->disableOriginalConstructor()->getMock();
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will($this->returnValue(null));
        $this->inject($this->subject, 'resourceFactory', $mockResourceFactory);
        $this->assertFalse($this->subject->hasWriteAccessToTempFolder());
    }

    /**
     * @test
     * @return void
     */
    public function hasWriteAccessToTempFolderReturnsFalseIfNoReadAccessToFolder()
    {
        $mockStorage = $this->getMockBuilder(ResourceStorage::class)->disableOriginalConstructor()->getMock();
        $mockStorage->expects($this->once())->method('getFolder')->will($this->throwException(
            new InsufficientFileAccessPermissionsException()
        ));

        $mockResourceFactory = $this->getMockBuilder(ResourceFactory::class)->disableOriginalConstructor()->getMock();
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
            $this->returnValue($mockStorage)
        );
        $this->inject($this->subject, 'resourceFactory', $mockResourceFactory);
        $this->assertFalse($this->subject->hasWriteAccessToTempFolder());
    }

    /**
     * @test
     * @return void
     */
    public function hasWriteAccessToTempFolderReturnsFalseIfNoWriteAccessToFolder()
    {
        $mockFolder = $this->getMockBuilder(Folder::class)->disableOriginalConstructor()->getMock();
        $mockFolder->expects($this->once())->method('checkActionPermission')->with('write')
            ->will($this->returnValue(false));

        $mockStorage = $this->getMockBuilder(ResourceStorage::class)->disableOriginalConstructor()->getMock();
        $mockStorage->expects($this->once())->method('getFolder')->will($this->returnValue($mockFolder));

        $mockResourceFactory = $this->getMockBuilder(ResourceFactory::class)->disableOriginalConstructor()->getMock();
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
            $this->returnValue($mockStorage)
        );
        $this->inject($this->subject, 'resourceFactory', $mockResourceFactory);
        $this->assertFalse($this->subject->hasWriteAccessToTempFolder());
    }

    /**
     * @test
     * @return void
     */
    public function hasWriteAccessToTempFolderReturnsTrueIfReadAndWriteAccess()
    {
        $mockFolder = $this->getMockBuilder(Folder::class)->disableOriginalConstructor()->getMock();
        $mockFolder->expects($this->once())->method('checkActionPermission')->with('write')
            ->will($this->returnValue(true));

        $mockStorage = $this->getMockBuilder(ResourceStorage::class)->disableOriginalConstructor()->getMock();
        $mockStorage->expects($this->once())->method('getFolder')->will($this->returnValue($mockFolder));

        $mockResourceFactory = $this->getMockBuilder(ResourceFactory::class)->disableOriginalConstructor()->getMock();
        $mockResourceFactory->expects($this->once())->method('getDefaultStorage')->will(
            $this->returnValue($mockStorage)
        );
        $this->inject($this->subject, 'resourceFactory', $mockResourceFactory);
        $this->assertTrue($this->subject->hasWriteAccessToTempFolder());
    }
}
