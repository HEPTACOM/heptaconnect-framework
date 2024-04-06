<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\FloatCollection;

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
