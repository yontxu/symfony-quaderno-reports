<?php

namespace YZubizarreta\QuadernoReportsBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('yz_quaderno_reports');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
                    ->children()
                        ->arrayNode('api')
                            ->children()
                                ->variableNode('private_key')->end()
                                ->variableNode('public_key')->end()
                                ->variableNode('api_url')->end()
                                ->variableNode('version')->end()
                            ->end()
                        ->end()
                        ->arrayNode('email')
                            ->children()
                                ->variableNode('from')->end()
                                ->variableNode('to')->end()
                            ->end()
                        ->end()
                    ->end()
                ;

        return $treeBuilder;
    }
}
