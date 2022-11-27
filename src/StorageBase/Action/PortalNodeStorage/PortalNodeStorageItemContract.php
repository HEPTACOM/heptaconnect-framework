<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * Describes a row in the storage for a portal node storage item.
 */
abstract class PortalNodeStorageItemContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private string $storageKey,
        private string $type,
        private string $value
    ) {
        $this->attachments = new AttachmentCollection();
    }

    /**
     * Gets the portal node the storage item is set for.
     */
    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    /**
     * Gets the key of the storage item.
     */
    public function getStorageKey(): string
    {
        return $this->storageKey;
    }

    /**
     * Get the type of the storage item.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the value of the storage item.
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
