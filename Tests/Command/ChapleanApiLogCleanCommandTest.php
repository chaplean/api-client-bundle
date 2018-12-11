<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Command;

use Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChapleanApiLogCleanCommandTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Command
 * @author    Hugo - Chaplean <hugo@chaplean.com>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.com)
 */
class ChapleanApiLogCleanCommandTest extends MockeryTestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::configure()
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::execute()
     *
     * @return void
     */
    public function testExecute()
    {
        $mockApiLogUtility = \Mockery::mock(ApiLogUtility::class);

        $container = \Mockery::mock(ContainerInterface::class);
        $container->shouldReceive('get')
            ->once()
            ->with(ApiLogUtility::class)
            ->andReturn($mockApiLogUtility);

        $mockApiLogUtility->shouldReceive('deleteMostRecentThan')->andReturn(15);

        $command = new ChapleanApiLogCleanCommand();
        $command->setContainer($container);

        $input = new ArgvInput([]);
        $output = new NullOutput();
        $command->run($input, $output);
    }
}
