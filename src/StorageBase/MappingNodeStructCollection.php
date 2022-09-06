<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface;

/**
 * @extends AbstractObjectCollection<MappingNodeStructInterface>
 */
class MappingNodeStructCollection extends AbstractObjectCollection
{
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (MappingNodeStructInterface $a, MappingNodeStructInterface $b): bool => $a->getKey()->equals($b->getKey())
        );
    }

    public function asUnique(): self
    {
        return $this->uniqueByContains();
    }

    /**
     * @psalm-return Contract\MappingNodeStructInterface::class
     */
    protected function getT(): string
    {
        return MappingNodeStructInterface::class;
    }
}
