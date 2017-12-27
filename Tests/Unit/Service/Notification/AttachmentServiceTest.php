<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service\Notification;

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
use DERHANSEN\SfEventMgt\Utility\MessageRecipient;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\Notification\AttachmentService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AttachmentServiceTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Service\Notification\AttachmentService
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Service\Notification\AttachmentService();
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
     * Data provider for getAttachmentsRespectsTypoScriptSettingsForGivenMessageType
     *
     * @return array
     */
    public function typoScriptConfigTestDataProvider()
    {
        return [
            'noTyposcriptSettingsForMessageType' => [
                MessageType::REGISTRATION_NEW,
                MessageRecipient::USER,
                'invalidTypoScriptSetting',
                []
            ],
            'messageTypeRegistrationNewForUser' => [
                MessageType::REGISTRATION_NEW,
                MessageRecipient::USER,
                'registrationNew',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf')
                ]
            ],
            'messageTypeRegistrationWaitlistNewForUser' => [
                MessageType::REGISTRATION_WAITLIST_NEW,
                MessageRecipient::USER,
                'registrationWaitlistNew',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf')
                ]
            ],
            'messageTypeRegistrationConfirmedForUser' => [
                MessageType::REGISTRATION_CONFIRMED,
                MessageRecipient::USER,
                'registrationConfirmed',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf')
                ]
            ],
            'messageTypeRegistrationWaitlistConfirmedForUser' => [
                MessageType::REGISTRATION_WAITLIST_CONFIRMED,
                MessageRecipient::USER,
                'registrationWaitlistConfirmed',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf')
                ]
            ],
            'messageTypeRegistrationNewForAdmin' => [
                MessageType::REGISTRATION_NEW,
                MessageRecipient::ADMIN,
                'registrationNew',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf')
                ]
            ],
        ];
    }

    /**
     * Tests if TypoScript settings are respected for the configured MessageTypes
     * and expected fromFiles result are returned
     *
     * @test
     * @dataProvider typoScriptConfigTestDataProvider
     */
    public function getAttachmentsRespectsTypoScriptSettingsForGivenMessageType(
        $messageType,
        $messageRecipient,
        $settingsPath,
        $expected
    ) {
        $registration = new \DERHANSEN\SfEventMgt\Domain\Model\Registration();

        $settings = ['notification' => [
            $settingsPath => [
                'attachments' => [
                    $messageRecipient => [
                        'fromFiles' => [
                            'fileadmin/attachment1.pdf',
                            'fileadmin/attachment2.pdf',
                        ]
                    ]
                ]
            ]
        ]];

        $attachments = $this->subject->getAttachments($settings, $registration, $messageType, $messageRecipient);
        $this->assertEquals($expected, $attachments);
    }

    /**
     * Tests if fromEventProperty returns expected attachments for objectStorage property
     * @test
     */
    public function getAttachmentsReturnsAttachmentsFromEventPropertyWithObjectStorage()
    {
        $registration = new \DERHANSEN\SfEventMgt\Domain\Model\Registration();
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

        $mockFile1 = $this->getMock(File::class, ['getForLocalProcessing'], [], '', false);
        $mockFile1->expects($this->any())->method('getForLocalProcessing')->will($this->returnValue('/path/to/somefile.pdf'));
        $mockFileRef1 = $this->getMock('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference', ['getOriginalResource'], [], '', false);
        $mockFileRef1->expects($this->any())->method('getOriginalResource')->will($this->returnValue($mockFile1));

        $mockFile2 = $this->getMock(File::class, ['getForLocalProcessing'], [], '', false);
        $mockFile2->expects($this->any())->method('getForLocalProcessing')->will($this->returnValue('/path/to/anotherfile.pdf'));
        $mockFileRef2 = $this->getMock('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference', ['getOriginalResource'], [], '', false);
        $mockFileRef2->expects($this->any())->method('getOriginalResource')->will($this->returnValue($mockFile2));

        $event->addFiles($mockFileRef1);
        $event->addFiles($mockFileRef2);
        $registration->setEvent($event);

        $settings = ['notification' => [
            'registrationNew' => [
                'attachments' => [
                    'user' => [
                        'fromEventProperty' => [
                            'files'
                        ]
                    ]
                ]
            ]
        ]];

        $expected = [
            '/path/to/somefile.pdf',
            '/path/to/anotherfile.pdf'
        ];

        $attachments = $this->subject->getAttachments(
            $settings,
            $registration,
            MessageType::REGISTRATION_NEW,
            MessageRecipient::USER
        );
        $this->assertEquals($expected, $attachments);
    }

    /**
     * Tests if fromRegistrationProperty returns expected attachments for FileReference property
     * @test
     */
    public function getAttachmentsReturnsAttachmentsFromEventPropertyWithFileReference()
    {
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

        $mockFile = $this->getMock(File::class, ['getForLocalProcessing'], [], '', false);
        $mockFile->expects($this->any())->method('getForLocalProcessing')->will($this->returnValue('/path/to/somefile.pdf'));
        $mockFileRef = $this->getMock('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference', ['getOriginalResource'], [], '', false);
        $mockFileRef->expects($this->any())->method('getOriginalResource')->will($this->returnValue($mockFile));

        $mockRegistration = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            ['getEvent', '_hasProperty', '_getProperty'],
            [],
            '',
            false
        );
        $mockRegistration->expects($this->once())->method('getEvent')->will($this->returnValue($event));
        $mockRegistration->expects($this->once())->method('_hasProperty')->will($this->returnValue(true));
        $mockRegistration->expects($this->once())->method('_getProperty')->will($this->returnValue($mockFileRef));

        $settings = ['notification' => [
            'registrationNew' => [
                'attachments' => [
                    'user' => [
                        'fromRegistrationProperty' => [
                            'fileProperty'
                        ]
                    ]
                ]
            ]
        ]];

        $expected = [
            '/path/to/somefile.pdf',
        ];

        $attachments = $this->subject->getAttachments(
            $settings,
            $mockRegistration,
            MessageType::REGISTRATION_NEW,
            MessageRecipient::USER
        );
        $this->assertEquals($expected, $attachments);
    }
}
