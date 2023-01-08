<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\ForeignKeyTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsharableOwnerException;

final class PrimaryKeySharingMappingStruct implements AttachmentAwareInterface, AttachableInterface, ForeignKeyAwareInterface, MappingInterface
{
    use AttachmentAwareTrait;
    use ForeignKeyTrait;

    /**
     * @var class-string<DatasetEntityContract>
     */
    private string $entityType;

    /**
     * @var DatasetEntityContract[]
     */
    private array $owners = [];

    public function __construct(
        EntityType $entityType,
        private ?string $externalId,
        private PortalNodeKeyInterface $portalNodeKey,
        private MappingNodeKeyInterface $mappingNodeKey
    ) {
        $this->entityType = (string) $entityType;
    }

    public function __wakeup(): void
    {
        // construct for validation, but don't store to prevent serialization
        // validation should always be true, as `unserialize` would fail when the class is not available
        new EntityType($this->entityType);
    }

    public function getEntityType(): EntityType
    {
        /*
         * We do not expect a throw here, because it has been validated in @see __construct, __wakeup
         */
        return new EntityType($this->entityType);
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getMappingNodeKey(): MappingNodeKeyInterface
    {
        return $this->mappingNodeKey;
    }

    public function getForeignEntityType(): EntityType
    {
        return $this->getEntityType();
    }

    public function setForeignKey(?string $foreignKey): void
    {
        $this->foreignKey = $foreignKey;

        foreach ($this->getOwners() as $owner) {
            if ($owner->getPrimaryKey() !== $foreignKey) {
                $owner->setPrimaryKey($foreignKey);
            }
        }
    }

    /**
     * @return iterable|DatasetEntityContract[]
     *
     * @psalm-return iterable<array-key, DatasetEntityContract>
     */
    public function getOwners(): iterable
    {
        return $this->owners;
    }

    /**
     * @throws UnsharableOwnerException
     */
    public function addOwner(DatasetEntityContract $owner): void
    {
        if (
            !$this->getForeignEntityType()->equalsObjectType($owner)
            || $owner->getPrimaryKey() !== $this->getForeignKey()
        ) {
            throw new UnsharableOwnerException((string) $this->getForeignEntityType(), $this->getForeignKey(), $owner);
        }

        $this->owners[] = $owner;
        $owner->attach($this);
        $owner->setPrimaryKey($this->getForeignKey());
    }
}
