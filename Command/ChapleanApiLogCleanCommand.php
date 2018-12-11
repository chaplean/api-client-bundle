<?php

namespace Chaplean\Bundle\ApiClientBundle\Command;

use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ChapleanApiLogCleanCommand.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Command
 * @author    Hugo - Chaplean <hugo@chaplean.com>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.com)
 */
class ChapleanApiLogCleanCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('chaplean:api-log:clean');
        $this->setDescription('Delete logs older than one month');
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ApiLogUtility $apiLogUtility */
        $apiLogUtility = $this->getContainer()->get(ApiLogUtility::class);

        $dateLimit = new \DateTime('now - 1 month midnight');
        $apiLogsDeleted = $apiLogUtility->deleteMostRecentThan($dateLimit);

        $output->writeln($apiLogsDeleted . ' logs removed');
    }
}
