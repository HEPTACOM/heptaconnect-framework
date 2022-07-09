<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping\Contract;

use Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassString;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * Resembles a mapping in the storage.
 * When a storage independent reference is needed, use @see MappingComponentStructContract
 */
interface MappingInterface
{
    /**
     * Get the primary key used by the referenced identity.
     */
    public function getExternalId(): ?string;

    /**
     * Set the primary key used by the referenced identity.
     */
    public function setExternalId(?string $externalId): self;

    /**
     * Get portal node of the mapping.
     */
    public function getPortalNodeKey(): PortalNodeKeyInterface;

    /**
     * Get mapping node of the mapping.
     */
    public function getMappingNodeKey(): MappingNodeKeyInterface;

    /**
     * Get entity type of the mapping.
     */
    public function getEntityType(): EntityTypeClassString;
}
