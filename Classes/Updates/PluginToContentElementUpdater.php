<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('sfEventMgtPluginToContentElementUpdate')]
class PluginToContentElementUpdater extends AbstractListTypeToCTypeUpdate
{
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'sfeventmgt_pieventlist' => 'sfeventmgt_pieventlist',
            'sfeventmgt_pieventdetail' => 'sfeventmgt_pieventdetail',
            'sfeventmgt_pieventregistration' => 'sfeventmgt_pieventregistration',
            'sfeventmgt_pieventsearch' => 'sfeventmgt_pieventsearch',
            'sfeventmgt_pieventcalendar' => 'sfeventmgt_pieventcalendar',
            'sfeventmgt_piuserreg' => 'sfeventmgt_piuserreg',
            'sfeventmgt_pipayment' => 'sfeventmgt_pipayment',
        ];
    }

    public function getTitle(): string
    {
        return 'ext:sf_event_mgt: Migrate plugins to content elements';
    }

    public function getDescription(): string
    {
        return 'Migrates existing plugin records and backend user permissions used by ext:sf_event_mgt.';
    }
}
