<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
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

        foreach ($this->getIterator() as $mappingComponent) {
            if (!\in_array($mappingComponent->getEntityType(), $result, true)) {
                $result[] = $mappingComponent->getEntityType();
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
     * @psalm-param $entityType class-string<DatasetEntityContract>
     * @psalm-return \Generator<MappingComponentStructContract>
     */
    public function filterByEntityType(string $entityType): \Generator
    {
        return $this->filter(
            static fn (MappingComponentStructContract $mc): bool => $mc->getEntityType() === $entityType
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
