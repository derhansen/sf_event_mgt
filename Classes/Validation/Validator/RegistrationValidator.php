<?php
namespace DERHANSEN\SfEventMgt\Validation\Validator;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\SpamCheckService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Validation\Error;
use TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;

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
     */
    protected $configurationManager;

    /**
     * Object Manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * DI for $configurationManager
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager
     */
    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager
    ) {
        $this->configurationManager = $configurationManager;
    }

    /**
     * DI for $objectManager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

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

        $spamSettings = $settings['registration']['spamCheck'] ?? [];
        if ((bool)$spamSettings['enabled'] && $this->isSpamCheckFailed($value, $spamSettings)) {
            $message = $this->translateErrorMessage('registration.spamCheckFailed', 'SfEventMgt');
            $error = new Error($message, 1578855253965);
            $this->result->forProperty('spamCheck')->addError($error);
            return false;
        }

        // If no required fields are set, then the registration is valid
        if ($settings['registration']['requiredFields'] === '' ||
            !isset($settings['registration']['requiredFields'])
        ) {
            return true;
        }

        $requiredFields = array_map('trim', explode(',', $settings['registration']['requiredFields']));
        $result = true;

        foreach ($requiredFields as $requiredField) {
            if ($value->_hasProperty($requiredField)) {
                $validator = $this->getValidator(gettype($value->_getProperty($requiredField)), $requiredField);
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

        return $result;
    }

    /**
     * Processes the spam check and returns, if it failed or not
     *
     * @param Registration $registration
     * @param array $settings
     * @return bool
     * @throws \DERHANSEN\SfEventMgt\SpamChecks\Exceptions\SpamCheckNotFoundException
     */
    protected function isSpamCheckFailed(Registration $registration, array $settings): bool
    {
        $spamCheckService = new SpamCheckService(
            $registration,
            $settings,
            GeneralUtility::_GPmerged('tx_sfeventmgt_pievent')
        );

        return $spamCheckService->isSpamCheckFailed();
    }

    /**
     * Returns a validator object depending on the given type of the property
     *
     * @param string $type Type
     * @param string $field The field
     *
     * @return \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
     */
    protected function getValidator($type, $field)
    {
        switch ($type) {
            case 'boolean':
                /** @var \TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator $validator */
                $validator = $this->objectManager->get(
                    BooleanValidator::class,
                    ['is' => true]
                );
                break;
            default:
                if ($field == 'recaptcha') {
                    /** @var \DERHANSEN\SfEventMgt\Validation\Validator\RecaptchaValidator $validator */
                    $validator = $this->objectManager->get(RecaptchaValidator::class);
                } else {
                    /** @var \TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator $validator */
                    $validator = $this->objectManager->get(NotEmptyValidator::class);
                }
        }

        return $validator;
    }
}
