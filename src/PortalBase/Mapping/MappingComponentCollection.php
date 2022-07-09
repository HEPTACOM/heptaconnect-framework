<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;

/**
 * @extends AbstractObjectCollection<MappingComponentStructContract>
 */
class MappingComponentCollection extends AbstractObjectCollection
{
    /**
     * @psalm-return class-string<DatasetEntityContract>[]
     *
     * @return string[]
     */
    public function getEntityTypes(): array
    {
        /** @var string[] $result */
        /** @psalm-var class-string<DatasetEntityContract>[] $result */
        $result = [];

        /** @var MappingComponentStructContract $mappingComponent */
        foreach ($this->getIterator() as $mappingComponent) {
            if (!\in_array($mappingComponent->getEntityType()->getClassString(), $result, true)) {
                $result[] = $mappingComponent->getEntityType()->getClassString();
            }
        }

        return $result;
    }

    public function getPortalNodeKeys(): PortalNodeKeyCollection
    {
        $preResult = [];

        foreach ($this->getIterator() as $mappingComponent) {
            $portalNodeKey = $mappingComponent->getPortalNodeKey();
            $preResult[\json_encode($portalNodeKey)] = $portalNodeKey;
        }

        return new PortalNodeKeyCollection(\array_values($preResult));
    }

    /**
     * @return string[]
     */
    public function getExternalIds(): array
    {
        $preResult = [];

        foreach ($this->getIterator() as $mappingComponent) {
            $preResult[$mappingComponent->getExternalId()] = $mappingComponent->getExternalId();
        }

        return \array_values($preResult);
    }

    /**
     * @psalm-return \Generator<MappingComponentStructContract>
     */
    public function filterByEntityType(EntityType $entityType): \Generator
    {
        return $this->filter(
            static fn (MappingComponentStructContract $mc): bool => $mc->getEntityType()->same($entityType)
        );
    }

    /**
     * @psalm-return \Generator<MappingComponentStructContract>
     */
    public function filterByPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): \Generator
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
