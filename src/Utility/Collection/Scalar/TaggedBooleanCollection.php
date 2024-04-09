<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Scalar;

use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;

/**
 * @extends AbstractTaggedCollection<bool>
 */
final class TaggedBooleanCollection extends AbstractTaggedCollection
{
    protected function getCollectionType(): string
    {
        return BooleanCollection::class;
    }
}
