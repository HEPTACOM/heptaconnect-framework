<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;

/**
 * @extends AbstractTranslatableScalarCollection<StringCollection>
 */
final class TranslatableStringCollection extends AbstractTranslatableScalarCollection
{
    protected function getInitialValue(): CollectionInterface
    {
        return new StringCollection();
    }
}
