<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * EmailService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EmailService
{
    /**
     * Sends an e-mail, if sender and recipient is an valid e-mail address
     *
     * @param string $sender The sender
     * @param string $recipient The recipient
     * @param string $subject The subject
     * @param string $body E-Mail body
     * @param string $name Optional sendername
     * @param array $attachments Array of files (e.g. ['/absolute/path/doc.pdf'])
     * @param string $replyTo The reply-to mail
     *
     * @return bool true/false if message is sent
     */
    public function sendEmailMessage(
        $sender,
        $recipient,
        $subject,
        $body,
        $name = null,
        $attachments = [],
        $replyTo = null
    ) {
        if (!GeneralUtility::validEmail($sender) || !GeneralUtility::validEmail($recipient)) {
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
        return $email->send();
    }
}
