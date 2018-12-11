<?php

namespace Chaplean\Bundle\ApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('chaplean_api_client')
            ->children()
                ->booleanNode('enable_database_logging')->defaultFalse()->end()
                ->booleanNode('enable_email_logging')->defaultFalse()->end()
                ->arrayNode('email_logging')
                    ->children()
                        ->arrayNode('codes_listened')
                            ->prototype('enum')->values(
                                [
                                    '0',
                                    '1XX',
                                    '2XX',
                                    '200',
                                    '201',
                                    '202',
                                    '204',
                                    '3XX',
                                    '301',
                                    '302',
                                    '303',
                                    '304',
                                    '307',
                                    '4XX',
                                    '400',
                                    '401',
                                    '403',
                                    '404',
                                    '405',
                                    '406',
                                    '412',
                                    '415',
                                    '5XX',
                                    '500',
                                    '501',
                                ]
                            )->end()
                            ->defaultValue(
                                [
                                    '0',
                                    '1XX',
                                    '2XX',
                                    '3XX',
                                    '4XX',
                                    '5XX'
                                ]
                            )
                        ->end()
                        ->scalarNode('address_from')->isRequired()->end()
                        ->scalarNode('address_to')->isRequired()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
