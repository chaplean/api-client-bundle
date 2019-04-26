<?php

namespace Chaplean\Bundle\ApiClientBundle\Command;

use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ChapleanApiLogCleanCommand.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Command
 * @author    Hugo - Chaplean <hugo@chaplean.com>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.com)
 */
class ChapleanApiLogCleanCommand extends Command
{
    /** @var ApiLogUtility */
    protected $apiLogUtility;

    /**
     * ChapleanApiLogCleanCommand constructor.
     *
     * @param ApiLogUtility $apiLogUtility
     */
    public function __construct(ApiLogUtility $apiLogUtility)
    {
        parent::__construct();

        $this->apiLogUtility = $apiLogUtility;
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('chaplean:api-log:clean');
        $this->setDescription('Delete logs older than the mentionned date (1 month by default)');
        $this->addArgument(
            'minimumDate',
            InputArgument::OPTIONAL,
            'The logs dated before this date will be removed.'
        );
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $strDate = $input->getArgument('minimumDate') ?: 'now - 1 month midnight';
        dump($strDate);
        $dateLimit = new \DateTime($strDate);

        $apiLogsDeleted = $this->apiLogUtility->deleteMostRecentThan($dateLimit);

        $output->writeln($apiLogsDeleted . ' logs removed');
    }
}
