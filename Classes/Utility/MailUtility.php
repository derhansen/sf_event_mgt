<?php
namespace DERHANSEN\SfEventMgt\Utility;

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

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * MailUtility
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class MailUtility
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
     *
     * @return bool TRUE/FALSE if message is sent
     */
    public static function sendEmailMessage($sender, $recipient, $subject, $body, $name = null, $attachments = [])
    {
        if (GeneralUtility::validEmail($sender) && GeneralUtility::validEmail($recipient)) {
            $message = GeneralUtility::makeInstance(MailMessage::class);
            $message->setFrom($sender, $name);
            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setTo($recipient);
            self::addAttachments($message, $attachments);
            $message->send();
            return $message->isSent();
        } else {
            return false;
        }
    }

    /**
     * Adds given attachments to the given message
     *
     * @param MailMessage $message
     * @param array $attachments
     * return void
     */
    protected static function addAttachments(&$message, $attachments)
    {
        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                if (file_exists($attachment)) {
                    $message->attach(\Swift_Attachment::fromPath($attachment));
                }
            }
        }
    }
}