<?php
namespace DERHANSEN\SfEventMgt\Service;

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

use DERHANSEN\SfEventMgt\Utility\MessageType;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * NotificationService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class NotificationService
{

    /**
     * The object manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     * The configuration manager
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     * @inject
     */
    protected $configurationManager;

    /**
     * Registration repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     * @inject
     */
    protected $registrationRepository = null;

    /**
     * Email Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\EmailService
     * @inject
     */
    protected $emailService;

    /**
     * Hash Service
     *
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     * @inject
     */
    protected $hashService;

    /**
     * CustomNotificationLogRepository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository
     * @inject
     */
    protected $customNotificationLogRepository = null;

    /**
     * Sends a custom notification defined by the given customNotification key
     * to all confirmed users of the event
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @param string $customNotification CustomNotification
     * @param array $settings Settings
     *
     * @return int Number of notifications sent
     */
    public function sendCustomNotification($event, $customNotification, $settings)
    {
        if ($this->cantSendCustomNotification($event, $settings, $customNotification)) {
            return 0;
        }
        $count = 0;

        $constraints = $settings['notification']['customNotifications'][$customNotification]['constraints'];
        $registrations = $this->registrationRepository->findNotificationRegistrations($event, $constraints);
        foreach ($registrations as $registration) {
            /** @var \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration */
            if ($registration->isConfirmed() && !$registration->isIgnoreNotifications()) {
                $result = $this->sendUserMessage(
                    $event,
                    $registration,
                    $settings,
                    MessageType::CUSTOM_NOTIFICATION,
                    $customNotification
                );
                if ($result) {
                    $count += 1;
                }
            }
        }
        return $count;
    }

    /**
     * Returns true if conditions are not met to send a custom notification
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
     * @param array $settings
     * @param string $customNotification
     *
     * @return bool
     */
    protected function cantSendCustomNotification($event, $settings, $customNotification)
    {
        return is_null($event) || $customNotification == '' || $settings == '' || !is_array($settings);
    }

    /**
     * Adds a logentry to the custom notification log
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @param string $details Details
     * @param int $emailsSent E-Mails sent
     *
     * @return void
     */
    public function createCustomNotificationLogentry($event, $details, $emailsSent)
    {
        $notificationlogEntry = new \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog();
        $notificationlogEntry->setEvent($event);
        $notificationlogEntry->setDetails($details);
        $notificationlogEntry->setEmailsSent($emailsSent);
        $notificationlogEntry->setCruserId($GLOBALS['BE_USER']->user['uid']);
        $this->customNotificationLogRepository->add($notificationlogEntry);
    }

    /**
     * Sends a message to the user based on the given type
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     * @param array $settings Settings
     * @param int $type Type
     * @param string $customNotification CustomNotification
     *
     * @return bool TRUE if successful, else FALSE
     */
    public function sendUserMessage($event, $registration, $settings, $type, $customNotification = '')
    {
        list($template, $subject) = $this->getUserMessageTemplateSubject($settings, $type, $customNotification);

        if (is_null($event) || is_null($registration) || !is_array($settings) || (substr($template, -5) != '.html')) {
            return false;
        }

        if (!$registration->isIgnoreNotifications()) {
            $body = $this->getNotificationBody($event, $registration, $template, $settings);
            return $this->emailService->sendEmailMessage(
                $settings['notification']['senderEmail'],
                $registration->getEmail(),
                $subject,
                $body,
                $settings['notification']['senderName']
            );
        }
        return false;
    }

    /**
     * Returns an array with template and subject for the user message
     *
     * @param array $settings
     * @param int $type Type
     * @param string $customNotification
     * @return array
     */
    protected function getUserMessageTemplateSubject($settings, $type, $customNotification)
    {
        $template = 'Notification/User/RegistrationNew.html';
        $subject = $settings['notification']['registrationNew']['userSubject'];
        switch ($type) {
            case MessageType::REGISTRATION_CONFIRMED:
                $template = 'Notification/User/RegistrationConfirmed.html';
                $subject = $settings['notification']['registrationConfirmed']['userSubject'];
                break;
            case MessageType::REGISTRATION_CANCELLED:
                $template = 'Notification/User/RegistrationCancelled.html';
                $subject = $settings['notification']['registrationCancelled']['userSubject'];
                break;
            case MessageType::CUSTOM_NOTIFICATION:
                $template = 'Notification/User/Custom/' . $settings['notification']['customNotifications'][$customNotification]['template'];
                $subject = $settings['notification']['customNotifications'][$customNotification]['subject'];
                break;
            case MessageType::REGISTRATION_NEW:
            default:
        }
        return array(
            $template,
            $subject
        );
    }

    /**
     * Sends a message to the admin based on the given type
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     * @param array $settings Settings
     * @param int $type Type
     *
     * @return bool TRUE if successful, else FALSE
     */
    public function sendAdminMessage($event, $registration, $settings, $type)
    {
        list($template, $subject) = $this->getAdminMessageTemplateSubject($settings, $type);


        if (is_null($event) || is_null($registration || !is_array($settings)) ||
            ($event->getNotifyAdmin() === false && $event->getNotifyOrganisator() === false)
        ) {
            return false;
        }

        $allEmailsSent = true;
        $body = $this->getNotificationBody($event, $registration, $template, $settings);
        if ($event->getNotifyAdmin()) {
            $adminEmailArr = GeneralUtility::trimExplode(',', $settings['notification']['adminEmail'], true);
            foreach ($adminEmailArr as $adminEmail) {
                $allEmailsSent = $allEmailsSent && $this->emailService->sendEmailMessage(
                    $settings['notification']['senderEmail'],
                    $adminEmail,
                    $subject,
                    $body,
                    $settings['notification']['senderName']
                );
            }
        }
        if ($event->getNotifyOrganisator() && $event->getOrganisator()) {
            $allEmailsSent = $allEmailsSent && $this->emailService->sendEmailMessage(
                $settings['notification']['senderEmail'],
                $event->getOrganisator()->getEmail(),
                $subject,
                $body,
                $settings['notification']['senderName']
            );
        }
        return $allEmailsSent;
    }

    /**
     * Returns an array with template and subject for the admin message
     *
     * @param array $settings
     * @param int $type Type
     * @return array
     */
    protected function getAdminMessageTemplateSubject($settings, $type)
    {
        $template = 'Notification/Admin/RegistrationNew.html';
        $subject = $settings['notification']['registrationNew']['adminSubject'];
        switch ($type) {
            case MessageType::REGISTRATION_CONFIRMED:
                $template = 'Notification/Admin/RegistrationConfirmed.html';
                $subject = $settings['notification']['registrationConfirmed']['adminSubject'];
                break;
            case MessageType::REGISTRATION_CANCELLED:
                $template = 'Notification/Admin/RegistrationCancelled.html';
                $subject = $settings['notification']['registrationCancelled']['adminSubject'];
                break;
            case MessageType::REGISTRATION_NEW:
            default:
        }
        return array(
            $template,
            $subject
        );
    }

    /**
     * Returns the rendered HTML for the given template
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
     * @param string $template Template
     * @param array $settings Settings
     *
     * @return string
     */
    protected function getNotificationBody($event, $registration, $template, $settings)
    {
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
        $emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $emailView->setFormat('html');
        $layoutRootPaths = $this->getTemplateFolders('layout');
        $partialRootPaths = $this->getTemplateFolders('partial');

        if (TYPO3_MODE === 'BE' && $registration->getLanguage() !== '') {
            // Temporary set Language of current BE user to given language
            $GLOBALS['BE_USER']->uc['lang'] = $registration->getLanguage();
            $emailView->getRequest()->setControllerExtensionName('SfEventMgt');
        }

        $emailView->setLayoutRootPaths($layoutRootPaths);
        $emailView->setPartialRootPaths($partialRootPaths);
        $emailView->setTemplatePathAndFilename($this->getTemplatePath($template));
        $emailView->assignMultiple(array(
            'event' => $event,
            'registration' => $registration,
            'settings' => $settings,
            'hmac' => $this->hashService->generateHmac('reg-' . $registration->getUid()),
            'reghmac' => $this->hashService->appendHmac((string)$registration->getUid())
        ));
        $emailBody = $emailView->render();
        return $emailBody;
    }

    /**
     * Returns the template folders for the given part
     *
     * @param string $part
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getTemplateFolders($part = 'template')
    {
        $extbaseConfig = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );

        if (!empty($extbaseConfig[$part . 'RootPaths'])) {
            $templatePaths = $extbaseConfig[$part . 'RootPaths'];
        }
        if (empty($templatePaths)) {
            $path = $extbaseConfig[$part . 'RootPath'];
            if (!empty($path)) {
                $templatePaths = $path;
            }
        }
        if (empty($templatePaths)) {
            $templatePaths = [];
            $templatePaths[] = 'EXT:sf_event_mgt/Resources/Private/' . ucfirst($part) . 's/';
        }

        $absolutePaths = [];
        foreach ($templatePaths as $templatePath) {
            $absolutePaths[] = GeneralUtility::getFileAbsFileName($templatePath);
        }
        return $absolutePaths;
    }

    /**
     * Return path and filename for a file or path.
     *        Only the first existing file/path will be returned.
     *        respect *RootPaths and *RootPath
     *
     * @param string $pathAndFilename e.g. Email/Name.html
     * @param string $part "template", "partial", "layout"
     * @return string Filename/path
     */
    protected function getTemplatePath($pathAndFilename, $part = 'template')
    {
        $matches = $this->getTemplatePaths($pathAndFilename, $part);
        return !empty($matches) ? end($matches) : '';
    }

    /**
     * Return path and filename for one or many files/paths.
     *        Only existing files/paths will be returned.
     *        respect *RootPaths and *RootPath
     *
     * @param string $pathAndFilename Path/filename (Email/Name.html) or path
     * @param string $part "template", "partial", "layout"
     * @return array All existing matches found
     */
    protected function getTemplatePaths($pathAndFilename, $part = 'template')
    {
        $matches = [];
        $absolutePaths = $this->getTemplateFolders($part);
        foreach ($absolutePaths as $absolutePath) {
            if (file_exists($absolutePath . $pathAndFilename)) {
                $matches[] = $absolutePath . $pathAndFilename;
            }
        }
        return $matches;
    }
}