<?php

namespace Dimfr\Bundle\ChainCommandBundle\DependencyInjection\CompilerPass;

use Dimfr\Bundle\ChainCommandBundle\Chain\ChainProviderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TaggedChainsPass
 * @package Dimfr\Bundle\ChainCommandBundle\DependencyInjection\CompilerPass
 */
class TaggedChainsPass implements CompilerPassInterface
{
    /**
     * Collect all services with the tag 'chain.command' into ChainProvider
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $serviceIds = $container->findTaggedServiceIds('chain.command');

        if ($serviceIds) {
            $registry = $container->getDefinition(ChainProviderInterface::class);

            foreach ($serviceIds as $className => $tags) {

                foreach ($tags as $tag) {
                    $registry->addMethodCall('addChainMember', [$className, $tag]);
                }
            }
        }
    }
}