<?php

declare(strict_types=1);

namespace Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dbp_relay_sublibrary_connector_base_organization');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('library_code_local_data_attribute')
                    ->info('The organization local data attribute that contains the library code')
                    ->defaultValue('code')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
