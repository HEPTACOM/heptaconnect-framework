<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException;
use Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidSubtypeClassNameException;

/**
 * @template T
 * @extends ClassStringContract<T>
 */
abstract class SubtypeClassStringContract extends ClassStringContract
{
    /**
     * @param class-string<T> $classString
     *
     * @throws InvalidClassNameException
     * @throws InvalidSubtypeClassNameException
     */
    public function __construct(string $classString)
    {
        parent::__construct($classString);

        $classString = $this->getClassString();
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
