<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

abstract class ClassStringReferenceContract implements \JsonSerializable
{
    private const NAMESPACE_SEPARATOR = '\\';

    /**
     * @var class-string
     */
    private string $classString;

    /**
     * @param class-string $classString
     */
    public function __construct(string $classString)
    {
        $this->classString = $classString;
    }

    /**
     * @return class-string
     */
    final public function __toString()
    {
        return $this->classString;
    }

    /**
     * Returns the class string without leading namespace separator.
     *
     * @return class-string
     */
    final public function getClassString(): string
    {
        return \ltrim($this->classString, self::NAMESPACE_SEPARATOR);
    }

    /**
     * Returns the class string with leading namespace separator.
     *
     * @return class-string
     */
    final public function getAbsoluteClassString(): string
    {
        return self::NAMESPACE_SEPARATOR . $this->getClassString();
    }

    /**
     * Compares canonical this with a different instance of a class string.
     * Returns true, when both class strings refer to the same class.
     */
    final public function equals(ClassStringReferenceContract $other): bool
    {
        return $other->getClassString() === $this->getClassString();
    }

    /**
     * Compares this with a different instance of a class string taking into account whether a leading namespace separator has been provided originally.
     * Returns true, when both class strings refer to the same class in the same way.
     */
    final public function same(ClassStringReferenceContract $other): bool
    {
        return (string) $other === (string) $this;
    }

    /**
     * @return class-string
     */
    final public function jsonSerialize()
    {
        return $this->classString;
    }
}
