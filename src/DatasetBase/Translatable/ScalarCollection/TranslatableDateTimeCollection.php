<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateTimeCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\AbstractTranslatableScalarCollection<\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateTimeCollection>
 */
final class TranslatableDateTimeCollection extends AbstractTranslatableScalarCollection
{
    protected function getInitialValue(): CollectionInterface
    {
        return new DateTimeCollection();
    }
}
