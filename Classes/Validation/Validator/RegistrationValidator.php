<?php
namespace DERHANSEN\SfEventMgt\Validation\Validator;

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

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * RegistrationValidator
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{

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
     * Validates the given registration according to required fields set in plugin
     * settings. For boolean fields, the booleanValidator is used and it is assumed,
     * that boolean fields must have the value "TRUE" (for checkboxes)
     *
     * @param Registration $value Registration
     *
     * @return bool
     */
    protected function isValid($value)
    {
        $settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'SfEventMgt',
            'Pievent'
        );

        // If no required fields are set, then the registration is valid
        if ($settings['registration']['requiredFields'] === '' ||
            !isset($settings['registration']['requiredFields'])
        ) {
            $result = true;
        }

        $requiredFields = array_map('trim', explode(',', $settings['registration']['requiredFields']));
        $result = true;

        foreach ($requiredFields as $requiredField) {
            if ($value->_hasProperty($requiredField)) {
                $validator = $this->getValidator(gettype($value->_getProperty($requiredField)));
                /** @var \TYPO3\CMS\Extbase\Error\Result $validationResult */
                $validationResult = $validator->validate($value->_getProperty($requiredField));
                if ($validationResult->hasErrors()) {
                    $result = false;
                    foreach ($validationResult->getErrors() as $error) {
                        $this->result->forProperty($requiredField)->addError($error);
                    }
                }
            }
        }

        if ($value->_hasProperty('recaptcha')) {
            $result = TRUE;
            $response = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('g-recaptcha-response');
            if ($response !== NULL) {
                    // Only check if a response is set

                    $configurationManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
                    $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
                    $reCaptchaSettings = $fullTs['plugin.']['tx_sfeventmgt.']['settings.']['reCAPTCHA.'];

                    if (
                        isset($reCaptchaSettings) &&
                        is_array($reCaptchaSettings) &&
                        isset($reCaptchaSettings['secretKey']) &&
                        $reCaptchaSettings['secretKey']
                    ) {
                        $ch = curl_init();

                        $fields = array(
                            'secret' => $reCaptchaSettings['secretKey'],
                            'response' => $response
                        );

                        //url-ify the data for the POST
                        $fieldsString = '';
                        foreach ($fields as $key => $value) {
                            $fieldsString .= $key . '=' . $value . '&';
                        }
                        rtrim($fieldsString, '&');

                        //set the url, number of POST vars, POST data
                        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                        curl_setopt($ch, CURLOPT_POST, count($fields));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);

                        //execute post
                        $resultCH = json_decode(curl_exec($ch));
                        if (!(bool)$resultCH->success) {
                            $this->result->forProperty('recaptcha')->addError(new \TYPO3\CMS\Extbase\Error\Error(LocalizationUtility::translate('validation.possible_robot', 'sf_event_mgt')));
                            $result = FALSE;
                        }
                    } else {
                        throw new InvalidVariableException(LocalizationUtility::translate('error.no_secretKey', 'sf_event_mgt'), 1358349150);
                    }
                }
        }
        return $result;
    }

    /**
     * Returns a validator object depending on the given type of the property
     *
     * @param string $type Type
     *
     * @return \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
     */
    protected function getValidator($type)
    {
        switch ($type) {
            case 'boolean':
                /** @var \TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator $validator */
                $validator = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Validation\\Validator\\BooleanValidator',
                    array('is' => true));
                break;
            default:
                /** @var \TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator $validator */
                $validator = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Validation\\Validator\\NotEmptyValidator');
        }
        return $validator;
    }
}