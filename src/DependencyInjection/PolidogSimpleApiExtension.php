<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\DependencyInjection;

use Polidog\SimpleApiBundle\ResponseHandler\JsonHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class PolidogSimpleApiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = new Definition(JsonHandler::class);
        $definition->setArguments([
            '$useJmsSerializer' => $config['use_jms_serializer'],
        ]);
        $definition->setAutowired(true);
        $container->setDefinition(JsonHandler::class, $definition);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
