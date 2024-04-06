<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;

/**
 * @extends AbstractTranslatableScalarCollection<DateCollection>
 */
final class TranslatableDateCollection extends AbstractTranslatableScalarCollection
{
    protected function getInitialValue(): CollectionInterface
    {
        return new DateCollection();
    }
}
