<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class MappingStruct implements MappingInterface
{
    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private MappingNodeKeyInterface $mappingNodeKey,
        private EntityType $entityType
    ) {
    }

    public function getExternalId(): string
    {
        return __METHOD__;
    }

    public function setExternalId(?string $externalId): MappingInterface
    {
        return $this;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getMappingNodeKey(): MappingNodeKeyInterface
    {
        return $this->mappingNodeKey;
    }

    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }
}
