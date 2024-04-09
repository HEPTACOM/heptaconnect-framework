<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Scalar;

use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;

/**
 * @extends AbstractTaggedCollection<float>
 */
final class TaggedFloatCollection extends AbstractTaggedCollection
{
    protected function getCollectionType(): string
    {
        return FloatCollection::class;
    }
}
