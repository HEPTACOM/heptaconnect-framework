<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\ClassString\Contract;

abstract class ClassStringReferenceContract implements \JsonSerializable, \Stringable
{
    public function __construct(
        private readonly string $classString
    ) {
    }

    final public function __toString(): string
    {
        return $this->classString;
    }

    /**
     * Compares this with a different instance of a class string.
     * Returns true, when both class strings are equal.
     */
    final public function equals(ClassStringReferenceContract $other): bool
    {
        return (string) $other === (string) $this;
    }

    final public function jsonSerialize(): string
    {
        return $this->classString;
    }
}
