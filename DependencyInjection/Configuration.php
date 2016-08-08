<?php

namespace Bubnov\TensorFlowBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bubnov_tensor_flow');

        $rootNode
            ->children()
                ->arrayNode('recognizer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('binary')->defaultValue(__DIR__ . '/../../tensorflow-bundle-binaries/label_image')->end()
                        ->scalarNode('graph')->defaultValue(__DIR__ . '/../../tensorflow-bundle-binaries/graph.pb')->end()
                        ->scalarNode('labels')->defaultValue(__DIR__ . '/../../tensorflow-bundle-binaries/labels.txt')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
