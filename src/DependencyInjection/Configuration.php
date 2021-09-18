<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    final public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('polidog_simple_api');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('use_jms_serializer')
                ->defaultTrue()
            ->end();

        return $treeBuilder;
    }
}
