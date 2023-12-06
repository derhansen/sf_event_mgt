<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service\Notification;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\ICalendarService;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * AttachmentService
 */
class AttachmentService
{
    protected ICalendarService $iCalendarService;

    public function injectICalService(ICalendarService $iCalService): void
    {
        $this->iCalendarService = $iCalService;
    }

    /**
     * Returns an array of absolute filenames to attach to notifications
     *
     * Attachments must be configured as following (example for "registrationNew"):
     *
     * registrationNew {
     *   attachments {
     *     user {
     *       fromFiles {
     *         1 = fileadmin/path-to-attachment.pdf
     *       }
     *       fromEventProperty {
     *         1 = files
     *         2 = image
     *       }
     *       fromRegistrationProperty {
     *         1 = propertyOfRegistration
     *       }
     *     }
     *     admin {
     *       fromFiles =
     *       fromEventProperty =
     *       fromRegistrationProperty =
     *     }
     *   }
     * }
     */
    public function getAttachments(
        array $settings,
        Registration $registration,
        int $messageType,
        string $messageRecipient,
        ?CustomNotification $customNotification = null
    ): array {
        $attachments = [];
        $settingPath = $this->getSettingsPath($messageType);
        $attachmentSettings = $settings['notification'][$settingPath]['attachments'][$messageRecipient] ?? [];

        if ($customNotification) {
            $attachmentSettings = $settings['notification']['customNotifications'][$customNotification->getTemplate()]['attachments'][$messageRecipient] ?? [];
        }

        if (!empty($attachmentSettings)) {
            // Attachments globally from TypoScript
            $attachments = $this->getFileAttachments($attachmentSettings);

            // Attachments from Event properties
            $eventAttachments = $this->getObjectAttachments(
                $attachmentSettings['fromEventProperty'] ?? [],
                $registration->getEvent()
            );
            $attachments = array_merge($attachments, $eventAttachments);

            // Attachments from Registration properties
            $registrationAttachments = $this->getObjectAttachments(
                $attachmentSettings['fromRegistrationProperty'] ?? [],
                $registration
            );
            $attachments = array_merge($attachments, $registrationAttachments);
        }

        return $attachments;
    }

    /**
     * Returns the absolute filename for to an iCal File of the event, if the iCalFile setting is set for
     * the given messageType
     *
     * Example:
     *
     *  registrationNew {
     *    attachments {
     *      user {
     *        iCalFile = 1
     *      }
     *   }
     * }
     */
    public function getICalAttachment(
        array $settings,
        Registration $registration,
        int $messageType,
        string $messageRecipient,
        ?CustomNotification $customNotification = null
    ): string {
        $file = '';
        $settingPath = $this->getSettingsPath($messageType);

        $attachICalFile = (bool)($settings['notification'][$settingPath]['attachments'][$messageRecipient]['iCalFile'] ?? false);

        if ($customNotification) {
            $attachICalFile = (bool)($settings['notification']['customNotifications'][$customNotification->getTemplate()]['attachments'][$messageRecipient]['iCalFile'] ?? false);
        }

        if ($attachICalFile) {
            $file = GeneralUtility::tempnam(
                'event-' . $registration->getEvent()->getUid() . '-',
                '.ics'
            );
            $content = $this->iCalendarService->getiCalendarContent($registration->getEvent());
            GeneralUtility::writeFile($file, $content);
        }

        return $file;
    }

    /**
     * Returns the settingspath for the given messagetype
     */
    protected function getSettingsPath(int $messageType): string
    {
        $settingPath = '';
        switch ($messageType) {
            case MessageType::REGISTRATION_NEW:
                $settingPath = 'registrationNew';
                break;
            case MessageType::REGISTRATION_WAITLIST_NEW:
                $settingPath = 'registrationWaitlistNew';
                break;
            case MessageType::REGISTRATION_CONFIRMED:
                $settingPath = 'registrationConfirmed';
                break;
            case MessageType::REGISTRATION_WAITLIST_CONFIRMED:
                $settingPath = 'registrationWaitlistConfirmed';
                break;
        }

        return $settingPath;
    }

    /**
     * Returns configured fromFiles attachments from TypoScript settings
     */
    protected function getFileAttachments(array $settings): array
    {
        $attachments = [];
        if (isset($settings['fromFiles']) && is_array($settings['fromFiles']) && count($settings['fromFiles']) > 0) {
            foreach ($settings['fromFiles'] as $file) {
                $attachments[] = GeneralUtility::getFileAbsFileName($file);
            }
        }

        return $attachments;
    }

    /**
     * Returns the attachments from an object of all configured properties
     */
    protected function getObjectAttachments(array $propertyNames, AbstractEntity $object): array
    {
        $attachments = [];
        if (count($propertyNames) > 0) {
            foreach ($propertyNames as $propertyName) {
                if ($object->_hasProperty($propertyName)) {
                    $attachments = array_merge($attachments, $this->getAttachmentsFromProperty($object, $propertyName));
                }
            }
        }

        return $attachments;
    }

    /**
     * Returns an array wih the absolute path to all FAL files in the given object-property
     */
    protected function getAttachmentsFromProperty(AbstractEntity $object, string $propertyName): array
    {
        $attachments = [];
        if ($propertyName === '') {
            return $attachments;
        }

        $property = $object->_getProperty($propertyName);

        if ($property instanceof ObjectStorage) {
            foreach ($property as $object) {
                if ($object instanceof FileReference) {
                    $attachments[] = $object->getOriginalResource()->getForLocalProcessing(false);
                }
            }
        }

        if ($property instanceof FileReference) {
            $attachments[] = $property->getOriginalResource()->getForLocalProcessing(false);
        }

        return $attachments;
    }
}
