<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract>
 */
class MappingComponentCollection extends DatasetEntityCollection
{
    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>[]
     * @return string[]
     */
    public function getDatasetEntityClassNames(): array
    {
        /** @var string[] $result */
        /** @psalm-var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>[] $result */
        $result = [];

        /** @var MappingComponentStructContract $mappingComponent */
        foreach ($this->getIterator() as $mappingComponent) {
            if (!\in_array($mappingComponent->getDatasetEntityClassName(), $result, true)) {
                $result[] = $mappingComponent->getDatasetEntityClassName();
            }
        }

        return $result;
    }

    public function getPortalNodeKeys(): PortalNodeKeyCollection
    {
        $preResult = [];

        /** @var MappingComponentStructContract $mappingComponent */
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

        /** @var MappingComponentStructContract $mappingComponent */
        foreach ($this->getIterator() as $mappingComponent) {
            $externalId = $mappingComponent->getExternalId();

            if (!\is_string($externalId)) {
                continue;
            }

            $preResult[$externalId] = $externalId;
        }

        return \array_values($preResult);
    }

    /**
     * @psalm-param $datasetEntityClassName class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     * @psalm-return iterable<\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract>
     */
    public function filterByDatasetEntityClassName(string $datasetEntityClassName): \Generator
    {
        return $this->filter(
            static fn (MappingComponentStructContract $mappingComponent): bool =>
                $mappingComponent->getDatasetEntityClassName() === $datasetEntityClassName
        );
    }

    /**
     * @psalm-return iterable<\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract>
     */
    public function filterByPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): \Generator
    {
        return $this->filter(
            static fn (MappingComponentStructContract $mappingComponent): bool =>
                $mappingComponent->getPortalNodeKey()->equals($portalNodeKey)
        );
    }

    protected function getT(): string
    {
        return MappingComponentStructContract::class;
    }
}
