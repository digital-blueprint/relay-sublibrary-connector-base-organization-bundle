<?php

declare(strict_types=1);

namespace Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\Service;

use Dbp\Relay\BaseOrganizationBundle\API\OrganizationProviderInterface;
use Dbp\Relay\CoreBundle\Exception\ApiError;
use Dbp\Relay\CoreBundle\Rest\Options;
use Dbp\Relay\SublibraryBundle\API\SublibraryInterface;
use Dbp\Relay\SublibraryBundle\API\SublibraryProviderInterface;
use Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\Event\BaseOrganizationSublibrary;
use Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\Event\SublibraryProviderPostEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SublibraryProvider implements SublibraryProviderInterface
{
    /** @var OrganizationProviderInterface */
    private $organizationProvider;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    private array $config;

    public function __construct(OrganizationProviderInterface $organizationProvider, EventDispatcherInterface $eventDispatcher)
    {
        $this->organizationProvider = $organizationProvider;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getSublibrary(string $identifier, array $options = []): ?SublibraryInterface
    {
        $libraryCodeLocalDataAttribute = $this->config['library_code_local_data_attribute'];
        $organization = null;
        try {
            Options::requestLocalDataAttributes($options, [$libraryCodeLocalDataAttribute]);
            $organization = $this->organizationProvider->getOrganizationById($identifier, $options);
        } catch (ApiError $exception) {
        }

        $sublibrary = null;
        if ($organization !== null) {
            $sublibrary = new BaseOrganizationSublibrary();
            $sublibrary->setIdentifier($organization->getIdentifier());
            $sublibrary->setName($organization->getName());
            $sublibrary->setCode($organization->getLocalDataValue($libraryCodeLocalDataAttribute));
        }

        $postEvent = new SublibraryProviderPostEvent($identifier, $sublibrary, $organization, $options);
        $this->eventDispatcher->dispatch($postEvent);

        return $postEvent->getSublibrary();
    }
}
