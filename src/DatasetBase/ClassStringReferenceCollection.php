<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<ClassStringReferenceContract>
 */
class ClassStringReferenceCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return ClassStringReferenceContract::class;
    }
}
