<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\PriceOption;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Event\ModifyRegistrationValidatorResultEvent;
use DERHANSEN\SfEventMgt\Service\SpamCheckService;
use DERHANSEN\SfEventMgt\SpamChecks\Exceptions\SpamCheckNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Error;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator;
use TYPO3\CMS\Extbase\Validation\Validator\EmailAddressValidator;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;

class RegistrationValidator extends AbstractValidator
{
    public function __construct(
        protected readonly ConfigurationManagerInterface $configurationManager,
        protected readonly EventDispatcherInterface $eventDispatcher
    ) {
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
        $settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'SfEventMgt',
            'Pieventregistration'
        );

        $spamSettings = $settings['registration']['spamCheck'] ?? [];
        if ((bool)($spamSettings['enabled'] ?? false) && $this->isSpamCheckFailed($value, $spamSettings)) {
            $message = $this->translateErrorMessage('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf:registration.spamCheckFailed');
            $this->addErrorForProperty('spamCheck', $message, 1578855253);

            return;
        }

        $this->validateDefaultFields($value);
        $this->validatePriceOption($value);

        $requiredFields = array_map('trim', explode(',', $settings['registration']['requiredFields'] ?? ''));
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
            $settings,
            $this->result,
            $this->getRequest()
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
    protected function validateDefaultFields(Registration $value): void
    {
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
                    $validator = new CaptchaValidator($this->configurationManager);
                    $validator->setRequest($this->getRequest());
                } else {
                    $validator = new NotEmptyValidator();
                }
        }

        return $validator;
    }

    protected function validatePriceOption(Registration $registration): void
    {
        $event = $registration->getEvent();
        if ($event === null || $event->getActivePriceOptions() === []) {
            // No price option check, since event has no price options
            return;
        }

        $validator = new NotEmptyValidator();
        $validationResult = $validator->validate($registration->getPriceOption());
        if ($validationResult->hasErrors()) {
            foreach ($validationResult->getErrors() as $error) {
                $this->result->forProperty('priceOption')->addError($error);
            }

            // No further checks required, since the price option is empty
            return;
        }

        if ($this->isInvalidPriceOption($registration->getPriceOption(), $event)) {
            $message = LocalizationUtility::translate('validation.invalid_priceoption', 'SfEventMgt');
            $error = new Error(
                $message ?? 'Invalid price option selected.',
                1727776820
            );
            $this->result->forProperty('priceOption')->addError($error);
        }
    }

    /**
     * Checks, if the given price option is an active price option of the event. Returns
     * true, if the price option is valid, else false is returned.
     */
    protected function isInvalidPriceOption(PriceOption $priceOption, Event $event): bool
    {
        foreach ($event->getActivePriceOptions() as $eventPriceOption) {
            if ($eventPriceOption->getUid() === $priceOption->getUid()) {
                // The price option is valid
                return false;
            }
        }

        return true;
    }
}
