<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\BooleanCollection;

/**
 * @extends AbstractTranslatableScalarCollection<BooleanCollection>
 */
final class TranslatableBooleanCollection extends AbstractTranslatableScalarCollection
{
    #[\Override]
    protected function getInitialValue(): CollectionInterface
    {
        return new BooleanCollection();
    }
}
