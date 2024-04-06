<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\BooleanCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;

/**
 * @extends AbstractTranslatableScalarCollection<BooleanCollection>
 */
final class TranslatableBooleanCollection extends AbstractTranslatableScalarCollection
{
    protected function getInitialValue(): CollectionInterface
    {
        return new BooleanCollection();
    }
}
