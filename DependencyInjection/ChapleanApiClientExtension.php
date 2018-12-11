<?php

namespace Chaplean\Bundle\ApiClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ChapleanApiClientExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('chaplean_api_client.config', $config);
        $this->setParameters($container, 'chaplean_api_client.config', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $name
     * @param array            $config
     *
     * @return void
     */
    public function setParameters(ContainerBuilder $container, $name, array $config)
    {
        foreach ($config as $key => $parameter) {
            $container->setParameter($name . '.' . $key, $parameter);

            if (is_array($parameter)) {
                $this->setParameters($container, $name . '.' . $key, $parameter);
            }
        }
    }
}
