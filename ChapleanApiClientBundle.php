<?php

namespace Chaplean\Bundle\ApiClientBundle;

use Chaplean\Bundle\ApiClientBundle\DependencyInjection\Compiler\WireMailerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ChapleanApiClientBundle.
 *
 * @package   Chaplean\Bundle\ApiClientBundle
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ChapleanApiClientBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new WireMailerCompilerPass());
    }
}
