<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Event\AfterAdminMessageSentEvent;
use DERHANSEN\SfEventMgt\Event\AfterUserMessageSentEvent;
use DERHANSEN\SfEventMgt\Event\ModifyCustomNotificationLogEvent;
use DERHANSEN\SfEventMgt\Event\ModifyUserMessageAttachmentsEvent;
use DERHANSEN\SfEventMgt\Event\ModifyUserMessageSenderEvent;
use DERHANSEN\SfEventMgt\Service\Notification\AttachmentService;
use DERHANSEN\SfEventMgt\Utility\MessageRecipient;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

/**
 * NotificationService
 */
class NotificationService
{
    protected RegistrationRepository $registrationRepository;
    protected EmailService $emailService;
    protected HashService $hashService;
    protected FluidStandaloneService $fluidStandaloneService;
    protected CustomNotificationLogRepository $customNotificationLogRepository;
    protected AttachmentService $attachmentService;
    protected EventDispatcherInterface $eventDispatcher;

    public function injectAttachmentService(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function injectCustomNotificationLogRepository(
        CustomNotificationLogRepository $customNotificationLogRepository
    ) {
        $this->customNotificationLogRepository = $customNotificationLogRepository;
    }

    public function injectEmailService(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function injectFluidStandaloneService(FluidStandaloneService $fluidStandaloneService)
    {
        $this->fluidStandaloneService = $fluidStandaloneService;
    }

    public function injectHashService(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    public function injectRegistrationRepository(RegistrationRepository $registrationRepository)
    {
        $this->registrationRepository = $registrationRepository;
    }

    public function injectEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Sends a custom notification defined by the given customNotification key
     * to all confirmed users of the event
     *
     * @param Event $event
     * @param CustomNotification $customNotification
     * @param array $settings
     *
     * @return int Number of notifications sent
     */
    public function sendCustomNotification(
        Event $event,
        CustomNotification $customNotification,
        array $settings = []
    ): int {
        if ($this->cantSendCustomNotification($settings, $customNotification)) {
            return 0;
        }
        $count = 0;

        $customNotificationSettings = $settings['notification']['customNotifications'] ?? [];
        $constraints = $customNotificationSettings[$customNotification->getTemplate()]['constraints'] ?? [];
        $registrations = $this->registrationRepository->findNotificationRegistrations(
            $event,
            $customNotification,
            $constraints
        );

        foreach ($registrations as $registration) {
            /** @var Registration $registration */
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

        return $count;
    }

    /**
     * Returns true if conditions are not met to send a custom notification
     *
     * @param array $settings
     * @param CustomNotification $customNotification
     *
     * @return bool
     */
    protected function cantSendCustomNotification(
        array $settings,
        CustomNotification $customNotification
    ): bool {
        return $customNotification->getTemplate() === '' || empty($settings);
    }

    /**
     * Adds a logentry to the custom notification log
     *
     * @param Event $event
     * @param string $details
     * @param int $emailsSent
     */
    public function createCustomNotificationLogentry(Event $event, string $details, int $emailsSent): void
    {
        $notificationlogEntry = new \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog();
        $notificationlogEntry->setPid($event->getPid());
        $notificationlogEntry->setEvent($event);
        $notificationlogEntry->setDetails($details);
        $notificationlogEntry->setEmailsSent($emailsSent);
        $notificationlogEntry->setCruserId($GLOBALS['BE_USER']->user['uid'] ?? 0);

        $modifyCustomNotificationLogEntry = new ModifyCustomNotificationLogEvent(
            $notificationlogEntry,
            $event,
            $details
        );
        $this->eventDispatcher->dispatch($modifyCustomNotificationLogEntry);
        $notificationlogEntry = $modifyCustomNotificationLogEntry->getCustomNotificationLog();

        $this->customNotificationLogRepository->add($notificationlogEntry);
    }

    /**
     * Sends a message to the user based on the given type
     *
     * @param Event $event
     * @param Registration $registration
     * @param array $settings
     * @param int $type
     * @param CustomNotification|null $customNotification
     *
     * @return bool TRUE if successful, else FALSE
     */
    public function sendUserMessage(
        Event $event,
        Registration $registration,
        array $settings,
        int $type,
        ?CustomNotification $customNotification = null
    ): bool {
        list($template, $subject) = $this->getUserMessageTemplateSubject(
            $settings,
            $type,
            $customNotification
        );

        if (!is_array($settings) ||
            (substr($template, -5) != '.html') ||
            (bool)($settings['notification']['disabled'] ?? false)
        ) {
            return false;
        }

        $additionalBodyVariables = [
            'customNotification' => $customNotification,
        ];

        if (!$registration->isIgnoreNotifications()) {
            $body = $this->getNotificationBody($event, $registration, $template, $settings, $additionalBodyVariables);
            $subject = $this->fluidStandaloneService->parseStringFluid(
                $subject,
                [
                    'event' => $event,
                    'registration' => $registration,
                ]
            );
            $attachments = $this->attachmentService->getAttachments(
                $settings,
                $registration,
                $type,
                MessageRecipient::USER
            );

            // Get iCal attachment if configured
            $iCalAttachment = $this->attachmentService->getICalAttachment(
                $settings,
                $registration,
                $type,
                MessageRecipient::USER
            );

            if ($iCalAttachment !== '') {
                $attachments[] = $iCalAttachment;
            }

            $modifyUserMessageSenderEvent = new ModifyUserMessageSenderEvent(
                $settings['notification']['senderName'] ?? '',
                $settings['notification']['senderEmail'] ?? '',
                $settings['notification']['replyToEmail'] ?? '',
                $registration,
                $type,
                $this
            );
            $this->eventDispatcher->dispatch($modifyUserMessageSenderEvent);
            $senderName = $modifyUserMessageSenderEvent->getSenderName();
            $senderEmail = $modifyUserMessageSenderEvent->getSenderEmail();
            $replyToEmail = $modifyUserMessageSenderEvent->getReplyToEmail();

            $modifyUserAttachmentsEvent = new ModifyUserMessageAttachmentsEvent(
                $attachments,
                $registration,
                $type,
                $this
            );
            $this->eventDispatcher->dispatch($modifyUserAttachmentsEvent);
            $attachments = $modifyUserAttachmentsEvent->getAttachments();

            $result = $this->emailService->sendEmailMessage(
                $senderEmail,
                $registration->getEmail(),
                $subject,
                $body,
                $senderName,
                $attachments,
                $replyToEmail
            );

            $afterUserMessageSentEvent = new AfterUserMessageSentEvent(
                $registration,
                $body,
                $subject,
                $attachments,
                $senderName,
                $senderEmail,
                $replyToEmail,
                $this
            );
            $this->eventDispatcher->dispatch($afterUserMessageSentEvent);

            // Cleanup iCal attachment if available
            if ($iCalAttachment !== '') {
                GeneralUtility::unlink_tempfile($iCalAttachment);
            }

            return $result;
        }

        return false;
    }

    /**
     * Returns an array with template and subject for the user message
     *
     * @param array $settings
     * @param int $type Type
     * @param CustomNotification|null $customNotification
     * @return array
     */
    protected function getUserMessageTemplateSubject(
        array $settings,
        int $type,
        CustomNotification $customNotification = null
    ): array {
        if ($type === MessageType::CUSTOM_NOTIFICATION && $customNotification === null) {
            return ['', '',];
        }

        switch ($type) {
            case MessageType::REGISTRATION_NEW:
                $template = 'Notification/User/RegistrationNew.html';
                $subject = $settings['notification']['registrationNew']['userSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_WAITLIST_NEW:
                $template = 'Notification/User/RegistrationWaitlistNew.html';
                $subject = $settings['notification']['registrationWaitlistNew']['userSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_CONFIRMED:
                $template = 'Notification/User/RegistrationConfirmed.html';
                $subject = $settings['notification']['registrationConfirmed']['userSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_WAITLIST_CONFIRMED:
                $template = 'Notification/User/RegistrationWaitlistConfirmed.html';
                $subject = $settings['notification']['registrationWaitlistConfirmed']['userSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_CANCELLED:
                $template = 'Notification/User/RegistrationCancelled.html';
                $subject = $settings['notification']['registrationCancelled']['userSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_WAITLIST_MOVE_UP:
                $template = 'Notification/User/RegistrationWaitlistMoveUp.html';
                $subject = $settings['notification']['registrationWaitlistMoveUp']['userSubject'] ?? '';
                break;
            case MessageType::CUSTOM_NOTIFICATION:
                $customNotificationSettings = $settings['notification']['customNotifications'] ?? '';
                $templateKey = $customNotification->getTemplate();
                $template = 'Notification/User/Custom/' . $customNotificationSettings[$templateKey]['template'] ?? '';
                $subject = $customNotificationSettings[$templateKey]['subject'] ?? '';
                if ($customNotification->getOverwriteSubject() !== '') {
                    $subject = $customNotification->getOverwriteSubject();
                }
                break;
            default:
                $template = '';
                $subject = '';
        }

        return [
            $template ?? '',
            $subject ?? '',
        ];
    }

    /**
     * Sends a message to the admin based on the given type
     *
     * @param Event $event Event
     * @param Registration $registration Registration
     * @param array $settings Settings
     * @param int $type Type
     *
     * @return bool TRUE if successful, else FALSE
     */
    public function sendAdminMessage(Event $event, Registration $registration, array $settings, int $type): bool
    {
        list($template, $subject) = $this->getAdminMessageTemplateSubject($settings, $type);

        if (!is_array($settings) ||
            ($event->getNotifyAdmin() === false && $event->getNotifyOrganisator() === false) ||
            (bool)($settings['notification']['disabled'] ?? false)
        ) {
            return false;
        }

        $allEmailsSent = true;
        $body = $this->getNotificationBody($event, $registration, $template, $settings);
        $subject = $this->fluidStandaloneService->parseStringFluid(
            $subject,
            [
                'event' => $event,
                'registration' => $registration,
            ]
        );
        $attachments = $this->attachmentService->getAttachments(
            $settings,
            $registration,
            $type,
            MessageRecipient::ADMIN
        );

        $senderName = $settings['notification']['senderName'] ?? '';
        $senderEmail = $settings['notification']['senderEmail'] ?? '';
        if ((bool)($settings['notification']['registrationDataAsSenderForAdminEmails'] ?? false)) {
            $senderName = $registration->getFullname();
            $senderEmail = $registration->getEmail();
        }

        if ($event->getNotifyAdmin()) {
            $adminEmailArr = GeneralUtility::trimExplode(',', $settings['notification']['adminEmail'] ?? '', true);
            foreach ($adminEmailArr as $adminEmail) {
                $allEmailsSent = $allEmailsSent && $this->emailService->sendEmailMessage(
                    $senderEmail,
                    $adminEmail,
                    $subject,
                    $body,
                    $senderName,
                    $attachments
                );
            }
        }

        if ($event->getNotifyOrganisator() && $event->getOrganisator()) {
            $allEmailsSent = $allEmailsSent && $this->emailService->sendEmailMessage(
                $senderEmail,
                $event->getOrganisator()->getEmail(),
                $subject,
                $body,
                $senderName,
                $attachments
            );
        }

        $afterAdminMessageSentEvent = new AfterAdminMessageSentEvent(
            $registration,
            $body,
            $subject,
            $attachments,
            $senderName,
            $senderEmail,
            $this
        );
        $this->eventDispatcher->dispatch($afterAdminMessageSentEvent);

        return $allEmailsSent;
    }

    /**
     * Returns an array with template and subject for the admin message
     *
     * @param array $settings
     * @param int $type Type
     * @return array
     */
    protected function getAdminMessageTemplateSubject(array $settings, int $type): array
    {
        $template = 'Notification/Admin/RegistrationNew.html';
        $subject = $settings['notification']['registrationNew']['adminSubject'] ?? '';
        switch ($type) {
            case MessageType::REGISTRATION_WAITLIST_NEW:
                $template = 'Notification/Admin/RegistrationWaitlistNew.html';
                $subject = $settings['notification']['registrationWaitlistNew']['adminSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_CONFIRMED:
                $template = 'Notification/Admin/RegistrationConfirmed.html';
                $subject = $settings['notification']['registrationConfirmed']['adminSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_WAITLIST_CONFIRMED:
                $template = 'Notification/Admin/RegistrationWaitlistConfirmed.html';
                $subject = $settings['notification']['registrationWaitlistConfirmed']['adminSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_CANCELLED:
                $template = 'Notification/Admin/RegistrationCancelled.html';
                $subject = $settings['notification']['registrationCancelled']['adminSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_WAITLIST_MOVE_UP:
                $template = 'Notification/Admin/RegistrationWaitlistMoveUp.html';
                $subject = $settings['notification']['registrationWaitlistMoveUp']['adminSubject'] ?? '';
                break;
            case MessageType::REGISTRATION_NEW:
            default:
        }

        return [$template, $subject];
    }

    /**
     * Returns the rendered HTML for the given template
     *
     * @param Event $event Event
     * @param Registration $registration Registration
     * @param string $template Template
     * @param array $settings Settings
     * @param array $additionalBodyVariables
     * @return string
     */
    protected function getNotificationBody(
        Event $event,
        Registration $registration,
        string $template,
        array $settings,
        array $additionalBodyVariables = []
    ): string {
        $isBackendRequest = ($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface
            && ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend();

        if ($isBackendRequest && $registration->getLanguage() !== '') {
            // Temporary set Language of current BE user to given language
            $GLOBALS['BE_USER']->uc['lang'] = $registration->getLanguage();
        }
        $defaultVariables = [
            'event' => $event,
            'registration' => $registration,
            'settings' => $settings,
            'hmac' => $this->hashService->generateHmac('reg-' . $registration->getUid()),
            'reghmac' => $this->hashService->appendHmac((string)$registration->getUid()),
        ];
        $variables = array_merge($additionalBodyVariables, $defaultVariables);

        return $this->fluidStandaloneService->renderTemplate($template, $variables);
    }
}
