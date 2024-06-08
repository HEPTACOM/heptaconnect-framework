<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection;

use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachableInterface;

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