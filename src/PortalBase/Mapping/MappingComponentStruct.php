<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class MappingComponentStruct extends MappingComponentStructContract
{
    public function __construct(
        private readonly PortalNodeKeyInterface $portalNodeKey,
        private readonly EntityType $entityType,
        private readonly string $externalId
    ) {
    }

    #[\Override]
    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    #[\Override]
    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }

    #[\Override]
    public function getExternalId(): string
    {
        return $this->externalId;
    }
}
