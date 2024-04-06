<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateTimeCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;

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
