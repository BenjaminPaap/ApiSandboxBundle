<?php

namespace Bpa\ApiSandboxBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('api_sandbox');

        $rootNode
            ->children()
                ->scalarNode('force_response')->defaultFalse()->end()
                ->scalarNode('enabled')->defaultFalse()->end()
            ->end();

        return $treeBuilder;
    }
}
