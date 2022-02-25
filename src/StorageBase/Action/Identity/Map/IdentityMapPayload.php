<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class IdentityMapPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var DatasetEntityCollection<DatasetEntityContract>
     */
    private DatasetEntityCollection $entityCollection;

    /**
     * @param DatasetEntityCollection<DatasetEntityContract> $entityCollection
     */
    public function __construct(PortalNodeKeyInterface $portalNodeKey, DatasetEntityCollection $entityCollection)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->entityCollection = $entityCollection;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    /**
     * @return DatasetEntityCollection<DatasetEntityContract>
     */
    public function getEntityCollection(): DatasetEntityCollection
    {
        return $this->entityCollection;
    }

    /**
     * @param DatasetEntityCollection<DatasetEntityContract> $entityCollection
     */
    public function setEntityCollection(DatasetEntityCollection $entityCollection): void
    {
        $this->entityCollection = $entityCollection;
    }
}
