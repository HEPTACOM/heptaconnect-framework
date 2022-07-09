<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassString;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class MappingComponentStruct extends MappingComponentStructContract
{
    protected PortalNodeKeyInterface $portalNodeKey;

    protected EntityTypeClassString $entityType;

    protected string $externalId;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        EntityTypeClassString $entityType,
        string $externalId
    ) {
        $this->portalNodeKey = $portalNodeKey;
        $this->entityType = $entityType;
        $this->externalId = $externalId;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getEntityType(): EntityTypeClassString
    {
        return $this->entityType;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }
}
