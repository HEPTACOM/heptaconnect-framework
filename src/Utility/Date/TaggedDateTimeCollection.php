<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Date;

use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;

/**
 * @extends AbstractTaggedCollection<\DateTimeInterface>
 */
final class TaggedDateTimeCollection extends AbstractTaggedCollection
{
    #[\Override]
    protected function getCollectionType(): string
    {
        return DateTimeCollection::class;
    }
}
