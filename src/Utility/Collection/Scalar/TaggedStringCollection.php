<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Scalar;

use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;

/**
 * @extends AbstractTaggedCollection<string>
 */
final class TaggedStringCollection extends AbstractTaggedCollection
{
    #[\Override]
    protected function getCollectionType(): string
    {
        return StringCollection::class;
    }
}
