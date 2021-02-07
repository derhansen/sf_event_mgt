<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Exception;
use DERHANSEN\SfEventMgt\Service\ExportService;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class ExportServiceTest
 */
class ExportServiceTest extends UnitTestCase
{
    /**
     * @var ExportService
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new ExportService();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
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
     */
    public function exportServiceThrowsExceptionWhenFieldIsNotValidForRegistrationModel()
    {
        $this->expectException(Exception::class);
        $mockRegistration = $this->getMockBuilder(Registration::class)->setMethods(['_hasProperty'])->getMock();
        $mockRegistration->expects(self::at(0))->method('_hasProperty')->with(
            self::equalTo('uid')
        )->willReturn(true);
        $mockRegistration->expects(self::at(1))->method('_hasProperty')->with(
            self::equalTo('firstname')
        )->willReturn(true);
        $mockRegistration->expects(self::at(2))->method('_hasProperty')->with(
            self::equalTo('wrongfield')
        )->willReturn(false);

        $allRegistrations = new ObjectStorage();
        $allRegistrations->attach($mockRegistration);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('findByEvent')->willReturn(
            $allRegistrations
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
        $mockRegistration->expects(self::at(0))->method('_hasProperty')->with(
            self::equalTo('uid')
        )->willReturn(true);
        $mockRegistration->expects(self::at(2))->method('_hasProperty')->with(
            self::equalTo('firstname')
        )->willReturn(true);
        $mockRegistration->expects(self::at(4))->method('_hasProperty')->with(
            self::equalTo('lastname')
        )->willReturn(true);
        $mockRegistration->expects(self::at(1))->method('_getCleanProperty')->with(
            self::equalTo('uid')
        )->willReturn(1);
        $mockRegistration->expects(self::at(3))->method('_getCleanProperty')->with(
            self::equalTo('firstname')
        )->willReturn('Max');
        $mockRegistration->expects(self::at(5))->method('_getCleanProperty')->with(
            self::equalTo('lastname')
        )->willReturn('Mustermann');

        $allRegistrations = new ObjectStorage();
        $allRegistrations->attach($mockRegistration);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('findByEvent')->willReturn(
            $allRegistrations
        );
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $returnValue = $this->subject->exportRegistrationsCsv($uid, $fields);
        self::assertSame($expected, $returnValue);
    }
}
