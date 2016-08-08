<?php

namespace Bubnov\TensorFlowBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 */
class BubnovTensorFlowExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $definition = $container->getDefinition('tenserflow.recognizer');
        $definition
            ->replaceArgument(0, $config['recognizer']['binary'])
            ->replaceArgument(1, $config['recognizer']['graph'])
            ->replaceArgument(2, $config['recognizer']['labels'])
        ;
    }
}
