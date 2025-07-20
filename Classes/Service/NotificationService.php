<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Event\AfterAdminMessageSentEvent;
use DERHANSEN\SfEventMgt\Event\AfterUserMessageSentEvent;
use DERHANSEN\SfEventMgt\Event\ModifyAdminMessageSenderEvent;
use DERHANSEN\SfEventMgt\Event\ModifyCustomNotificationLogEvent;
use DERHANSEN\SfEventMgt\Event\ModifyUserMessageAttachmentsEvent;
use DERHANSEN\SfEventMgt\Event\ModifyUserMessageSenderEvent;
use DERHANSEN\SfEventMgt\Security\HashScope;
use DERHANSEN\SfEventMgt\Service\Notification\AttachmentService;
use DERHANSEN\SfEventMgt\Utility\MessageRecipient;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

class NotificationService
{
    public function __construct(
        protected readonly RegistrationRepository $registrationRepository,
        protected readonly EmailService $emailService,
        protected readonly HashService $hashService,
        protected readonly FluidRenderingService $fluidRenderingService,
        protected readonly CustomNotificationLogRepository $customNotificationLogRepository,
        protected readonly AttachmentService $attachmentService,
        protected readonly EventDispatcherInterface $eventDispatcher,
        protected readonly Context $context
    ) {
    }

    /**
     * Sends a custom notification defined by the given customNotification key
     * to users of the event. Returns the number of notifications sent.
     */
    public function sendCustomNotification(
        RequestInterface $request,
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
                $request,
                $event,
                $registration,
                $settings,
                MessageType::CUSTOM_NOTIFICATION,
                $customNotification
            );
            if ($result) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * Returns true if conditions are not met to send a custom notification
     */
    protected function cantSendCustomNotification(
        array $settings,
        CustomNotification $customNotification
    ): bool {
        return $customNotification->getTemplate() === '' || empty($settings);
    }

    /**
     * Adds a logentry to the custom notification log
     */
    public function createCustomNotificationLogentry(
        Event $event,
        string $details,
        int $emailsSent,
        CustomNotification $customNotification
    ): void {
        $notificationlogEntry = new CustomNotificationLog();
        $notificationlogEntry->setPid($event->getPid());
        $notificationlogEntry->setEvent($event);
        $notificationlogEntry->setDetails($details);
        $notificationlogEntry->setEmailsSent($emailsSent);
        $notificationlogEntry->setCruserId($this->context->getPropertyFromAspect('backend.user', 'id'));

        $modifyCustomNotificationLogEntry = new ModifyCustomNotificationLogEvent(
            $notificationlogEntry,
            $event,
            $details,
            $customNotification
        );
        $this->eventDispatcher->dispatch($modifyCustomNotificationLogEntry);
        $notificationlogEntry = $modifyCustomNotificationLogEntry->getCustomNotificationLog();

        $this->customNotificationLogRepository->add($notificationlogEntry);
    }

