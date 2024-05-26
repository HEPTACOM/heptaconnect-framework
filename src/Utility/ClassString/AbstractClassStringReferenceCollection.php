<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\ClassString;

use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @template T of ClassStringReferenceContract
 *
 * @template-extends AbstractObjectCollection<T>
 */
abstract class AbstractClassStringReferenceCollection extends AbstractObjectCollection
{
    #[\Override]
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (ClassStringReferenceContract $a, ClassStringReferenceContract $b): bool => $a->equals($b)
        );
    }
}
