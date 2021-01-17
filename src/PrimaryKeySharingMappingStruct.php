<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsharableOwnerException;

class PrimaryKeySharingMappingStruct implements AttachableInterface, ForeignKeyAwareInterface, MappingInterface
{
    protected ?string $datasetEntityClassName = null;

    protected ?string $externalId = null;

    protected ?PortalNodeKeyInterface $portalNodeKey = null;

    protected ?MappingNodeKeyInterface $mappingNodeKey = null;

    /**
     * @var array|\WeakReference[]
     * @psalm-var array<array-key, \WeakReference<DatasetEntityInterface>>
     */
    protected $owners = [];

    public function getDatasetEntityClassName(): string
    {
        return $this->datasetEntityClassName;
    }

    public function setDatasetEntityClassName(string $datasetEntityClassName): self
    {
        $this->datasetEntityClassName = $datasetEntityClassName;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->getForeignKey();
    }

    public function setExternalId(?string $externalId): MappingInterface
    {
        $this->setForeignKey($externalId);

        return $this;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): self
    {
        $this->portalNodeKey = $portalNodeKey;

        return $this;
    }

    public function getMappingNodeKey(): MappingNodeKeyInterface
    {
        return $this->mappingNodeKey;
    }

    public function setMappingNodeKey(MappingNodeKeyInterface $mappingNodeKey): self
    {
        $this->mappingNodeKey = $mappingNodeKey;

        return $this;
    }

    public function getForeignDatasetEntityClassName(): string
    {
        return $this->getDatasetEntityClassName();
    }

    public function getForeignKey(): ?string
    {
        return $this->externalId;
    }

    public function setForeignKey(?string $foreignKey): void
    {
        $this->externalId = $foreignKey;
    }

    /**
     * @return iterable|DatasetEntityInterface[]
     * @psalm-return iterable<array-key, DatasetEntityInterface>
     */
    public function getOwners(): iterable
    {
        foreach ($this->owners as $owner) {
            if (($o = $owner->get()) instanceof DatasetEntityInterface) {
                yield $o;
            }
        }
    }

    /**
     * @throws UnsharableOwnerException
     */
    public function addOwner(DatasetEntityInterface $owner): void
    {
        if (\get_class($owner) !== $this->getForeignDatasetEntityClassName()
            || $owner->getPrimaryKey() !== $this->getForeignKey()) {
            throw new UnsharableOwnerException($this->getForeignDatasetEntityClassName(), $this->getForeignKey(), $owner);
        }

        $this->owners[] = \WeakReference::create($owner);
        $owner->attach($this);
        $owner->setPrimaryKey($this->getForeignKey());
    }
}
