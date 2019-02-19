<?php

namespace JRemmurd\CopyrightAttributionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('copyright_attribution');

        $rootNode
            ->children()
                ->scalarNode("route")->end()
                ->arrayNode('subjects')
                    ->arrayPrototype()
                        ->children()
                            ->enumNode("display")->values(["table", "list"])->defaultValue("table")->end()
                            ->arrayNode('credits')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('author')->isRequired()->end()
                                        ->scalarNode('author_url')->end()
                                        ->scalarNode('source')->end()
                                        ->scalarNode('source_url')->end()
                                        ->scalarNode('license')->end()
                                        ->scalarNode('license_url')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}