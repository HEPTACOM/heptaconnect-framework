<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;

/**
 * @template T
 *
 * @template-extends AbstractCollection<T>
 */
abstract class AbstractObjectCollection extends AbstractCollection implements AttachableInterface
{
    abstract protected function getT(): string;

    /**
     * @psalm-assert-if-true T $item
     */
    protected function isValidItem(mixed $item): bool
    {
        $expected = $this->getT();

        return \is_object($item) && $item instanceof $expected;
    }
}
