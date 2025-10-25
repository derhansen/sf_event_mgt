<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Command;

use DERHANSEN\SfEventMgt\Service\MaintenanceService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[AsCommand(
    'sf_event_mgt:cleanup:gdpr',
    'Delete registrations of expired events (registrations for events with no enddate will be ignored).'
)]
class CleanupGdprCommand extends Command
{
    public function configure(): void
    {
        $this
            ->addArgument(
                'days',
                InputArgument::REQUIRED,
                'Amount of days reduced from todays date for expired event selection'
            )
            ->addOption(
                'softDelete',
                's',
                InputOption::VALUE_NONE,
                'If set, registration will not be deleted hard, but only flagged as deleted'
            )
            ->addOption(
                'ignoreEventRestriction',
                'i',
                InputOption::VALUE_NONE,
                'If set, simply all available registrations will be selected and deleted. Use with care!'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $maintenanceService = GeneralUtility::makeInstance(MaintenanceService::class);
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());
        $days = (int)$input->getArgument('days');
        $softDelete = (bool)$input->getOption('softDelete');
        $ignoreEventRestriction = (bool)$input->getOption('ignoreEventRestriction');
        $amountDeleted = $maintenanceService->processGdprCleanup($days, $softDelete, $ignoreEventRestriction);
        $io->success($amountDeleted . ' registrations deleted.');

        return Command::SUCCESS;
    }
}
