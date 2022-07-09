<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException;
use Heptacom\HeptaConnect\Dataset\Base\Support\UnsafeClassString;

/**
 * @template T
 *
 * @psalm-method class-string<T> __toString()
 * @psalm-method class-string<T> getClassString()
 * @psalm-method class-string<T> getAbsoluteClassString()
 * @psalm-method class-string<T> jsonSerialize()
 */
abstract class ClassStringContract extends ClassStringReferenceContract
{
    /**
     * @param class-string<T> $classString
     *
     * @throws InvalidClassNameException
     */
    public function __construct(string $classString)
    {
        parent::__construct($classString);

        if (!\class_exists($this->getClassString()) && !\interface_exists($this->getClassString())) {
            throw new InvalidClassNameException($classString, 1655559295);
        }
    }

    /**
     * Compares the given class-string to be equal or a subtype of this represented class string.
     */
    final public function matchClassStringIsOfType(ClassStringReferenceContract $classString): bool
    {
        return \is_a($classString->getClassString(), $this->getClassString(), true);
    }

    /**
     * Compares the given object to be of type or a subtype of this represented class string.
     */
    final public function matchObjectIsOfType(object $object): bool
    {
        return \is_a($object, $this->getClassString(), false);
    }

    /**
     * Compares the given object to be of type of this canonical class string.
     */
    final public function matchObjectEqualsType(object $object): bool
    {
        return $this->equals(new UnsafeClassString(\get_class($object)));
    }

    /**
     * Compares the given object to be of type of this canonical class string.
     */
    final public function matchObjectSameType(object $object): bool
    {
        return $this->same(new UnsafeClassString(\get_class($object)));
    }
}
