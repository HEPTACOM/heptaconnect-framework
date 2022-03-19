<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection;

/**
 * @extends AbstractTaggedCollection<int>
 */
final class TaggedIntegerCollection extends AbstractTaggedCollection
{
    /**
     * @psalm-return IntegerCollection::class
     */
    protected function getCollectionType(): string
    {
        return IntegerCollection::class;
    }
}
