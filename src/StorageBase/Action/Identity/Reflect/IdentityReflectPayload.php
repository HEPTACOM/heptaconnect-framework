<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Reflect;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class IdentityReflectPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private MappedDatasetEntityCollection $mappedDatasetEntities;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        MappedDatasetEntityCollection $mappedDatasetEntities
    ) {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->mappedDatasetEntities = $mappedDatasetEntities;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getMappedDatasetEntities(): MappedDatasetEntityCollection
    {
        return $this->mappedDatasetEntities;
    }

    public function setMappedDatasetEntities(MappedDatasetEntityCollection $mappedDatasetEntities): void
    {
        $this->mappedDatasetEntities = $mappedDatasetEntities;
    }
}
