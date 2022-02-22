<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateTimeCollection;

/**
 * @extends AbstractTaggedCollection<\DateTimeInterface>
 */
class TaggedDateTimeCollection extends AbstractTaggedCollection
{
    protected function getCollectionType(): string
    {
        return DateTimeCollection::class;
    }
}
