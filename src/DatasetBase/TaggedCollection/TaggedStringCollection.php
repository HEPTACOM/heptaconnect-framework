<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;

/**
 * @extends AbstractTaggedCollection<string>
 */
final class TaggedStringCollection extends AbstractTaggedCollection
{
    protected function getCollectionType(): string
    {
        return StringCollection::class;
    }
}
