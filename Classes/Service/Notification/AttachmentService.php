<?php
namespace DERHANSEN\SfEventMgt\Service\Notification;

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
use DERHANSEN\SfEventMgt\Utility\MessageType;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * AttachmentService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AttachmentService
{
    /**
     * Returns an array of filenames to attach to notifications
     *
     * Attachments must be configured as following (example for "registrationNew"):
     *
     *  registrationNew {
     *    attachments {
     *      user {
     *        fromFiles {
     *          1 = fileadmin/path-to-attachment.pdf
     *        }
     *        fromEventProperty {
     *          1 = files
     *          2 = image
     *        }
     *        fromRegistrationProperty {
     *          1 = propertyOfRegistration
     *        }
     *      }
     *      admin {
     *        fromFiles =
     *        fromEventProperty =
     *        fromRegistrationProperty =
     *      }
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
        if ($object && $propertyNames !== '' && count($propertyNames) > 0) {
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

        if ($property instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {
            /** @var $property \TYPO3\CMS\Extbase\Persistence\ObjectStorage */
            foreach ($property as $object) {
                if ($object instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {
                    $attachments[] = $object->getOriginalResource()->getForLocalProcessing(false);
                }
            }
        }

        if ($property instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {
            /** @var $property \TYPO3\CMS\Extbase\Domain\Model\FileReference */
            $attachments[] = $property->getOriginalResource()->getForLocalProcessing(false);
        }
        return $attachments;
    }
}
