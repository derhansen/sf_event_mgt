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

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * EmailService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EmailService
{

    /**
     * Mailmessage
     *
     * @var \TYPO3\CMS\Core\Mail\MailMessage
     */
    protected $mailer = null;

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
    public function sendEmailMessage($sender, $recipient, $subject, $body, $name = null, $attachments = [])
    {
        if (GeneralUtility::validEmail($sender) && GeneralUtility::validEmail($recipient)) {
            $this->initialize();
            $this->mailer->setFrom($sender, $name);
            $this->mailer->setSubject($subject);
            $this->mailer->setBody($body, 'text/html');
            $this->mailer->setTo($recipient);
            $this->addAttachments($attachments);
            $this->mailer->send();
            return $this->mailer->isSent();
        } else {
            return false;
        }
    }

    /**
     * Creates a new mail message
     *
     * @return void
     */
    protected function initialize()
    {
        $this->mailer = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
    }

    /**
     * Attaches the given array of files to the email message
     *
     * @param array $attachments
     * @return void
     */
    protected function addAttachments($attachments)
    {
        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                if (file_exists($attachment)) {
                    $this->mailer->attach(\Swift_Attachment::fromPath($attachment));
                }
            }
        }
    }
}
