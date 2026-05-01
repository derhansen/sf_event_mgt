<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\DataHandling\Event\BeforeRemoveNonCopyableFieldsEvent;

final class ModifyNonCopyableFields
{
    #[AsEventListener('sfeventmgt/modify-non-copyable-fields')]
    public function __invoke(BeforeRemoveNonCopyableFieldsEvent $event): void
    {
        if ($event->getTable() !== 'tx_sfeventmgt_domain_model_event') {
            return;
        }

        $fields = $event->getNonCopyableFields();
        $fields[] = 'registration';
        $event->setNonCopyableFields($fields);
    }
}
