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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class CleanupExpiredCommand
 */
class CleanupExpiredCommand extends Command
{
    /**
     * Configuring the command options
     *
     * @return void
     */
    public function configure()
    {
        $this->addOption(
            'delete',
            'd',
            InputOption::VALUE_NONE,
            'If set, registrations will be set to deleted instead of hidden'
        );
    }

    /**
     * Execute the cleanup command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $maintenanceService = GeneralUtility::makeInstance(MaintenanceService::class);
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());
        $delete = (bool)$input->getOption('delete');
        $maintenanceService->handleExpiredRegistrations($delete);
        $io->success('All done!');

        return 0;
    }
}
