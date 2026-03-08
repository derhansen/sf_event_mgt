<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Format;

use DERHANSEN\SfEventMgt\Utility\CurrencyUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class CurrencySymbolViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('isoCode', 'string', 'ISO 4217 currency code', true);
    }

    /**
     * Returns the currency symbol for the given ISO code
     */
    public function render(): ?string
    {
        $isoCode = $this->arguments['isoCode'];
        $currency = CurrencyUtility::getByIsoCode($isoCode);
        if ($currency === null) {
            return null;
        }

        return $currency['symbol'];
    }
}
