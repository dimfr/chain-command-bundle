<?php

namespace Dimfr\Bundle\ChainCommandBundle;

use Dimfr\Bundle\ChainCommandBundle\DependencyInjection\CompilerPass\TaggedChainsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ChainCommandBundle
 * @package Dimfr\Bundle\ChainCommandBundle
 */
class ChainCommandBundle extends Bundle
{
    /**
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TaggedChainsPass());

        $this->configureLogger($container);
    }

    /**
     * Adds chain_command logger to the monolog bundle configuration
     *
     * @param ContainerBuilder $container
     */
    protected function configureLogger(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('monolog', [
            'handlers' => [
                'chain_command' => [
                    'type' => 'stream',
                    'verbosity_levels' => ['VERBOSITY_NORMAL' => 'NOTICE'],
                    'channels' => ['chain_command'],
                    'process_psr_3_messages' => true,
                    'formatter' => 'monolog.formatter.chain_command'
                ]
            ]
        ]);
    }
}