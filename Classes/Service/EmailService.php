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
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
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

        $useFluidEmail = (bool)(GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('sf_event_mgt', 'useFluidEmail'));

        $email = $this->getEmailObject($useFluidEmail);
        $email->from(new Address($sender, $name));
        $email->to($recipient);
        $email->subject($subject);
        $email->html($body);
        if ($replyTo !== null && $replyTo !== '' && GeneralUtility::validEmail($replyTo)) {
            $email->replyTo(new Address($replyTo));
        }
        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                $email->attachFromPath($attachment);
            }
        }

        if ($email instanceof FluidEmail) {
            $email->format('html');
            $email->setTemplate('EventManagement');
            $email->assignMultiple([
                'title' => $subject,
                'body' => $body,
            ]);
        }

        $mailer = GeneralUtility::makeInstance(MailerInterface::class);

        try {
            $mailer->send($email);
            return $mailer->getSentMessage() !== null;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }

    private function getEmailObject(bool $useFluidEmail): MailMessage|FluidEmail
    {
        if ($useFluidEmail) {
            /** @var FluidEmail $email */
            $email = GeneralUtility::makeInstance(FluidEmail::class);
        } else {
            /** @var MailMessage $email */
            $email = GeneralUtility::makeInstance(MailMessage::class);
        }
        return $email;
    }
}
