<?php

namespace Chaplean\Bundle\ApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

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
        $treeBuilder = new TreeBuilder('chaplean_api_client');
        $rootNode = $this->getRootNode($treeBuilder);

        $rootNode->children()
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

        $this->addLoggingNode($rootNode, 'enable_database_logging');
        $this->addLoggingNode($rootNode, 'enable_email_logging');

        return $treeBuilder;
    }

    /**
     * Inspired by monolog channels
     *
     * @param ArrayNodeDefinition $rootNode
     * @param string              $nodeName
     *
     * @return void
     */
    public function addLoggingNode(ArrayNodeDefinition $rootNode, string $nodeName)
    {
        $rootNode
            ->children()
                ->arrayNode($nodeName)
                    ->beforeNormalization() // false value
                        ->ifTrue(function ($v) { return is_bool($v) && !$v; })
                        ->thenUnset()
                    ->end()
                    ->beforeNormalization() // string value
                        ->ifString()
                        ->then(function ($v) { return ['elements' => [$v]]; })
                    ->end()
                    ->beforeNormalization() // ~ value
                        ->ifNull()
                        ->then(function () { return ['elements' => []]; })
                    ->end()
                    ->beforeNormalization() // true value (deprecated value)
                        ->ifTrue(function ($v) { is_bool($v) && $v; })
                        ->then(function () { return ['elements' => []]; })
                    ->end()
                    ->beforeNormalization() // empty array value
                        ->ifTrue(function ($v) { return empty($v); })
                        ->then(function () { return ['type' => 'inclusive', 'elements' => []]; })
                    ->end()
                    ->beforeNormalization() // array value
                        ->ifTrue(function ($v) { return is_array($v) && is_numeric(key($v)); })
                        ->then(function ($v) { return ['elements' => $v]; })
                    ->end()
                    ->validate()
                        ->always(function ($v) use ($nodeName) {
                            if (empty($v['elements']) && !isset($v['type'])) {
                                return null;
                            }

                            $isExclusive = null;
                            if (isset($v['type'])) {
                                $isExclusive =  $v['type'] === 'exclusive';
                            }

                            $elements = [];
                            foreach ($v['elements'] as $element) {
                                if (strpos($element, '!') === 0) {
                                    if ($isExclusive === false) {
                                        throw new InvalidConfigurationException('Cannot combine exclusive/inclusive definitions in ' . $nodeName . ' list.');
                                    }
                                    $elements[] = substr($element, 1);
                                    $isExclusive = true;
                                } else {
                                    if ($isExclusive === true) {
                                        throw new InvalidConfigurationException('Cannot combine exclusive/inclusive definitions in ' . $nodeName . ' list');
                                    }
                                    $elements[] = $element;
                                    $isExclusive = false;
                                }
                            }

                            return ['type' => $isExclusive ? 'exclusive' : 'inclusive', 'elements' => $elements];
                        })
                    ->end()
                    ->children()
                        ->scalarNode('type')
                            ->validate()
                                ->ifNotInArray(['inclusive', 'exclusive'])
                                ->thenInvalid('The type of ' . $nodeName . ' has to be inclusive or exclusive')
                            ->end()
                        ->end()
                        ->arrayNode('elements')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param TreeBuilder $treeBuilder
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    protected function getRootNode(TreeBuilder $treeBuilder)
    {
        if (method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->getRootNode();
        }

        return $treeBuilder->root('chaplean_api_client');;
    }
}
