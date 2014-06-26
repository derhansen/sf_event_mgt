<?php
namespace SKYFILLERS\SfEventMgt\Validation\Validator;

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

use SKYFILLERS\SfEventMgt\Domain\Model\Registration;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * RegistrationValidator
 */
class RegistrationValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {

	/**
	 * Configuration Manager
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Object Manager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Validates the given registration according to required fields set in plugin settings
	 *
	 * @param Registration $value
	 * @return bool
	 */
	protected function isValid($value) {
		$settings = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
			'SfEventMgt',
			'Pievent'
		);

		// If no required fields are set, then the registration is valid
		if ($settings['registration']['requiredFields'] === '' ||
			!isset($settings['registration']['requiredFields'])) {
			return TRUE;
		}

		$requiredFields = array_map('trim', explode(',', $settings['registration']['requiredFields']));
		$result = TRUE;

		foreach($requiredFields as $requiredField) {
			if ($value->_hasProperty($requiredField)) {
				/** @var \TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator $validator */
				$validator = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Validation\\Validator\\NotEmptyValidator');

				/** @var \TYPO3\CMS\Extbase\Error\Result $validationResult */
				$validationResult = $validator->validate($value->_getProperty($requiredField));
				if ($validationResult->hasErrors()) {
					$result = FALSE;
					foreach ($validationResult->getErrors() as $error) {
						$this->result->forProperty($requiredField)->addError($error);
					}
				}
			}
		}
		return $result;
	}
}