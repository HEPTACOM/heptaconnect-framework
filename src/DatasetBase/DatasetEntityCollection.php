<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @template T
 * @template-extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract&T>
 * @psalm-consistent-constructor
 */
class DatasetEntityCollection extends AbstractObjectCollection
{
    /**
     * @return \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>[]
     */
    public function groupByType(): iterable
    {
        $groups = [];

        /** @var DatasetEntityContract $item */
        foreach ($this->items as $item) {
            $groups[\get_class($item)][] = $item;
        }

        /** @var DatasetEntityContract[] $group */
        foreach ($groups as $type => $group) {
            yield $type => new self($group);
        }
    }

    protected function getT(): string
    {
        return DatasetEntityContract::class;
    }
}
