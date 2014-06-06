<?php
namespace SKYFILLERS\SfEventMgt\Tests\Unit\Validation\Validator;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class SKYFILLERS\SfEventMgt\Validation\Validator\RegistrationValidator.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationValidator extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager
	 */
	protected $objectManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Validation\Validator\StringLengthValidator
	 */
	protected $validator;

	/**
	 * @var string
	 */
	protected $validatorClassName = 'SKYFILLERS\\SfEventMgt\\Validation\\Validator\\RegistrationValidator';

	/**
	 * Setup
	 *
	 * @retun void
	 */
	public function setup() {
		$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->objectManager = clone $objectManager;

		$this->validator = $this->getMock($this->validatorClassName, array('translateErrorMessage'));
	}

	/**
	 * Data provider for settings
	 *
	 * @return array
	 */
	public function settingsDataProvider() {
		return array(
			'emptySettings' => array(
				array(),
				array(),
				FALSE
			),
			'noRequiredFieldsSettings' => array(
				array('registration' => array('requiredFields' => '')),
				array(),
				FALSE
			),
			'requiredFieldsSettingsForCityIfCityNotSet' => array(
				array('registration' => array('requiredFields' => 'city')),
				array(),
				TRUE
			),
			'requiredFieldsSettingsForCityIfCitySet' => array(
				array('registration' => array('requiredFields' => 'city')),
				array('city' => 'Some city'),
				FALSE
			),
			'requiredFieldsSettingsForCityAndZipWithWhitespace' => array(
				array('registration' => array('requiredFields' => 'city, zip')),
				array('city' => 'Some city', 'zip' => '12345'),
				FALSE
			),
			'requiredFieldsSettingsForUnknownProperty' => array(
				array('registration' => array('requiredFields' => 'unknown_field')),
				array(),
				FALSE
			),
		);
	}

	/**
	 * Executes all validation tests defines by the given data provider
	 *
	 * @test
	 * @dataProvider settingsDataProvider
	 */
	public function validatorReturnsExpectedResults($settings, $fields, $expected) {
		/** @var \SKYFILLERS\SfEventMgt\Domain\Model\Registration $registration */
		$registration = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration');
		$registration->setFirstname('John');
		$registration->setLastname('Doe');
		$registration->getEmail('email@domain.tld');

		foreach ($fields as $key => $value) {
			$registration->_setProperty($key, $value);
		}

		// Inject configuration and configurationManager
		$configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
			array('getConfiguration'), array(), '', FALSE);
		$configurationManager->expects($this->once())->method('getConfiguration')->will(
			$this->returnValue($settings));
		$this->inject($this->validator, 'configurationManager', $configurationManager);

		// Inject the object manager
		$this->inject($this->validator, 'objectManager', $this->objectManager);

		$this->assertEquals($expected, $this->validator->validate($registration)->hasErrors());
	}

}
