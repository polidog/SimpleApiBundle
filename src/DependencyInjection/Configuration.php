<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode =$treeBuilder->root('polidog_simple_api');
        $rootNode
            ->children()
                ->booleanNode('use_jms_serializer')
                ->defaultTrue()
            ->end();

        return $treeBuilder;
    }
}
