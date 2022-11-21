<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<DatasetEntityContract>
 */
class DatasetEntityCollection extends AbstractObjectCollection
{
    /**
     * @return iterable<class-string<DatasetEntityContract>, static>
     */
    public function groupByType(): iterable
    {
        /** @var array<class-string<DatasetEntityContract>, DatasetEntityContract[]> $groups */
        $groups = [];

        /** @var DatasetEntityContract $item */
        foreach ($this->items as $item) {
            $groups[$item::class][] = $item;
        }

        /** @var DatasetEntityContract[] $group */
        foreach ($groups as $type => $group) {
            $grouped = $this->withoutItems();
            $grouped->push($group);

            yield $type => $grouped;
        }
    }

    /**
     * @psalm-return Contract\DatasetEntityContract::class
     */
    protected function getT(): string
    {
        return DatasetEntityContract::class;
    }
}
