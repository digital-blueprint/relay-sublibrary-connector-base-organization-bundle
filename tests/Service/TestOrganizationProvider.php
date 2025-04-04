<?php

declare(strict_types=1);

namespace Dbp\Relay\SublibraryConnectorBaseOrganizationBundle\Tests\Service;

use Dbp\Relay\BaseOrganizationBundle\API\OrganizationProviderInterface;
use Dbp\Relay\BaseOrganizationBundle\Entity\Organization;
use Dbp\Relay\CoreBundle\Rest\Options;

class TestOrganizationProvider implements OrganizationProviderInterface
{
    public static function toOrganizationName(string $identifier): string
    {
        return 'Organization '.$identifier;
    }

    public static function toOrganizationCode(string $identifier): string
    {
        return 'code_'.$identifier;
    }

    public function getOrganizationById(string $identifier, array $options = []): Organization
    {
        $org = new Organization();
        $org->setIdentifier($identifier);
        $org->setName(self::toOrganizationName($identifier));

        if (($localDataAttributes = Options::getLocalDataAttributes($options)) !== null) {
            if (in_array('some_code', $localDataAttributes, true)) {
                $org->setLocalDataValue('some_code', self::toOrganizationCode($identifier));
            }
        }

        return $org;
    }

    public function getOrganizations(int $currentPageNumber, int $maxNumItemsPerPage, array $options = []): array
    {
        return [
            $this->getOrganizationById('1'),
            $this->getOrganizationById('2'),
        ];
    }
}
