<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\BooleanCollection;

/**
 * @extends AbstractTaggedCollection<bool>
 */
final class TaggedBooleanCollection extends AbstractTaggedCollection
{
    /**
     * @psalm-return BooleanCollection::class
     */
    protected function getCollectionType(): string
    {
        return BooleanCollection::class;
    }
}
