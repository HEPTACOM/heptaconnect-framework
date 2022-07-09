<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException;
use Heptacom\HeptaConnect\Dataset\Base\Exception\UnexpectedLeadingNamespaceSeparatorInClassNameException;

/**
 * @template T
 *
 * @psalm-method class-string<T> __toString()
 * @psalm-method class-string<T> jsonSerialize()
 */
abstract class ClassStringContract extends ClassStringReferenceContract
{
    private const NAMESPACE_SEPARATOR = '\\';

    /**
     * @param class-string<T> $classString
     *
     * @throws InvalidClassNameException
     * @throws UnexpectedLeadingNamespaceSeparatorInClassNameException
     */
    public function __construct(string $classString)
    {
        if (\strncmp($classString, self::NAMESPACE_SEPARATOR, \strlen(self::NAMESPACE_SEPARATOR)) === 0) {
            throw new UnexpectedLeadingNamespaceSeparatorInClassNameException($classString, 1655559294);
        }

        if (!\class_exists($classString) && !\interface_exists($classString)) {
            throw new InvalidClassNameException($classString, 1655559295);
        }

        parent::__construct($classString);
    }

    /**
     * Returns a string, that does not have a leading namespace separator, if it has some, otherwise unchanged.
     * Leading namespace separators are allowed by php in various reflection related contexts, but not suitable for HEPTAconnect.
     */
    final public static function removeLeadingNamespaceSeparator(string $classString): string
    {
        return \ltrim($classString, self::NAMESPACE_SEPARATOR);
    }

    /**
     * Compares the given class-string to be equal or a subtype of this represented class string.
     */
    final public function isClassStringOfType(ClassStringReferenceContract $classString): bool
    {
        return \is_a((string) $classString, (string) $this, true);
    }

    /**
     * Compares the given object to be of type or a subtype of this represented class string.
     */
    final public function isObjectOfType(?object $object): bool
    {
        if (!\is_object($object)) {
            return false;
        }

        return \is_a($object, (string) $this, false);
    }

    /**
     * Compares the given object to be of type of this represented class string.
     */
    final public function equalsObjectType(?object $object): bool
    {
        if (!\is_object($object)) {
            return false;
        }

        return ((string) $this) === \get_class($object);
    }
}
