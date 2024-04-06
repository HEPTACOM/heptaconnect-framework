<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Utility\Contract\CollectionInterface;

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
