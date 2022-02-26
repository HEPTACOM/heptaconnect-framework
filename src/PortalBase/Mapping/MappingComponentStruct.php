<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class MappingComponentStruct extends MappingComponentStructContract
{
    protected PortalNodeKeyInterface $portalNodeKey;

    /**
     * @psalm-var class-string<DatasetEntityContract>
     */
    protected string $entityType;

    protected string $externalId;

    /**
     * @psalm-param class-string<DatasetEntityContract> $entityType
     */
    public function __construct(PortalNodeKeyInterface $portalNodeKey, string $entityType, string $externalId)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->entityType = $entityType;
        $this->externalId = $externalId;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    /**
     * @psalm-return class-string<DatasetEntityContract>
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }
}
