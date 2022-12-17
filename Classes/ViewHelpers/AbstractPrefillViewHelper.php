<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

abstract class AbstractPrefillViewHelper extends AbstractViewHelper
{
    /**
     * Returns the current plugin namespace
     */
    protected function getPluginNamespace(RequestInterface $request): string
    {
        $pluginSignature = strtolower($request->getControllerExtensionName() . '_' . $request->getPluginName());
        return 'tx_' . $pluginSignature;
    }
}
