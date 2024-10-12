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
use DERHANSEN\SfEventMgt\Service\CaptchaConfigurationService;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

/**
 * Validator for either reCaptcha or hCaptcha
 */
class CaptchaValidator extends AbstractValidator
{
    /**
     * This validator always needs to be executed even if the given value is empty, because else
     * the captcha can be bypassed.
     */
    protected $acceptsEmptyValues = false;

    protected RequestFactory $requestFactory;

    public function __construct(
        protected readonly ConfigurationManagerInterface $configurationManager
    ) {
        $this->requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
    }

    /**
     * @param Registration $value Registration
     */
    protected function isValid(mixed $value): void
    {
        $settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'SfEventMgt',
            'Pieventregistration'
        );

        $configurationService = new CaptchaConfigurationService($settings['registration']['captcha'] ?? []);

        if (!$configurationService->getEnabled()) {
            return;
        }

        $parsedBody = $this->getRequest()->getParsedBody();
        $captchaFormFieldValue = $parsedBody[$configurationService->getResponseField()] ?? null;
        if ($captchaFormFieldValue === null) {
            $this->addError(
                LocalizationUtility::translate('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf:validation.missing_captcha'),
                1631943016
            );
            return;
        }

        $url = HttpUtility::buildUrl(
            [
                'host' => $configurationService->getVerificationServer(),
                'query' => \http_build_query(
                    [
                        'secret' => $configurationService->getPrivateKey(),
                        'response' => $captchaFormFieldValue,
                        'remoteip' => $this->getRequest()->getAttribute('normalizedParams')->getRemoteAddress(),
                    ]
                ),
            ]
        );

        $response = $this->requestFactory->request($url, 'POST');

        $body = (string)$response->getBody();
        $responseArray = json_decode($body, true);
        if (!is_array($responseArray) || empty($responseArray) || $responseArray['success'] === false) {
            $this->addError(
                $this->translateErrorMessage('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf:validation.possible_robot'),
                1631940277
            );
        }
    }
}
