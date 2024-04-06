<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Utility\ClassString\Exception\InvalidClassNameException;
use Heptacom\HeptaConnect\Utility\ClassString\Exception\InvalidSubtypeClassNameException;
use Heptacom\HeptaConnect\Utility\ClassString\Exception\UnexpectedLeadingNamespaceSeparatorInClassNameException;

/**
 * @extends ClassStringContract<PortalContract>
 *
 * @psalm-method class-string<PortalContract> __toString()
 * @psalm-method class-string<PortalContract> jsonSerialize()
 */
final class SupportedPortalType extends ClassStringContract
{
    /**
     * @throws InvalidClassNameException
     * @throws InvalidSubtypeClassNameException
     * @throws UnexpectedLeadingNamespaceSeparatorInClassNameException
     */
    public function __construct(string $classString)
    {
        parent::__construct($classString);

        /** @phpstan-var class-string $expectedClass */
        $expectedClass = $this->getExpectedSuperClassName();

        /* Quite similar to @see \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract but allows @see PortalContract itself as well */
        if (!\is_a($classString, $expectedClass, true)) {
            throw new InvalidSubtypeClassNameException($classString, $expectedClass, 1659729321);
        }
    }

    /**
     * Returns the expected class name that the content needs to extend or implement.
     *
     * @returns class-string
     */
    public function getExpectedSuperClassName(): string
    {
        return PortalContract::class;
    }
}
