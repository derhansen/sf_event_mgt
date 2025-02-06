<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EmailService
{
    /**
     * Sends an email, if sender and recipient is an valid email address and if the subject is not empty
     *
     * @param string $sender The sender
     * @param string $recipient The recipient
     * @param string $subject The subject
     * @param string $body E-Mail body
     * @param string|null $name Optional sendername
     * @param array $attachments Array of files (e.g. ['/absolute/path/doc.pdf'])
     * @param string|null $replyTo The reply-to mail
     *
     * @return bool true/false if message is sent
     */
    public function sendEmailMessage(
        string $sender,
        string $recipient,
        string $subject,
        string $body,
        ?string $name = null,
        array $attachments = [],
        ?string $replyTo = null
    ): bool {
        if ($subject === '' || !GeneralUtility::validEmail($sender) || !GeneralUtility::validEmail($recipient)) {
            return false;
        }

        /** @var MailMessage $email */
        $email = GeneralUtility::makeInstance(MailMessage::class);
        $email->setFrom($sender, $name);
        $email->setTo($recipient);
        $email->setSubject($subject);
        $email->html($body);
        if ($replyTo !== null && $replyTo !== '') {
            $email->setReplyTo($replyTo);
        }
        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                $email->attachFromPath($attachment);
            }
        }
        try {
            return $email->send();
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }
}
