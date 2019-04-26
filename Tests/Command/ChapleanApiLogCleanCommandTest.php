<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Command;

use Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChapleanApiLogCleanCommandTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Command
 * @author    Hugo - Chaplean <hugo@chaplean.com>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.com)
 */
class ChapleanApiLogCleanCommandTest extends MockeryTestCase
{
    /**
     * @var ApiLogUtility|MockInterface
     */
    protected $apiLogUtility;

    /**
     * @var ChapleanApiLogCleanCommand
     */
    private $command;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->apiLogUtility = \Mockery::mock(ApiLogUtility::class);

        $this->command = new ChapleanApiLogCleanCommand($this->apiLogUtility);
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::configure()
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::execute()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testExecuteWithDefaultDate()
    {
        $this->apiLogUtility->shouldReceive('deleteMostRecentThan')->once()->andReturn(1);

        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();

        $this->assertContains('1 logs removed', $output);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::configure()
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::execute()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testExecuteWithDate()
    {
        $this->apiLogUtility->shouldReceive('deleteMostRecentThan')->once()->andReturn(1);

        $this->commandTester->execute(['minimumDate' => '-1 day']);

        $output = $this->commandTester->getDisplay();

        $this->assertContains('1 logs removed', $output);
    }
}
