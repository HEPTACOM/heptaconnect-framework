<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<ClassStringReferenceContract>
 */
class ClassStringReferenceCollection extends AbstractObjectCollection
{
    public function has(ClassStringReferenceContract $classString): bool
    {
        foreach ($this->filter([$classString, 'equals']) as $_) {
            return true;
        }

        return false;
    }

    protected function getT(): string
    {
        return ClassStringReferenceContract::class;
    }
}
