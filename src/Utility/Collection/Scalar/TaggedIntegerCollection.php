<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Scalar;

use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;

/**
 * @extends AbstractTaggedCollection<int>
 */
final class TaggedIntegerCollection extends AbstractTaggedCollection
{
    #[\Override]
    protected function getCollectionType(): string
    {
        return IntegerCollection::class;
    }
}
