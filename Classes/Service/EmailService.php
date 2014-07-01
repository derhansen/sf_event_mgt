<?php
namespace SKYFILLERS\SfEventMgt\Service;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * EmailService
 */
class EmailService {

	/**
	 * @var \TYPO3\CMS\Core\Mail\MailMessage
	 */
	protected $mailer = NULL;

	/**
	 * Constructor - creates new instance of mailer
	 */
	public function __construct() {
		$this->mailer = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
	}
	/**
	 * Sends an e-mail
	 *
	 * @param string $sender The sender
	 * @param string $recipient The recipient
	 * @param string $subject The subject
	 * @param string $body E-Mail body
	 * @param string $name Optional sendername
	 *
	 * @return TRUE/FALSE if message is sent
	 */
	public function sendEmailMessage($sender, $recipient, $subject, $body, $name = NULL) {
		$this->mailer->setFrom($sender, $name);
		$this->mailer->setSubject($subject);
		$this->mailer->setBody($body, 'text/html');
		$this->mailer->setTo($recipient);
		$this->mailer->send();
		return $this->mailer->isSent();
	}
}