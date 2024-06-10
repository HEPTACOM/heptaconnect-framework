<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<MappingNodeStructInterface>
 */
class MappingNodeStructCollection extends AbstractObjectCollection
{
    #[\Override]
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (MappingNodeStructInterface $a, MappingNodeStructInterface $b): bool => $a->getKey()->equals($b->getKey())
        );
    }

    #[\Override]
    protected function getT(): string
    {
        return MappingNodeStructInterface::class;
    }
}
