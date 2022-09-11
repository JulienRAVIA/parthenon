<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022, all rights reserved.
 */

namespace Parthenon\DependencyInjection\Modules;

use Parthenon\Funnel\Repository\FunnelRepositoryInterface;
use Parthenon\Funnel\UnfinnishedActions\ActionInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class Funnel implements ModuleConfigurationInterface
{
    public function addConfig(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder
            ->arrayNode('funnel')
                ->children()
                    ->booleanNode('enabled')->end()
                ->end()
            ->end();
    }

    public function handleDefaultParameters(ContainerBuilder $container): void
    {
        // TODO: Implement handleDefaultParameters() method.
    }

    public function handleConfiguration(array $config, ContainerBuilder $container): void
    {
        if (!isset($config['funnel']) || !isset($config['funnel']['enabled']) || false == $config['funnel']['enabled']) {
            return;
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));
        $loader->load('services/funnel.xml');

        $container->registerForAutoconfiguration(ActionInterface::class)->addTag('parthenon.funnel.action');
        $container->registerForAutoconfiguration(FunnelRepositoryInterface::class)->addTag('parthenon.funnel.repository');
    }
}
