<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection;
use Heptacom\HeptaConnect\Utility\Contract\CollectionInterface;

/**
 * @extends AbstractTranslatableScalarCollection<IntegerCollection>
 */
final class TranslatableIntegerCollection extends AbstractTranslatableScalarCollection
{
    protected function getInitialValue(): CollectionInterface
    {
        return new IntegerCollection();
    }
}
