<?php

/*
 * This file is part of the Mailhog service provider for the Codeception Email Testing Framework.
 * (c) 2015-2016 Eric Martel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Codeception\Module;

use Codeception\Email\EmailServiceProvider;
use Codeception\Email\TestsEmails;
use Codeception\Module;
use Codeception\TestInterface;
use GuzzleHttp\Client;

class MailHog extends Module
{
    use TestsEmails;

    use EmailServiceProvider;

    /**
     * HTTP Client to interact with MailHog
     *
     * @var Client
     */
    protected $mailhog;

    /**
     * Raw email header data converted to JSON
     *
     * @var array
     */
    protected $fetchedEmails;

    /**
     * Currently selected set of email headers to work with
     *
     * @var array
     */
    protected $currentInbox;

    /**
     * Starts as the same data as the current inbox, but items are removed as they're used
     *
     * @var array
     */
    protected $unreadInbox;

    /**
     * Contains the currently open email on which test operations are conducted
     *
     * @var mixed
     */
    protected $openedEmail;

    /**
     * Codeception exposed variables
     */
    protected array $config = ['url', 'port', 'guzzleRequestOptions', 'deleteEmailsAfterScenario', 'timeout'];

    /**
     * Codeception required variables
     */
    protected array $requiredFields = ['url', 'port'];

    public function _initialize()
    {
        $url = trim($this->config['url'], '/') . ':' . $this->config['port'];

        $timeout = 1.0;
        if (isset($this->config['timeout'])) {
            $timeout = $this->config['timeout'];
        }
        $this->mailhog = new Client(['base_uri' => $url, 'timeout' => $timeout]);

        if (isset($this->config['guzzleRequestOptions'])) {
            foreach ($this->config['guzzleRequestOptions'] as $option => $value) {
                $this->mailhog->setDefaultOption($option, $value);
            }
        }
    }

    /**
     * Method executed after each scenario
     */
    public function _after(TestInterface $test)
    {
        if (isset($this->config['deleteEmailsAfterScenario']) && $this->config['deleteEmailsAfterScenario']) {
            $this->deleteAllEmails();
        }
    }

    /**
     * Delete All Emails
     *
     * Accessible from tests, deletes all emails
     */
    public function deleteAllEmails()
    {
        try {
            $this->mailhog->request('DELETE', '/api/v1/messages');
        } catch (Exception $e) {
            $this->fail('Exception: ' . $e->getMessage());
        }
    }

    /**
     * Fetch Emails
     *
     * Accessible from tests, fetches all emails
     */
    public function fetchEmails()
    {
        $this->fetchedEmails = [];

        try {
            $response = $this->mailhog->request('GET', '/api/v1/messages');
            $this->fetchedEmails = json_decode($response->getBody());
        } catch (Exception $e) {
            $this->fail('Exception: ' . $e->getMessage());
        }

        $this->sortEmails($this->fetchedEmails);

        // by default, work on all emails
        $this->setCurrentInbox($this->fetchedEmails);
    }

    /**
     * Access Inbox For *
     *
     * Filters emails to only keep those that are received by the provided address
     *
     * @param string $address Recipient address' inbox
     */
    public function accessInboxFor($address)
    {
        $inbox = [];

        foreach ($this->fetchedEmails as $email) {
            if (strpos($email->Content->Headers->To[0], $address) !== false) {
                array_push($inbox, $email);
            }

            if (isset($email->Content->Headers->Cc) && array_search($address, $email->Content->Headers->Cc)) {
                array_push($inbox, $email);
            }

            if (isset($email->Content->Headers->Bcc) && array_search($address, $email->Content->Headers->Bcc)) {
                array_push($inbox, $email);
            }
        }
        $this->setCurrentInbox($inbox);
    }

    /**
     * Access Inbox For To
     *
     * Filters emails to only keep those that are received by the provided address
     *
     * @param string $address Recipient address' inbox
     */
    public function accessInboxForTo($address)
    {
        $inbox = [];

        foreach ($this->fetchedEmails as $email) {
            if (strpos($email->Content->Headers->To[0], $address) !== false) {
                array_push($inbox, $email);
            }
        }
        $this->setCurrentInbox($inbox);
    }

    /**
     * Access Inbox For CC
     *
     * Filters emails to only keep those that are received by the provided address
     *
     * @param string $address Recipient address' inbox
     */
    public function accessInboxForCc($address)
    {
        $inbox = [];

        foreach ($this->fetchedEmails as $email) {
            if (isset($email->Content->Headers->Cc) && array_search($address, $email->Content->Headers->Cc)) {
                array_push($inbox, $email);
            }
        }
        $this->setCurrentInbox($inbox);
    }

    /**
     * Access Inbox For BCC
     *
     * Filters emails to only keep those that are received by the provided address
     *
     * @param string $address Recipient address' inbox
     */
    public function accessInboxForBcc($address)
    {
        $inbox = [];

        foreach ($this->fetchedEmails as $email) {
            if (isset($email->Content->Headers->Bcc) && array_search($address, $email->Content->Headers->Bcc)) {
                array_push($inbox, $email);
            }
        }
        $this->setCurrentInbox($inbox);
    }

    /**
     * Open Next Unread Email
     *
     * Pops the most recent unread email and assigns it as the email to conduct tests on
     */
    public function openNextUnreadEmail()
    {
        $this->openedEmail = $this->getMostRecentUnreadEmail();
    }

    /**
     * Get Opened Email
     *
     * Main method called by the tests, providing either the currently open email or the next unread one
     *
     * @param bool $fetchNextUnread Goes to the next Unread Email
     * @return mixed Returns a JSON encoded Email
     */
    protected function getOpenedEmail($fetchNextUnread = false)
    {
        if ($fetchNextUnread || $this->openedEmail == null) {
            $this->openNextUnreadEmail();
        }

        return $this->openedEmail;
    }

    /**
     * Get Most Recent Unread Email
     *
     * Pops the most recent unread email, fails if the inbox is empty
     *
     * @return mixed Returns a JSON encoded Email
     */
    protected function getMostRecentUnreadEmail()
    {
        if (empty($this->unreadInbox)) {
            $this->fail('Unread Inbox is Empty');
        }

        $email = array_shift($this->unreadInbox);
        return $this->getFullEmail($email->ID);
    }

    /**
     * Get Full Email
     *
     * Returns the full content of an email
     *
     * @param string $id ID from the header
     * @return mixed Returns a JSON encoded Email
     */
    protected function getFullEmail($id)
    {
        try {
            $response = $this->mailhog->request('GET', "/api/v1/messages/{$id}");
        } catch (Exception $e) {
            $this->fail('Exception: ' . $e->getMessage());
        }
        $fullEmail = json_decode($response->getBody());
        return $fullEmail;
    }

    /**
     * Get Email Subject
     *
     * Returns the subject of an email
     *
     * @param mixed $email Email
     * @return string Subject
     */
    protected function getEmailSubject($email)
    {
        return $this->getDecodedEmailProperty($email, $email->Content->Headers->Subject[0]);
    }

    /**
     * Get Email Body
     *
     * Returns the body of an email
     *
     * @param mixed $email Email
     * @return string Body
     */
    protected function getEmailBody($email)
    {
        return $this->getDecodedEmailProperty($email, $email->Content->Body);
    }

    /**
     * Get Email To
     *
     * Returns the string containing the persons included in the To field
     *
     * @param mixed $email Email
     * @return string To
     */
    protected function getEmailTo($email)
    {
        return $this->getDecodedEmailProperty($email, $email->Content->Headers->To[0]);
    }

    /**
     * Get Email CC
     *
     * Returns the string containing the persons included in the CC field
     *
     * @param mixed $email Email
     * @return string CC
     */
    protected function getEmailCC($email)
    {
        $emailCc = '';
        if (isset($email->Content->Headers->Cc)) {
            $emailCc = $this->getDecodedEmailProperty($email, $email->Content->Headers->Cc[0]);
        }
        return $emailCc;
    }

    /**
     * Get Email BCC
     *
     * Returns the string containing the persons included in the BCC field
     *
     * @param mixed $email Email
     * @return string BCC
     */
    protected function getEmailBCC($email)
    {
        $emailBcc = '';
        if (isset($email->Content->Headers->Bcc)) {
            $emailBcc = $this->getDecodedEmailProperty($email, $email->Content->Headers->Bcc[0]);
        }
        return $emailBcc;
    }

    /**
     * Get Email Recipients
     *
     * Returns the string containing all of the recipients, such as To, CC and if provided BCC
     *
     * @param mixed $email Email
     * @return string Recipients
     */
    protected function getEmailRecipients($email)
    {
        $recipients = [];
        if (isset($email->Content->Headers->To)) {
            $recipients[] = $this->getEmailTo($email);
        }
        if (isset($email->Content->Headers->Cc)) {
            $recipients[] = $this->getEmailCC($email);
        }
        if (isset($email->Content->Headers->Bcc)) {
            $recipients[] = $this->getEmailBCC($email);
        }

        $recipients = implode(' ', $recipients);

        return $recipients;
    }

    /**
     * Get Email Sender
     *
     * Returns the string containing the sender of the email
     *
     * @param mixed $email Email
     * @return string Sender
     */
    protected function getEmailSender($email)
    {
        return $this->getDecodedEmailProperty($email, $email->Content->Headers->From[0]);
    }

    /**
     * Get Email Reply To
     *
     * Returns the string containing the address to reply to
     *
     * @param mixed $email Email
     * @return string ReplyTo
     */
    protected function getEmailReplyTo($email)
    {
        return $this->getDecodedEmailProperty($email, $email->Content->Headers->{'Reply-To'}[0]);
    }

    /**
     * Get Email Priority
     *
     * Returns the priority of the email
     *
     * @param mixed $email Email
     * @return string Priority
     */
    protected function getEmailPriority($email)
    {
        return $this->getDecodedEmailProperty($email, $email->Content->Headers->{'X-Priority'}[0]);
    }

    /**
     * Returns the decoded email property
     *
     * @param string $property
     * @return string
     */
    protected function getDecodedEmailProperty($email, $property)
    {
        if ((string)$property != '') {
            if (!empty($email->Content->Headers->{'Content-Transfer-Encoding'}) &&
                in_array('quoted-printable', $email->Content->Headers->{'Content-Transfer-Encoding'})
            ) {
                $property = quoted_printable_decode($property);
            }
            if (!empty($email->Content->Headers->{'Content-Type'}[0]) &&
                strpos($email->Content->Headers->{'Content-Type'}[0], 'multipart/mixed') !== false
            ) {
                $property = quoted_printable_decode($property);
            }
            if (strpos($property, '=?utf-8?Q?') !== false && extension_loaded('mbstring')) {
                $property = mb_decode_mimeheader($property);
            }
        }
        return $property;
    }

    /**
     * Set Current Inbox
     *
     * Sets the current inbox to work on, also create a copy of it to handle unread emails
     *
     * @param array $inbox Inbox
     */
    protected function setCurrentInbox($inbox)
    {
        $this->currentInbox = $inbox;
        $this->unreadInbox = $inbox;
    }

    /**
     * Get Current Inbox
     *
     * Returns the complete current inbox
     *
     * @return array Current Inbox
     */
    protected function getCurrentInbox()
    {
        return $this->currentInbox;
    }

    /**
     * Get Unread Inbox
     *
     * Returns the inbox containing unread emails
     *
     * @return array Unread Inbox
     */
    protected function getUnreadInbox()
    {
        return $this->unreadInbox;
    }

    /**
     * Sort Emails
     *
     * Sorts the inbox based on the timestamp
     *
     * @param array $inbox Inbox to sort
     */
    protected function sortEmails($inbox)
    {
        usort($inbox, [$this, 'sortEmailsByCreationDatePredicate']);
    }

    /**
     * Get Email To
     *
     * Returns the string containing the persons included in the To field
     *
     * @param mixed $emailA Email
     * @param mixed $emailB Email
     * @return int Which email should go first
     */
    public static function sortEmailsByCreationDatePredicate($emailA, $emailB)
    {
        $sortKeyA = $emailA->Content->Headers->Date;
        $sortKeyB = $emailB->Content->Headers->Date;
        return ($sortKeyA > $sortKeyB) ? -1 : 1;
    }
}
