<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\Date;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection;

/**
 * @extends AbstractTaggedCollection<Date>
 */
final class TaggedDateCollection extends AbstractTaggedCollection
{
    protected function getCollectionType(): string
    {
        return DateCollection::class;
    }
}
