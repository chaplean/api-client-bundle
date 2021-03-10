<?php


namespace Chaplean\Bundle\ApiClientBundle\DependencyInjection\Compiler;

use Chaplean\Bundle\ApiClientBundle\Utility\EmailUtilityInterface;
use Chaplean\Bundle\ApiClientBundle\Utility\SwiftMailerEmailUtility;
use Chaplean\Bundle\ApiClientBundle\Utility\SymfonyMailerEmailUtility;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WireMailerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // we need to decide which mailer we want to use
        if ($container->hasAlias('swiftmailer.mailer.abstract')) {
            $container->removeDefinition(SymfonyMailerEmailUtility::class);
            $container->setAlias(
                EmailUtilityInterface::class,
                SwiftMailerEmailUtility::class
            );
        } else {
            $container->removeDefinition(SwiftMailerEmailUtility::class);
            $container->setAlias(
                EmailUtilityInterface::class,
                SymfonyMailerEmailUtility::class
            );
        }
    }
}
