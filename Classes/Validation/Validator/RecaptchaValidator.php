<?php
namespace DERHANSEN\SfEventMgt\Validation\Validator;

/**
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

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Validator for recaptcha.
 */
class RecaptchaValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{

    /**
     * This validator always needs to be executed even if the given value is empty.
     * See AbstractValidator::validate()
     *
     * @var boolean
     */
    protected $acceptsEmptyValues = false;

    /**
     * Object Manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     * Checks if the given value is a valid recaptcha.
     *
     * @param mixed $value The value that should be validated
     * @throws InvalidVariableException
     */
    public function isValid($value)
    {
        $response = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('g-recaptcha-response');
        if ($response !== null) {
            // Only check if a response is set
            $configurationManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
            $fullTs = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $reCaptchaSettings = $fullTs['plugin.']['tx_sfeventmgt.']['settings.']['reCaptcha.'];

            if (isset($reCaptchaSettings) &&
                is_array($reCaptchaSettings) &&
                isset($reCaptchaSettings['secretKey']) &&
                $reCaptchaSettings['secretKey']
            ) {
                $ch = curl_init();

                $fields = [
                    'secret' => $reCaptchaSettings['secretKey'],
                    'response' => $response
                ];

                // url-ify the data for the POST
                $fieldsString = '';
                foreach ($fields as $key => $value) {
                    $fieldsString .= $key . '=' . $value . '&';
                }
                rtrim($fieldsString, '&');

                // set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);

                // execute post
                $resultCH = json_decode(curl_exec($ch));
                if (!(bool)$resultCH->success) {
                    $this->addError(
                        LocalizationUtility::translate('validation.possible_robot', 'sf_event_mgt'),
                        1231423345
                    );
                }
            } else {
                throw new InvalidVariableException(
                    LocalizationUtility::translate('error.no_secretKey', 'sf_event_mgt'),
                    1358349150
                );
            }
        }
    }
}
