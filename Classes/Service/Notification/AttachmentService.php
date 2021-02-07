<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service\Notification;

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
    /**
     * @var ICalendarService
     */
    protected $iCalendarService;

    /**
     * DI for iCalService
     *
     * @param ICalendarService $iCalService
     */
    public function injectICalService(ICalendarService $iCalService)
    {
        $this->iCalendarService = $iCalService;
    }

    /**
     * Returns an array of filenames to attach to notifications
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
     *
     * @param array $settings
     * @param Registration $registration
     * @param int $messageType
     * @param string $messageRecipient
     *
     * @return array Array with absolute filenames to attachments
     */
    public function getAttachments($settings, $registration, $messageType, $messageRecipient)
    {
        $attachments = [];
        $settingPath = $this->getSettingsPath($messageType);

        if (isset($settings['notification'][$settingPath]['attachments'][$messageRecipient])) {
            // Attachments globally from TypoScript
            $config = $settings['notification'][$settingPath]['attachments'][$messageRecipient];
            $attachments = $this->getFileAttachments($config);

            // Attachments from Event properties
            $eventAttachments = $this->getObjectAttachments($config['fromEventProperty'], $registration->getEvent());
            $attachments = array_merge($attachments, $eventAttachments);

            // Attachments from Registration properties
            $registrationAttachments = $this->getObjectAttachments($config['fromRegistrationProperty'], $registration);
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
     *
     *
     * @param array $settings
     * @param Registration $registration
     * @param int $messageType
     * @param string $messageRecipient
     * @return string
     */
    public function getICalAttachment($settings, $registration, $messageType, $messageRecipient)
    {
        $file = '';
        $settingPath = $this->getSettingsPath($messageType);

        if (isset($settings['notification'][$settingPath]['attachments'][$messageRecipient]['iCalFile']) &&
            (bool)$settings['notification'][$settingPath]['attachments'][$messageRecipient]['iCalFile']) {
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
     *
     * @param string $messageType
     * @return string
     */
    protected function getSettingsPath($messageType)
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
     *
     * @param array $settings
     * @return array
     */
    protected function getFileAttachments($settings)
    {
        $attachments = [];
        if (isset($settings['fromFiles']) && $settings['fromFiles'] !== '' && count($settings['fromFiles']) > 0) {
            foreach ($settings['fromFiles'] as $file) {
                $attachments[] = GeneralUtility::getFileAbsFileName($file);
            }
        }

        return $attachments;
    }

    /**
     * Returns the attachments from an object of all configured properties
     *
     * @param array $propertyNames
     * @param AbstractEntity $object
     * @return array
     */
    protected function getObjectAttachments($propertyNames, $object)
    {
        $attachments = [];
        if ($object && $propertyNames !== '' && is_array($propertyNames) && count($propertyNames) > 0) {
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
     *
     * @param AbstractEntity $object
     * @param string $propertyName
     * @return array
     */
    protected function getAttachmentsFromProperty($object, $propertyName)
    {
        $attachments = [];
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
