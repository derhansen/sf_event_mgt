<?php

defined('TYPO3') or die();

use TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask;

// Register table for garbage collection task
if (isset($GLOBALS['TCA']['tx_scheduler_task'])) {
    $GLOBALS['TCA']['tx_scheduler_task']['types'][TableGarbageCollectionTask::class]['taskOptions']['tables']['tx_sfeventmgt_domain_model_registration_fieldvalue'] = [
        'dateField' => 'tstamp',
        'expirePeriod' => 30,
    ];
}
