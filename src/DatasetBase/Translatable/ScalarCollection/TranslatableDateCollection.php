<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Utility\Date\DateCollection;

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
