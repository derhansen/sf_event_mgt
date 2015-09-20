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

/**
 * MessageType
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class MessageType {
	const REGISTRATION_NEW = 0;
	const REGISTRATION_CONFIRMED = 1;
	const CUSTOM_NOTIFICATION = 2;
	const REGISTRATION_CANCELLED = 3;
}