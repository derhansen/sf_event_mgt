<?php
declare(strict_types=1);
namespace DERHANSEN\SfEventMgt\SpamChecks;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * LinksSpamCheck
 */
class LinkSpamCheck extends AbstractSpamCheck
{
    /**
     * Counts the amount of links in all fields/registration fields and evaluates, if the found amount
     * of links is greater than the configured max. amount of links
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        $amountOfLinks = 0;

        // First check all accessible properties that are strings
        foreach ($this->registration->_getProperties() as $value) {
            if (!is_string($value)) {
                continue;
            }

            $amountOfLinks += $this->getAmountOfLinks($value);
        }

        // Next check all values of possible registration fields
        foreach ($this->registration->getFieldValues() as $fieldValue) {
            if (!is_string($fieldValue->getValue())) {
                continue;
            }

            $amountOfLinks += $this->getAmountOfLinks($fieldValue->getValue());
        }

        return $amountOfLinks > (int)$this->configuration['maxAmountOfLinks'];
    }

    /**
     * Returns the amount of links
     *
     * @param string $value
     * @return int
     */
    private function getAmountOfLinks(string $value): int
    {
        $pattern = '~[a-z]+://\S+~';
        $amount = preg_match_all($pattern, $value, $out);
        if ($amount !== false) {
            return $amount;
        } else {
            return 0;
        }
    }
}