    /**
     * Sends a message to the user based on the given type
     */
    public function sendUserMessage(
        RequestInterface $request,
        Event $event,
        Registration $registration,
        array $settings,
        int $type,
        ?CustomNotification $customNotification = null
    ): bool {
        [$template, $subject] = $this->getUserMessageTemplateSubject(
            $settings,
            $type,
            $customNotification
        );

        if ((bool)($settings['notification']['disabled'] ?? false) || !str_ends_with($template, '.html')) {
            return false;
        }

        $additionalBodyVariables = [
            'customNotification' => $customNotification,
        ];

        if (!$registration->isIgnoreNotifications()) {
            $body = $this->getNotificationBody($request, $event, $registration, $template, $settings, $additionalBodyVariables);
            $subject = $this->fluidRenderingService->parseString(
                $request,
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
                MessageRecipient::USER,
                $customNotification
            );

            // Get iCal attachment if configured
            $iCalAttachment = $this->attachmentService->getICalAttachment(
                $request,
                $settings,
                $registration,
                $type,
                MessageRecipient::USER,
                $customNotification
            );

            if ($iCalAttachment !== '') {
                $attachments[] = $iCalAttachment;
            }

            $modifyUserMessageSenderEvent = new ModifyUserMessageSenderEvent(
                $settings['notification']['senderName'] ?? '',
                $settings['notification']['senderEmail'] ?? '',
                $settings['notification']['replyToEmail'] ?? '',
                $subject,
                $body,
                $registration,
                $type,
                $this,
                $request
            );
            $this->eventDispatcher->dispatch($modifyUserMessageSenderEvent);
            $subject = $modifyUserMessageSenderEvent->getSubject();
            $body = $modifyUserMessageSenderEvent->getBody();

            $senderName = $modifyUserMessageSenderEvent->getSenderName();
            $senderEmail = $modifyUserMessageSenderEvent->getSenderEmail();
            $replyToEmail = $modifyUserMessageSenderEvent->getReplyToEmail();

            $modifyUserAttachmentsEvent = new ModifyUserMessageAttachmentsEvent(
                $attachments,
                $registration,
                $type,
                $settings,
                $customNotification,
                $this,
                $request
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
                $this,
                $request
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
     */
    protected function getUserMessageTemplateSubject(
        array $settings,
        int $type,
        ?CustomNotification $customNotification = null
    ): array {
        if ($type === MessageType::CUSTOM_NOTIFICATION && $customNotification === null) {
            return ['', ''];
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
                $customNotificationSettings = $settings['notification']['customNotifications'] ?? [];
                $templateKey = $customNotification->getTemplate();
                $template = 'Notification/User/Custom/' . ($customNotificationSettings[$templateKey]['template'] ?? '');
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
            $template,
            $subject,
        ];
    }

    /**
     * Sends a message to the admin based on the given type. Returns true, if the message was sent, otherwise false
     */
    public function sendAdminMessage(
        RequestInterface $request,
        Event $event,
        Registration $registration,
        array $settings,
        int $type
    ): bool {
        [$template, $subject] = $this->getAdminMessageTemplateSubject($settings, $type);

        if ((bool)($settings['notification']['disabled'] ?? false) ||
            ($event->getNotifyAdmin() === false && $event->getNotifyOrganisator() === false)
        ) {
            return false;
        }

        $allEmailsSent = true;
        $body = $this->getNotificationBody($request, $event, $registration, $template, $settings);
        $subject = $this->fluidRenderingService->parseString(
            $request,
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

        $modifyAdminMessageSenderEvent = new ModifyAdminMessageSenderEvent(
            $senderName,
            $senderEmail,
            $senderEmail,
            $subject,
            $body,
            $registration,
            $type,
            $this,
            $request
        );
        $this->eventDispatcher->dispatch($modifyAdminMessageSenderEvent);
        $subject = $modifyAdminMessageSenderEvent->getSubject();
        $body = $modifyAdminMessageSenderEvent->getBody();

        $senderName = $modifyAdminMessageSenderEvent->getSenderName();
        $senderEmail = $modifyAdminMessageSenderEvent->getSenderEmail();
        $replyToEmail = $modifyAdminMessageSenderEvent->getReplyToEmail();

        if ($event->getNotifyAdmin()) {
            $adminEmailArr = GeneralUtility::trimExplode(',', $settings['notification']['adminEmail'] ?? '', true);
            foreach ($adminEmailArr as $adminEmail) {
                $allEmailsSent = $allEmailsSent && $this->emailService->sendEmailMessage(
                    $senderEmail,
                    $adminEmail,
                    $subject,
                    $body,
                    $senderName,
                    $attachments,
                    $replyToEmail
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
                $attachments,
                $replyToEmail
            );
        }

        $afterAdminMessageSentEvent = new AfterAdminMessageSentEvent(
            $registration,
            $body,
            $subject,
            $attachments,
            $senderName,
            $senderEmail,
            $type,
            $replyToEmail,
            $this,
            $request
        );
        $this->eventDispatcher->dispatch($afterAdminMessageSentEvent);

        return $allEmailsSent;
    }

    /**
     * Returns an array with template and subject for the admin message
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
        }

        return [$template, $subject];
    }

    /**
     * Returns the rendered HTML for the given template
     */
    protected function getNotificationBody(
        RequestInterface $request,
        Event $event,
        Registration $registration,
        string $template,
        array $settings,
        array $additionalBodyVariables = []
    ): string {
        $isBackendRequest = ApplicationType::fromRequest($request)->isBackend();

        if ($isBackendRequest && $registration->getLanguage() !== '') {
            // Temporary set Language of current BE user to given language
            $GLOBALS['BE_USER']->uc['lang'] = $registration->getLanguage();
        }
        $defaultVariables = [
            'event' => $event,
            'registration' => $registration,
            'settings' => $settings,
            'hmac' => $this->hashService->hmac('reg-' . $registration->getUid(), HashScope::RegistrationUid->value),
            'reghmac' => $this->hashService->appendHmac((string)$registration->getUid(), HashScope::RegistrationHmac->value),
            'confirmAction' => $this->getTargetLinkAction('confirmAction', $settings),
            'cancelAction' => $this->getTargetLinkAction('cancelAction', $settings),
        ];
        $variables = array_merge($additionalBodyVariables, $defaultVariables);

        return $this->fluidRenderingService->renderTemplate($request, $template, $variables);
    }

    private function getTargetLinkAction(string $action, array $settings): string
    {
        switch ($action) {
            case 'confirmAction':
                $additionalStep = (bool)($settings['confirmation']['additionalVerificationStep'] ?? false);
                $action = $additionalStep ? 'verifyConfirmRegistration' : 'confirmRegistration';
                break;
            case 'cancelAction':
                $additionalStep = (bool)($settings['cancellation']['additionalVerificationStep'] ?? false);
                $action = $additionalStep ? 'verifyCancelRegistration' : 'cancelRegistration';
                break;
            default:
                throw new RuntimeException('Unknown action for getTargetLinkAction()', 1718170550);
        }

        return $action;
    }
}
