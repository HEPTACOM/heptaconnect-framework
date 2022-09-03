<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;

/**
 * @template T
 * @template-extends AbstractObjectCollection<T>
 */
abstract class AbstractClassStringReferenceCollection extends AbstractObjectCollection
{
    public function has(ClassStringReferenceContract $classString): bool
    {
        foreach ($this->filter([$classString, 'equals']) as $_) {
            return true;
        }

        return false;
    }

    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (ClassStringReferenceContract $a, ClassStringReferenceContract $b): bool => $a->equals($b)
        );
    }

    public function unique(): AbstractCollection
    {
        return $this->uniqueByContains();
    }
}
