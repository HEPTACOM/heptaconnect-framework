<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Date;

use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;

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
