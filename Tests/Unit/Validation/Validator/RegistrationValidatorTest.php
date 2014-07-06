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

/**
 * Test case for class SKYFILLERS\SfEventMgt\Validation\Validator\RegistrationValidator.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationValidatorTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

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
	 * @return void
	 */
	public function setup() {
		$this->validator = $this->getMock($this->validatorClassName, array('translateErrorMessage'));
	}

	/**
	 * Data provider for settings
	 *
	 * @return array
	 */
	public function missingSettingsDataProvider() {
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
		);
	}

	/**
	 * Executes all validation tests defines by the given data provider
	 *
	 * @test
	 * @dataProvider missingSettingsDataProvider
	 */
	public function validatorReturnsTrueWhenArgumentsMissing($settings, $fields, $expected) {
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();
		$registration->setFirstname('John');
		$registration->setLastname('Doe');
		$registration->setEmail('email@domain.tld');

		foreach ($fields as $key => $value) {
			$registration->_setProperty($key, $value);
		}

		// Inject configuration and configurationManager
		$configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
			array('getConfiguration'), array(), '', FALSE);
		$configurationManager->expects($this->once())->method('getConfiguration')->will(
			$this->returnValue($settings));
		$this->inject($this->validator, 'configurationManager', $configurationManager);

		$this->assertEquals($expected, $this->validator->validate($registration)->hasErrors());
	}

	/**
	 * Data provider for settings
	 *
	 * @return array
	 */
	public function settingsDataProvider() {
		return array(
			'requiredFieldsSettingsForCityIfCityNotSet' => array(
				array('registration' => array('requiredFields' => 'city')),
				array(),
				TRUE,
				TRUE
			),
			'requiredFieldsSettingsForCityIfCitySet' => array(
				array('registration' => array('requiredFields' => 'city')),
				array('city' => 'Some city'),
				FALSE,
				FALSE
			),
			'requiredFieldsSettingsForCityAndZipWithWhitespace' => array(
				array('registration' => array('requiredFields' => 'city, zip')),
				array('city' => 'Some city', 'zip' => '12345'),
				FALSE,
				FALSE
			),
			'requiredFieldsSettingsForUnknownProperty' => array(
				array('registration' => array('requiredFields' => 'unknown_field')),
				array(),
				FALSE,
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
	public function validatorReturnsExpectedResults($settings, $fields, $hasErrors, $expected) {
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();
		$registration->setFirstname('John');
		$registration->setLastname('Doe');
		$registration->setEmail('email@domain.tld');

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
		$validationError = $this->getMock('TYPO3\\CMS\\Extbase\\Error\\Error', array(), array(), '', FALSE);

		$notEmptyValidatorResult = $this->getMock('TYPO3\\CMS\\Extbase\\Error\\Result', array(), array(), '', FALSE);
		$notEmptyValidatorResult->expects($this->any())->method('hasErrors')->will($this->returnValue($hasErrors));
		$notEmptyValidatorResult->expects($this->any())->method('getErrors')->will(
			$this->returnValue(array($validationError)));

		$notEmptyValidator = $this->getMock('TYPO3\\CMS\\Extbase\\Validation\\Validator\\NotEmptyValidator',
			array(), array(), '', FALSE);
		$notEmptyValidator->expects($this->any())->method('validate')->will($this->returnValue(
			$notEmptyValidatorResult));

		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$objectManager->expects($this->any())->method('get')->will($this->returnValue($notEmptyValidator));
		$this->inject($this->validator, 'objectManager', $objectManager);

		$this->assertEquals($expected, $this->validator->validate($registration)->hasErrors());
	}

}
