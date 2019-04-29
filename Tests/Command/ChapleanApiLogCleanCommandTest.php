<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Command;

use Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Symfony\Component\Console\Tester\CommandTester;

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
    private $chapleanApiLogCleanCommand;

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

        $this->chapleanApiLogCleanCommand = new ChapleanApiLogCleanCommand($this->apiLogUtility);
        $this->commandTester = new CommandTester($this->chapleanApiLogCleanCommand);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::__construct()
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(ChapleanApiLogCleanCommand::class, $this->chapleanApiLogCleanCommand);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::configure()
     *
     * @return void
     */
    public function testConfigure()
    {
        $arguments = $this->chapleanApiLogCleanCommand
            ->getDefinition()
            ->getArguments();
        $minimumDateArgument = \array_values($arguments)[0];

        $this->assertSame('chaplean:api-log:clean', $this->chapleanApiLogCleanCommand->getName());
        $this->assertSame('Delete logs older than the mentionned date (1 month by default)', $this->chapleanApiLogCleanCommand->getDescription());
        $this->assertCount(1, $arguments);
        $this->assertContains('minimumDate', $minimumDateArgument->getName());
        $this->assertContains('The logs after the mentionned date will be kept', $minimumDateArgument->getDescription());
        $this->assertContains('now -1 month midnight', $minimumDateArgument->getDefault());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::execute()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testExecuteWithDefaultDate()
    {
        $this->apiLogUtility
            ->shouldReceive('deleteMostRecentThan')
            ->once()
            ->andReturn(1);

        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();

        $this->assertContains('1 logs removed', $output);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::execute()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testExecuteWithUnvalidDate()
    {
        $this->apiLogUtility->shouldNotReceive('deleteMostRecentThan');

        $this->commandTester->execute(['minimumDate' => 'test']);

        $output = $this->commandTester->getDisplay();

        $this->assertContains('The date "test" is an unvalid string for the PHP\'s DateTime constructor', $output);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Command\ChapleanApiLogCleanCommand::execute()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testExecuteWithDate()
    {
        $this->apiLogUtility
            ->shouldReceive('deleteMostRecentThan')
            ->once()
            ->andReturn(1);

        $this->commandTester->execute(['minimumDate' => '-1 day']);

        $output = $this->commandTester->getDisplay();

        $this->assertContains('1 logs removed', $output);
    }
}
