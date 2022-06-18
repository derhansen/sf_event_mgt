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
use Psr\Http\Message\ServerRequestInterface;
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
     * This validator always needs to be executed even if the given value is empty.
     * See AbstractValidator::validate()
     *
     * @var bool
     */
    protected $acceptsEmptyValues = false;

    protected ConfigurationManagerInterface $configurationManager;
    protected RequestFactory $requestFactory;

    protected array $settings;

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        $this->requestFactory = GeneralUtility::makeInstance(RequestFactory::class);

        $this->settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'SfEventMgt',
            'Pieventregistration'
        );
    }

    /**
     * @param Registration $value Registration
     */
    protected function isValid($value)
    {
        $configurationService = new CaptchaConfigurationService($this->settings['registration']['captcha'] ?? []);

        if (!$configurationService->getEnabled()) {
            return;
        }

        /** @var ServerRequestInterface $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
        $parsedBody = $request->getParsedBody();
        $captchaFormFieldValue = $parsedBody[$configurationService->getResponseField()] ?? null;
        if (null === $captchaFormFieldValue) {
            $this->addError(
                LocalizationUtility::translate('validation.missing_captcha', 'SfEventMgt'),
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
                        'remoteip' => $request->getAttribute('normalizedParams')->getRemoteAddress(),
                    ]
                ),
            ]
        );

        $response = $this->requestFactory->request($url, 'POST');

        $body = (string)$response->getBody();
        $responseArray = json_decode($body, true);
        if (!is_array($responseArray) || empty($responseArray) || $responseArray['success'] === false) {
            $this->addError(
                $this->translateErrorMessage('validation.possible_robot', 'SfEventMgt'),
                1631940277
            );
        }
    }
}
