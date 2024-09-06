<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Mvc\Request;

class PrefillViewHelper extends AbstractPrefillViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('fieldname', 'string', 'Fieldname', true);
        $this->registerArgument('prefillSettings', 'array', 'PrefillSettings', false, []);
    }

    /**
     * If the current field is available in POST data of the current request, return the value, otherwise
     * a property from fe_user (if logged in and if the given field is configured to be prefilled) is returned.
     */
    public function render(): string
    {
        $fieldname = $this->arguments['fieldname'];
        $prefillSettings = $this->arguments['prefillSettings'];

        /** @var Request $request */
        $request = $this->renderingContext->getAttribute(ServerRequestInterface::class);
        $registrationData = $request->getParsedBody()[$this->getPluginNamespace($request)] ?? [];
        if (isset($registrationData['registration'][$fieldname])) {
            return $registrationData['registration'][$fieldname];
        }

        $frontendUser = $request->getAttribute('frontend.user');
        if (!$frontendUser->user || empty($prefillSettings) ||
            !array_key_exists($fieldname, $prefillSettings)
        ) {
            return '';
        }

        return (string)($frontendUser->user[$prefillSettings[$fieldname]]);
    }
}
