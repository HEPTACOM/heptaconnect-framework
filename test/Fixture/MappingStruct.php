<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StoragePortalNodeKeyInterface;

class MappingStruct implements MappingInterface
{
    private StoragePortalNodeKeyInterface $portalNodeKey;

    public function __construct(StoragePortalNodeKeyInterface $portalNodeKey)
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getExternalId(): string
    {
        return __METHOD__;
    }

    public function setExternalId(string $externalId): MappingInterface
    {
        return $this;
    }

    public function getPortalNodeKey(): StoragePortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getMappingNodeId(): string
    {
        return __METHOD__;
    }

    public function getDatasetEntityClassName(): string
    {
        return DatasetEntityStruct::class;
    }
}
