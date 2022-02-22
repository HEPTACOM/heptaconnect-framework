<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\BooleanCollection;

/**
 * @extends AbstractTaggedCollection<bool>
 */
class TaggedBooleanCollection extends AbstractTaggedCollection
{
    protected function getCollectionType(): string
    {
        return BooleanCollection::class;
    }
}
