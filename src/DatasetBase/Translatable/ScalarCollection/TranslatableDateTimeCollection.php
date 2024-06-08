<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Utility\Date\DateTimeCollection;

/**
 * @extends AbstractTranslatableScalarCollection<DateTimeCollection>
 */
final class TranslatableDateTimeCollection extends AbstractTranslatableScalarCollection
{
    protected function getInitialValue(): CollectionInterface
    {
        return new DateTimeCollection();
    }
}
