<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;

/**
 * @template T of ClassStringReferenceContract
 *
 * @template-extends AbstractObjectCollection<T>
 */
abstract class AbstractClassStringReferenceCollection extends AbstractObjectCollection
{
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (ClassStringReferenceContract $a, ClassStringReferenceContract $b): bool => $a->equals($b)
        );
    }
}
