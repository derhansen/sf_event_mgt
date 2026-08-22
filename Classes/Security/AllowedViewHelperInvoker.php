<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Security;

use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\Format\DateViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInvoker;

/**
 * A ViewHelper invoker that only allows a defined set of ViewHelpers to be executed.
 * By default, only the DateViewHelper and TranslateViewHelper are allowed. Additional ViewHelpers
 * can be registered via $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['parseStringFluid']['additionalAllowedViewHelpers'].
 * Any ViewHelper not on the allowed list will return a placeholder string instead of being rendered.
 */
class AllowedViewHelperInvoker extends ViewHelperInvoker
{
    private const DEFAULT_ALLOWED_VIEWHELPERS = [
        DateViewHelper::class,
        TranslateViewHelper::class,
    ];

    public function invoke(
        string|ViewHelperInterface $viewHelperClassNameOrInstance,
        array $arguments,
        RenderingContextInterface $renderingContext,
        ?\Closure $renderChildrenClosure = null
    ): mixed {
        $className = is_string($viewHelperClassNameOrInstance)
            ? $viewHelperClassNameOrInstance
            : get_class($viewHelperClassNameOrInstance);

        if (!in_array($className, $this->getAllowedViewHelpers(), true)) {
            // Log disallowed ViewHelper usage and return an empty string
            GeneralUtility::makeInstance(LogManager::class)
                ->getLogger(__CLASS__)
                ->warning('Disallowed ViewHelper blocked', ['viewHelper' => $className]);
            return '';
        }
        return parent::invoke($viewHelperClassNameOrInstance, $arguments, $renderingContext, $renderChildrenClosure);
    }

    private function getAllowedViewHelpers(): array
    {
        $additionalAllowedViewHelpers = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['parseStringFluid']['additionalAllowedViewHelpers'] ?? [];
        return array_values(array_unique(array_merge(self::DEFAULT_ALLOWED_VIEWHELPERS, $additionalAllowedViewHelpers)));
    }
}
