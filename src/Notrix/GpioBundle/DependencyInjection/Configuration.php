<?php

namespace Notrix\GpioBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('notrix_gpio')
                ->children()
                    ->floatNode('watcher_interval')->min(0.001)->defaultValue(0.35)->end()
                    ->booleanNode('development')->defaultFalse()->end()
                    ->booleanNode('sudo')->defaultTrue()->end()
                    ->arrayNode('out')
                        ->ignoreExtraKeys()
                        ->performNoDeepMerging()
                        ->prototype('array')
                            ->children()
                                ->scalarNode('slug')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('in')
                        ->ignoreExtraKeys()
                        ->performNoDeepMerging()
                        ->prototype('array')
                            ->children()
                                ->scalarNode('slug')->end()
                                ->arrayNode('event')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
