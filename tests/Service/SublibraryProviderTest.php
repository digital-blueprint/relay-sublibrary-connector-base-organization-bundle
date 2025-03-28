<?php

declare(strict_types=1);

namespace Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\Service\SublibraryProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SublibraryProviderTest extends ApiTestCase
{
    /** @var SublibraryProvider */
    private $sublibraryProvider;

    protected function setUp(): void
    {
        $eventDispatcher = new EventDispatcher();
        $this->sublibraryProvider = new SublibraryProvider(new TestOrganizationProvider(), $eventDispatcher);
        $this->sublibraryProvider->setConfig(['library_code_local_data_attribute' => 'some_code']);
    }

    public function testGetSublibrary(): void
    {
        $sublibraryId = '123';
        $sublibrary = $this->sublibraryProvider->getSublibrary($sublibraryId);
        $this->assertSame($sublibraryId, $sublibrary->getIdentifier());
        $this->assertSame(TestOrganizationProvider::toOrganizationName($sublibraryId), $sublibrary->getName());
        $this->assertSame(TestOrganizationProvider::toOrganizationCode($sublibraryId), $sublibrary->getCode());
    }
}
