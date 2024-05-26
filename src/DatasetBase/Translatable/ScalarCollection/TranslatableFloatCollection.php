<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\FloatCollection;

/**
 * @extends AbstractTranslatableScalarCollection<FloatCollection>
 */
final class TranslatableFloatCollection extends AbstractTranslatableScalarCollection
{
    #[\Override]
    protected function getInitialValue(): CollectionInterface
    {
        return new FloatCollection();
    }
}
