<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection;

/**
 * @extends AbstractTaggedCollection<float>
 */
class TaggedFloatCollection extends AbstractTaggedCollection
{
    /**
     * @psalm-return FloatCollection::class
     */
    protected function getCollectionType(): string
    {
        return FloatCollection::class;
    }
}
