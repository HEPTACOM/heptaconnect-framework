<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class MappingStruct implements MappingInterface
{
    public function __construct(
        private PortalNodeKeyInterface $portalNodeId,
        private MappingNodeKeyInterface $mappingNodeKey
    ) {
    }

    #[\Override]
    public function getExternalId(): string
    {
        return __METHOD__;
    }

    #[\Override]
    public function setExternalId(?string $externalId): MappingInterface
    {
        return $this;
    }

    #[\Override]
    public function getMappingNodeKey(): MappingNodeKeyInterface
    {
        return $this->mappingNodeKey;
    }

    #[\Override]
    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeId;
    }

    #[\Override]
    public function getEntityType(): EntityType
    {
        return FooBarEntity::class();
    }
}
