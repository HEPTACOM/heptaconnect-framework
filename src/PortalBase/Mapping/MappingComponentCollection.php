<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\EntityTypeCollection;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;

/**
 * @extends AbstractObjectCollection<MappingComponentStructContract>
 */
class MappingComponentCollection extends AbstractObjectCollection
{
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (MappingComponentStructContract $a, MappingComponentStructContract $b): bool => $a->getPortalNodeKey()->equals($b->getPortalNodeKey())
                && $a->getEntityType() === $b->getEntityType()
                && $a->getExternalId() === $b->getExternalId()
        );
    }

    public function unique(): self
    {
        return $this->uniqueByContains();
    }

    /**
     * @psalm-return class-string<DatasetEntityContract>[]
     *
     * @return string[]
     */
    public function getEntityTypes(): array
    {
        $entityTypes = (new EntityTypeCollection($this->column('getEntityType')))->unique()->asArray();

        return \array_map(static fn (EntityType $type): string => (string) $type, $entityTypes);
    }

    public function getPortalNodeKeys(): PortalNodeKeyCollection
    {
        return (new PortalNodeKeyCollection($this->column('getPortalNodeKey')))->unique();
    }

    /**
     * @return string[]
     */
    public function getExternalIds(): array
    {
        return (new StringCollection($this->column('getExternalId')))->unique()->asArray();
    }

    /**
     * @return static
     */
    public function filterByEntityType(EntityType $entityType): self
    {
        return $this->filter(
            static fn (MappingComponentStructContract $mc): bool => $mc->getEntityType()->equals($entityType)
        );
    }

    /**
     * @return static
     */
    public function filterByPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): self
    {
        return $this->filter(
            static fn (MappingComponentStructContract $mc): bool => $mc->getPortalNodeKey()->equals($portalNodeKey)
        );
    }

    protected function getT(): string
    {
        return MappingComponentStructContract::class;
    }
}
