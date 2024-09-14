<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Event\ModifyRegistrationValidatorResultEvent;
use DERHANSEN\SfEventMgt\Service\SpamCheckService;
use DERHANSEN\SfEventMgt\SpamChecks\Exceptions\SpamCheckNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator;
use TYPO3\CMS\Extbase\Validation\Validator\EmailAddressValidator;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;

/**
 * RegistrationValidator
 */
class RegistrationValidator extends AbstractValidator
{
    protected ConfigurationManagerInterface $configurationManager;
    protected EventDispatcherInterface $eventDispatcher;
    protected array $settings;

    public function __construct()
    {
        // @todo Use CPP in sf_event_mgt v8 (breaking change)
        $this->configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        $this->eventDispatcher = GeneralUtility::makeInstance(EventDispatcherInterface::class);

        $this->settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'SfEventMgt',
            'Pieventregistration'
        );
    }

    /**
     * Validates the given registration according to required fields set in plugin
     * settings. For boolean fields, the booleanValidator is used and it is assumed,
     * that boolean fields must have the value "TRUE" (for checkboxes)
     *
     * @param Registration $value Registration
     */
    protected function isValid(mixed $value): void
    {
        $spamSettings = $this->settings['registration']['spamCheck'] ?? [];
        if ((bool)($spamSettings['enabled'] ?? false) && $this->isSpamCheckFailed($value, $spamSettings)) {
            $message = $this->translateErrorMessage('registration.spamCheckFailed', 'SfEventMgt');
            $this->addErrorForProperty('spamCheck', $message, 1578855253);

            return;
        }

        $this->validateDefaultFields($value);

        $requiredFields = array_map('trim', explode(',', $this->settings['registration']['requiredFields'] ?? ''));
        foreach ($requiredFields as $requiredField) {
            if ($requiredField !== '' && $value->_hasProperty($requiredField)) {
                $validator = $this->getValidator(gettype($value->_getProperty($requiredField)), $requiredField);
                $validationResult = $validator->validate($value->_getProperty($requiredField));
                if ($validationResult->hasErrors()) {
                    foreach ($validationResult->getErrors() as $error) {
                        $this->result->forProperty($requiredField)->addError($error);
                    }
                }
            }
        }

        $event = new ModifyRegistrationValidatorResultEvent(
            $value,
            $this->settings,
            $this->result
        );
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * Validates the default fields of a registration, that must be filled out. Since domain object validation
     * did not work as expected with registration fields (domain object validation results completely ignored)
     * this own validation is done
     *
     * Checks:
     * - firstname: NotEmpty
     * - lastname: NotEmpty
     * - email: NotEmpty, EmailAddress
     */
    protected function validateDefaultFields(Registration $value): bool
    {
        $result = true;

        $defaultFields = ['firstname', 'lastname', 'email'];
        foreach ($defaultFields as $defaultField) {
            $validator = new NotEmptyValidator();
            $validationResult = $validator->validate($value->_getProperty($defaultField));
            if ($validationResult->hasErrors()) {
                $result = false;
                foreach ($validationResult->getErrors() as $error) {
                    $this->result->forProperty($defaultField)->addError($error);
                }
            }
        }

        $validator = new EmailAddressValidator();
        $validationResult = $validator->validate($value->_getProperty('email'));
        if ($validationResult->hasErrors()) {
            $result = false;
            foreach ($validationResult->getErrors() as $error) {
                $this->result->forProperty('email')->addError($error);
            }
        }

        return $result;
    }

    /**
     * Processes the spam check and returns, if it failed or not
     *
     * @throws SpamCheckNotFoundException
     */
    protected function isSpamCheckFailed(Registration $registration, array $settings): bool
    {
        $pluginKey = 'tx_sfeventmgt_pieventregistration';
        $getMergedWithPost = $this->getRequest()->getQueryParams()[$pluginKey];
        ArrayUtility::mergeRecursiveWithOverrule($getMergedWithPost, $this->getRequest()->getParsedBody()[$pluginKey] ?? []);

        $spamCheckService = new SpamCheckService(
            $registration,
            $settings,
            $getMergedWithPost
        );

        return $spamCheckService->isSpamCheckFailed();
    }

    /**
     * Returns a validator object depending on the given type of the property
     */
    protected function getValidator(string $type, string $field): AbstractValidator
    {
        switch ($type) {
            case 'boolean':
                $validator = new BooleanValidator();
                $validator->setOptions(['is' => true]);
                break;
            default:
                if ($field === 'captcha') {
                    $validator = new CaptchaValidator();
                } else {
                    $validator = new NotEmptyValidator();
                }
        }

        return $validator;
    }
}
