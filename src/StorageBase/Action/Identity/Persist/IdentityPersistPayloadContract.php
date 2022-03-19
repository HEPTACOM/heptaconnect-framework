<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

/**
 * Base class for every identity persist type as they all share to effect a mapping node.
 *
 * @see IdentityPersistCreatePayload
 * @see IdentityPersistDeletePayload
 * @see IdentityPersistUpdatePayload
 */
abstract class IdentityPersistPayloadContract implements CreatePayloadInterface
{
    private MappingNodeKeyInterface $mappingNodeKey;

    /**
     * Set the key for the mapping node to write to.
     */
    public function __construct(MappingNodeKeyInterface $mappingNodeKey)
    {
        $this->mappingNodeKey = $mappingNodeKey;
    }

    /**
     * Get the key for the mapping node to write to.
     */
    public function getMappingNodeKey(): MappingNodeKeyInterface
    {
        return $this->mappingNodeKey;
    }

    /**
     * Set the key for the mapping node to write to.
     */
    public function setMappingNodeKey(MappingNodeKeyInterface $mappingNodeKey): void
    {
        $this->mappingNodeKey = $mappingNodeKey;
    }
}
