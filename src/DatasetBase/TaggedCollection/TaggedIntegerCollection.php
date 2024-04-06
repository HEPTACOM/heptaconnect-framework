<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Utility\Collection\Scalar\IntegerCollection;

/**
 * @extends AbstractTaggedCollection<int>
 */
final class TaggedIntegerCollection extends AbstractTaggedCollection
{
    protected function getCollectionType(): string
    {
        return IntegerCollection::class;
    }
}
