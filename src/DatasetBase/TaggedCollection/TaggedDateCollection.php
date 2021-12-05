<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\AbstractTaggedCollection<\Heptacom\HeptaConnect\Dataset\Base\Date>
 */
class TaggedDateCollection extends AbstractTaggedCollection
{
    protected function getCollectionType(): string
    {
        return DateCollection::class;
    }
}
