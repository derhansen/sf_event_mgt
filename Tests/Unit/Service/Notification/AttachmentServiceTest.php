<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service\Notification;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\ICalendarService;
use DERHANSEN\SfEventMgt\Service\Notification\AttachmentService;
use DERHANSEN\SfEventMgt\Utility\MessageRecipient;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

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
        $this->subject = new AttachmentService();
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
     * @param mixed $messageType
     * @param mixed $messageRecipient
     * @param mixed $settingsPath
     * @param mixed $expected
     */
    public function getAttachmentsRespectsTypoScriptSettingsForGivenMessageType(
        $messageType,
        $messageRecipient,
        $settingsPath,
        $expected
    ) {
        $registration = new Registration();

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
        $registration = new Registration();
        $event = new Event();

        $mockFile1 = $this->getMockBuilder(File::class)
            ->setMethods(['getForLocalProcessing'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFile1->expects($this->any())->method('getForLocalProcessing')->will($this->returnValue('/path/to/somefile.pdf'));
        $mockFileRef1 = $this->getMockBuilder(FileReference::class)
            ->setMethods(['getOriginalResource'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFileRef1->expects($this->any())->method('getOriginalResource')->will($this->returnValue($mockFile1));

        $mockFile2 = $this->getMockBuilder(File::class)
            ->setMethods(['getForLocalProcessing'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFile2->expects($this->any())->method('getForLocalProcessing')->will($this->returnValue('/path/to/anotherfile.pdf'));
        $mockFileRef2 = $this->getMockBuilder(FileReference::class)
            ->setMethods(['getOriginalResource'])
            ->disableOriginalConstructor()
            ->getMock();
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
        $event = new Event();

        $mockFile = $this->getMockBuilder(File::class)
            ->setMethods(['getForLocalProcessing'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFile->expects($this->any())->method('getForLocalProcessing')->will($this->returnValue('/path/to/somefile.pdf'));
        $mockFileRef = $this->getMockBuilder(FileReference::class)
            ->setMethods(['getOriginalResource'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFileRef->expects($this->any())->method('getOriginalResource')->will($this->returnValue($mockFile));

        $mockRegistration = $this->getMockBuilder(Registration::class)
            ->setMethods(['getEvent', '_hasProperty', '_getProperty'])
            ->disableOriginalConstructor()
            ->getMock();
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

    /**
     * @test
     */
    public function getICalAttachmentReturnsAFilenameIfICalFileEnabled()
    {
        $event = new Event();

        $settings = ['notification' => [
            'registrationNew' => [
                'attachments' => [
                    'user' => [
                        'iCalFile' => 1
                    ]
                ]
            ]
        ]];

        $mockRegistration = $this->getMockBuilder(Registration::class)
            ->setMethods(['getEvent', '_hasProperty', '_getProperty'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($event));

        $mockICalendarService = $this->getMockBuilder(ICalendarService::class)
            ->setMethods(['getiCalendarContent'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'iCalendarService', $mockICalendarService);

        $attachment = $this->subject->getICalAttachment(
            $settings,
            $mockRegistration,
            MessageType::REGISTRATION_NEW,
            MessageRecipient::USER
        );
        $this->assertNotEmpty($attachment);
    }
}
