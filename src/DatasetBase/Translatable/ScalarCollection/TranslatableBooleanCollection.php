<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\BooleanCollection;

/**
 * @extends AbstractTranslatableScalarCollection<BooleanCollection>
 */
class TranslatableBooleanCollection extends AbstractTranslatableScalarCollection
{
    protected function getInitialValue(): CollectionInterface
    {
        return new BooleanCollection();
    }
}
