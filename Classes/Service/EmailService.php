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
     * @param string $replyTo The reply-to mail
     *
     * @return bool TRUE/FALSE if message is sent
     */
    public function sendEmailMessage($sender, $recipient, $subject, $body, $name = null, $attachments = [], $replyTo = null)
    {
        if (GeneralUtility::validEmail($sender) && GeneralUtility::validEmail($recipient)) {
            $this->initialize();
            $this->mailer->setFrom($sender, $name);
            $this->mailer->setSubject($subject);
            $this->mailer->setBody($body, 'text/html');
            if ($replyTo !== null) {
                $this->mailer->setReplyTo($replyTo);
            }
            $this->mailer->setTo($recipient);
            $this->addAttachments($attachments);
            $this->mailer->send();

            return $this->mailer->isSent();
        }

        return false;
    }

    /**
     * Creates a new mail message
     *
     * @return void
     */
    protected function initialize()
    {
        $this->mailer = GeneralUtility::makeInstance(MailMessage::class);
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
