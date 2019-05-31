<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\DependencyInjection;

use Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class ConfigurationTest.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Tests\DependencyInjection
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2019 Chaplean (https://www.chaplean.coop)
 */
class ConfigurationTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingNoValue()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayNotHasKey('enable_database_logging', $finalizedConfig);
    }

    /**
     * Keep compatibility with the older values
     *
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingFalse()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => false
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayNotHasKey('enable_database_logging', $finalizedConfig);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingTildeValue()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => null
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_database_logging', $finalizedConfig);
        $this->assertNull($finalizedConfig['enable_database_logging']);
    }

    /**
     * Keep compatibility with the older values
     *
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingTrue()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => true
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_database_logging', $finalizedConfig);
        $this->assertNull($finalizedConfig['enable_database_logging']);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingEmptyArray()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => []
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_database_logging', $finalizedConfig);
        $this->assertSame(
            [
                'type'     => 'inclusive',
                'elements' => [],

            ],
            $finalizedConfig['enable_database_logging']
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingStringValue()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => 'api_1'
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_database_logging', $finalizedConfig);
        $this->assertSame(
            [
                'type'     => 'inclusive',
                'elements' => ['api_1'],

            ],
            $finalizedConfig['enable_database_logging']
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingInclusiveApis()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => ['api_1']
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_database_logging', $finalizedConfig);
        $this->assertSame(
            [
                'type'     => 'inclusive',
                'elements' => ['api_1'],

            ],
            $finalizedConfig['enable_database_logging']
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingExclusiveApis()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => ['!api_1']
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_database_logging', $finalizedConfig);
        $this->assertSame(
            [
                'type'     => 'exclusive',
                'elements' => ['api_1'],

            ],
            $finalizedConfig['enable_database_logging']
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableDatabaseLoggingCombineExclusiveInclusiveApis()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Cannot combine exclusive/inclusive definitions in enable_database_logging list');

        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => ['api_2', '!api_1']
        ]);
        $node->finalize($normalizedConfig);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingNoValue()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayNotHasKey('enable_email_logging', $finalizedConfig);
    }

    /**
     * Keep compatibility with the older values
     *
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingFalse()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => false
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayNotHasKey('enable_database_logging', $finalizedConfig);
    }


    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingTildeValue()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_email_logging' => null
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_email_logging', $finalizedConfig);
        $this->assertNull($finalizedConfig['enable_email_logging']);
    }

    /**
     * Keep compatibility with the older values
     *
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingTrue()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_database_logging' => true
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_database_logging', $finalizedConfig);
        $this->assertNull($finalizedConfig['enable_database_logging']);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingEmptyArray()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_email_logging' => []
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_email_logging', $finalizedConfig);
        $this->assertSame(
            [
                'type'     => 'inclusive',
                'elements' => [],

            ],
            $finalizedConfig['enable_email_logging']
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingStringValue()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_email_logging' => 'api_1'
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_email_logging', $finalizedConfig);
        $this->assertSame(
            [
                'type'     => 'inclusive',
                'elements' => ['api_1'],

            ],
            $finalizedConfig['enable_email_logging']
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingInclusiveApis()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_email_logging' => ['api_1']
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_email_logging', $finalizedConfig);
        $this->assertSame(
            [
                'type'     => 'inclusive',
                'elements' => ['api_1'],

            ],
            $finalizedConfig['enable_email_logging']
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingExclusiveApis()
    {
        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_email_logging' => ['!api_1']
        ]);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertArrayHasKey('enable_email_logging', $finalizedConfig);
        $this->assertSame(
            [
                'type'     => 'exclusive',
                'elements' => ['api_1'],

            ],
            $finalizedConfig['enable_email_logging']
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\DependencyInjection\Configuration
     *
     * @return void
     */
    public function testEnableEmailLoggingCombineExclusiveInclusiveApis()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Cannot combine exclusive/inclusive definitions in enable_email_logging list');

        $config = new Configuration();

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'enable_email_logging' => ['api_2', '!api_1']
        ]);
        $node->finalize($normalizedConfig);
    }
}
