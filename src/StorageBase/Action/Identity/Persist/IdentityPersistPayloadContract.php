<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

abstract class IdentityPersistPayloadContract implements CreatePayloadInterface
{
    private MappingNodeKeyInterface $mappingNodeKey;

    public function __construct(MappingNodeKeyInterface $mappingNodeKey)
    {
        $this->mappingNodeKey = $mappingNodeKey;
    }

    public function getMappingNodeKey(): MappingNodeKeyInterface
    {
        return $this->mappingNodeKey;
    }

    public function setMappingNodeKey(MappingNodeKeyInterface $mappingNodeKey): void
    {
        $this->mappingNodeKey = $mappingNodeKey;
    }
}
