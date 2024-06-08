<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\IntegerCollection;

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
