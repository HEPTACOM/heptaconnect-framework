<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;
use Heptacom\HeptaConnect\Utility\Date\Date;
use Heptacom\HeptaConnect\Utility\Date\DateCollection;

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
