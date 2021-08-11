<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class MappingPersistPayload
{
    private PortalNodeKeyInterface $portalNodeKey;

    private array $createMappings = [];

    private array $updateMappings = [];

    private array $deleteMappings = [];

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function create(MappingNodeKeyInterface $mappingNodeKey, string $externalId): void
    {
        $this->createMappings[] = [
            'mappingNodeKey' => $mappingNodeKey,
            'externalId' => $externalId,
        ];
    }

    public function getCreateMappings(): array
    {
        return $this->createMappings;
    }

    public function update(MappingNodeKeyInterface $mappingNodeKey, string $externalId): void
    {
        $this->updateMappings[] = [
            'mappingNodeKey' => $mappingNodeKey,
            'externalId' => $externalId,
        ];
    }

    public function getUpdateMappings(): array
    {
        return $this->updateMappings;
    }

    public function delete(MappingNodeKeyInterface $mappingNodeKey): void
    {
        $this->deleteMappings[] = $mappingNodeKey;
    }

    public function getDeleteMappings(): array
    {
        return $this->deleteMappings;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }
}
