<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StorageMappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StoragePortalNodeKeyInterface;

class MappingStruct implements MappingInterface
{
    private StoragePortalNodeKeyInterface $portalNodeKey;

    private StorageMappingNodeKeyInterface $mappingNodeKey;

    public function __construct(
        StoragePortalNodeKeyInterface $portalNodeKey,
        StorageMappingNodeKeyInterface $mappingNodeKey
    ) {
        $this->portalNodeKey = $portalNodeKey;
        $this->mappingNodeKey = $mappingNodeKey;
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

    public function getMappingNodeKey(): StorageMappingNodeKeyInterface
    {
        return $this->mappingNodeKey;
    }

    public function getDatasetEntityClassName(): string
    {
        return DatasetEntityStruct::class;
    }
}
