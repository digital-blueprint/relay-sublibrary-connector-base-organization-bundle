<?php

declare(strict_types=1);

namespace Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\DependencyInjection;

use Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\Service\SublibraryProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class DbpRelaySublibraryConnectorBaseOrganizationExtension extends ConfigurableExtension
{
    public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

        $definition = $container->getDefinition(SublibraryProvider::class);
        $definition->addMethodCall('setConfig', [$mergedConfig]);
    }
}
