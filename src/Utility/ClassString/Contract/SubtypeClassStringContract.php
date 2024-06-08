<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\ClassString\Contract;

use Heptacom\HeptaConnect\Utility\ClassString\Exception\InvalidClassNameException;
use Heptacom\HeptaConnect\Utility\ClassString\Exception\InvalidSubtypeClassNameException;
use Heptacom\HeptaConnect\Utility\ClassString\Exception\UnexpectedLeadingNamespaceSeparatorInClassNameException;

/**
 * @template T
 *
 * @extends ClassStringContract<T>
 */
abstract class SubtypeClassStringContract extends ClassStringContract
{
    /**
     * @throws InvalidClassNameException
     * @throws InvalidSubtypeClassNameException
     * @throws UnexpectedLeadingNamespaceSeparatorInClassNameException
     */
    public function __construct(string $classString)
    {
        parent::__construct($classString);

        $expectedClass = $this->getExpectedSuperClassName();

        if (!\is_subclass_of($classString, $expectedClass, true)) {
            throw new InvalidSubtypeClassNameException($classString, $expectedClass, 1655559296);
        }
    }

    /**
     * Returns the expected class name that the content needs to extend or implement but not be itself.
     *
     * @return class-string<T>
     */
    abstract public function getExpectedSuperClassName(): string;
}
