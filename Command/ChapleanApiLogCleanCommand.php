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
    /** @var string */
    protected static $defaultName = 'chaplean:api-log:clean';

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
        $this->setDescription('Delete logs older than the mentionned date (1 month by default)');
        $this->addArgument(
            'minimumDate',
            InputArgument::OPTIONAL,
            'The logs after the mentionned date will be kept. (Format: PHP\'s DateTime string)',
            'now -1 month midnight'
        );
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $strDate = $input->getArgument('minimumDate');
        $dateLimit = null;

        try {
            $dateLimit = new \DateTime($strDate);
        } catch (\Exception $e) {
            $output->writeln(
                sprintf('The date "%s" is an unvalid string for the PHP\'s DateTime constructor.', $strDate)
            );

            return 1;
        }

        $apiLogsDeleted = $this->apiLogUtility->deleteMostRecentThan($dateLimit);

        $output->writeln($apiLogsDeleted . ' logs removed');
    }
}
