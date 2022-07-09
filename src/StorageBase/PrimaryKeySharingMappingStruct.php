<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassString;
use Heptacom\HeptaConnect\Dataset\Base\Support\ForeignKeyTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsharableOwnerException;

final class PrimaryKeySharingMappingStruct implements AttachableInterface, ForeignKeyAwareInterface, MappingInterface
{
    use ForeignKeyTrait;

    /**
     * @var class-string<DatasetEntityContract>
     */
    protected string $entityType;

    protected ?string $externalId = null;

    protected PortalNodeKeyInterface $portalNodeKey;

    protected MappingNodeKeyInterface $mappingNodeKey;

    /**
     * @var DatasetEntityContract[]
     */
    protected $owners = [];

    public function __construct(
        EntityTypeClassString $entityType,
        ?string $externalId,
        PortalNodeKeyInterface $portalNodeKey,
        MappingNodeKeyInterface $mappingNodeKey
    ) {
        $this->entityType = (string) $entityType;
        $this->externalId = $externalId;
        $this->portalNodeKey = $portalNodeKey;
        $this->mappingNodeKey = $mappingNodeKey;
    }

    public function __wakeup()
    {
        // construct for validation, but don't store to prevent serialization
        // validation should always be true, as `unserialize` would fail when the class is not available
        new EntityTypeClassString($this->entityType);
    }

    public function getEntityType(): EntityTypeClassString
    {
        /**
         * We do not expect a throw here, because it has been validated in @see __construct, __wakeup
         */
        return new EntityTypeClassString($this->entityType);
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

    public function getForeignEntityType(): EntityTypeClassString
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
            !$this->getForeignEntityType()->sameObjectType($owner)
            || $owner->getPrimaryKey() !== $this->getForeignKey()
        ) {
            throw new UnsharableOwnerException($this->getForeignEntityType()->getClassString(), $this->getForeignKey(), $owner);
        }

        $this->owners[] = $owner;
        $owner->attach($this);
        $owner->setPrimaryKey($this->getForeignKey());
    }
}
